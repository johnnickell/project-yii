#!/usr/bin/env python3
"""Validate Yii planning offline or explicitly refresh generated views."""

from __future__ import annotations

import argparse
import os
import re
import subprocess
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
PLANNING = ROOT / "planning"
VALID_STATUSES = {
    "needs-triage", "needs-info", "ready-for-agent", "ready-for-human",
    "in-progress", "done", "wontfix",
}
TERMINAL = {"done", "wontfix"}
LEVELS = {"epics": "EPIC", "tickets": "TICKET", "tasks": "TASK"}
LINK = re.compile(r"\!?\[[^\]]*\]\(([^\s)]+)")


def read_text(path: Path) -> str:
    # Administrative writes must retain unrelated bytes, including CRLF/mixed line endings.
    return path.read_bytes().decode("utf-8")


def frontmatter(path: Path) -> dict[str, str]:
    return parse_frontmatter(read_text(path))


def parse_frontmatter(text: str) -> dict[str, str]:
    text = text.replace("\r\n", "\n").replace("\r", "\n")
    if not text.startswith("---\n") or "\n---\n" not in text[4:]:
        raise ValueError("missing or unterminated frontmatter")
    block = text[4:].split("\n---\n", 1)[0]
    values: dict[str, str] = {}
    for line in block.splitlines():
        key, separator, value = line.partition(":")
        if not separator or key.strip() in values:
            raise ValueError(f"invalid or duplicate frontmatter line: {line}")
        values[key.strip()] = value.strip()
    return values


def markdown_documents() -> list[Path]:
    # Never inspect or rewrite vendor, runtime, ignored scratch, or another worktree.
    result = subprocess.run(
        ["git", "-c", f"safe.directory={ROOT.resolve()}", "ls-files", "--cached", "--others",
         "--exclude-standard", "-z", "--", "*.md"],
        cwd=ROOT, check=True, capture_output=True,
    )
    return sorted({ROOT / os.fsdecode(name) for name in result.stdout.split(b"\0")
                   if name and (ROOT / os.fsdecode(name)).is_file()})


def blockers(data: dict[str, str]) -> list[str]:
    return [value.strip() for value in data.get("blocked_by", "").split(",") if value.strip()]


def archived(path: Path) -> bool:
    return "archive" in path.relative_to(PLANNING).parts


def priority(record: tuple[Path, dict[str, str]]) -> tuple[bool, int, str]:
    data = record[1]
    order = data.get("order")
    return (not order, int(order) if order else 0, data["id"])


def link(source: Path, destination: Path, label: str) -> str:
    relative = os.path.relpath(destination, source.parent).replace(os.sep, "/")
    return f"[{label.replace('|', '&#124;')}]({relative})"


def table(source: Path, rows: list[tuple[Path, dict[str, str]]], records: dict) -> str:
    if not rows:
        return "None."
    lines = ["| Order | ID | Title | Parent | Status | Blocked by | PR |",
             "| --- | --- | --- | --- | --- | --- | --- |"]
    for path, data in sorted(rows, key=priority):
        parent_id = data.get("ticket") or data.get("epic")
        parent = "—"
        if parent_id:
            parent_path, parent_data = records[parent_id]
            parent = link(source, parent_path, f"{parent_id} — {parent_data['title']}")
        dependencies = ", ".join(link(source, records[item][0], item) for item in blockers(data)) or "—"
        cells = [data.get("order") or "—", link(source, path, data["id"]),
                 data["title"].replace("|", "&#124;"), parent, data["status"], dependencies,
                 data.get("pr") or "—"]
        lines.append("| " + " | ".join(cells) + " |")
    return "\n".join(lines)


def task_groups(records: dict) -> dict[str, list]:
    """One authoritative classification and priority order for TASK tables and routing."""
    sections: dict[str, list] = {name: [] for name in
                               ("active", "ready", "waiting", "needs-info", "human", "triage", "closed")}
    tasks = sorted((row for row in records.values()
                    if row[1]["id"].startswith("TASK-") and not archived(row[0])), key=priority)
    for row in tasks:
        data = row[1]
        status = data["status"]
        if status == "ready-for-agent":
            section = "waiting" if any(records[item][1]["status"] not in TERMINAL
                                       for item in blockers(data)) else "ready"
        else:
            section = {"in-progress": "active", "needs-info": "needs-info", "ready-for-human": "human",
                       "needs-triage": "triage", "done": "closed", "wontfix": "closed"}[status]
        sections[section].append(row)
    return sections


def routing_summary(source: Path, groups: dict[str, list], records: dict) -> str:
    labels = {"active": "Active Work", "ready": "Ready Frontier", "waiting": "Waiting",
              "needs-info": "Needs Info", "human": "Human Action", "triage": "Needs Triage"}
    selected = [(name, row) for name in ("human", "needs-info") for row in groups[name]]
    if groups["active"]:
        selected.extend(("active", row) for row in groups["active"])
    elif groups["ready"]:
        selected.append(("ready", groups["ready"][0]))
    else:
        selected.extend((name, row) for name in ("triage", "waiting") for row in groups[name][:1])
    lines = ["| Route | TASK | Title | Status | Unresolved blockers |",
             "| --- | --- | --- | --- | --- |"]
    for name, (path, data) in selected:
        unresolved = [item for item in blockers(data) if records[item][1]["status"] not in TERMINAL]
        cells = [labels[name], link(source, path, data["id"]), data["title"].replace("|", "&#124;"),
                 data["status"], ", ".join(link(source, records[item][0], item) for item in unresolved) or "—"]
        lines.append("| " + " | ".join(cells) + " |")
    if not groups["active"] and not groups["ready"]:
        lines.extend(["", "No TASK is currently executable; unresolved work may remain."])
    return "\n".join(lines)


def generated_views(records: dict) -> dict[Path, dict[str, str]]:
    live = [(path, data) for path, data in records.values() if not archived(path)]
    views: dict[Path, dict[str, str]] = {}
    for directory, prefix in LEVELS.items():
        index = PLANNING / directory / "README.md"
        views[index] = {"records": table(index, [row for row in live if row[1]["id"].startswith(prefix + "-")], records)}
        archive_index = PLANNING / directory / "archive/README.md"
        archive_rows = [row for row in records.values() if archived(row[0]) and row[1]["id"].startswith(prefix + "-")]
        if archive_rows or archive_index.exists():
            views[archive_index] = {"records": table(archive_index, archive_rows, records)}
    for path, data in live:
        if not data["id"].startswith("TASK-"):
            children = [row for row in records.values() if (row[1].get("epic") or row[1].get("ticket")) == data["id"]]
            views[path] = {"children": table(path, children, records)}

    board = PLANNING / "tasks/BOARD.md"
    sections = task_groups(records)
    views[board] = {name: table(board, rows, records) for name, rows in sections.items()}
    views[board]["next"] = routing_summary(board, sections, records)

    roadmap = PLANNING / "ROADMAP.md"
    frontier = []
    for row in live:
        data = row[1]
        if data["id"].startswith("TASK-") or data["status"] in TERMINAL:
            continue
        children = [child for _, child in records.values() if (child.get("epic") or child.get("ticket")) == data["id"]]
        if not children:
            reason = "Needs decomposition"
            frontier.append(f"- {link(roadmap, row[0], data['id'] + ' — ' + data['title'])}: {reason}.")
    views[roadmap] = {
        "epics": table(roadmap, [row for row in live if row[1]["id"].startswith("EPIC-")], records),
        "frontier": "\n".join(frontier) or "None.",
        "execution": routing_summary(roadmap, sections, records),
    }
    return views


def complete_parents(records: dict) -> dict[Path, str]:
    """Plan bottom-up parent closure from all live and archived child records."""
    pending = {}
    for kind, parent_key in (("TICKET-", "ticket"), ("EPIC-", "epic")):
        for identifier, (path, data) in sorted(records.items()):
            if not identifier.startswith(kind) or "archive" in path.parts or data["status"] in TERMINAL:
                continue
            children = [child for _, child in records.values() if child.get(parent_key) == identifier]
            if not children or any(child["status"] not in TERMINAL for child in children):
                continue
            status = "wontfix" if all(child["status"] == "wontfix" for child in children) else "done"
            text = read_text(path)
            header, boundary, body = re.split(r"((?:\r\n|\r|\n)---(?:\r\n|\r|\n))", text, maxsplit=1)
            lines = []
            for line in header.splitlines(keepends=True):
                key, separator, value = line.partition(":")
                if separator and key.strip() == "status":
                    ending = re.search(r"(?:\r\n|\r|\n)$", value)
                    line = key + ": " + status + (ending[0] if ending else "")
                lines.append(line)
            planned = "".join(lines) + boundary + body
            if parse_frontmatter(planned) != {**data, "status": status}:
                raise ValueError(f"{path.relative_to(ROOT)}: planned parent closure changed unexpected metadata")
            pending[path] = planned
            data["status"] = status
    return pending


def newline(text: str) -> str:
    match = re.search(r"\r\n|\r|\n", text)
    return match[0] if match else "\n"


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__)
    mode = parser.add_mutually_exclusive_group()
    mode.add_argument("--write", action="store_true", help="close eligible parents and refresh generated views")
    args = parser.parse_args()
    writing = args.write
    errors: list[str] = []
    records: dict[str, tuple[Path, dict[str, str]]] = {}
    for directory, prefix in LEVELS.items():
        for path in sorted((PLANNING / directory).rglob("*.md")):
            if path.name.startswith("_") or path.name in {"README.md", "BOARD.md"}:
                continue
            try:
                data = frontmatter(path)
            except ValueError as exception:
                errors.append(f"{path.relative_to(ROOT)}: {exception}")
                continue
            identifier = data.get("id", "")
            if not re.fullmatch(prefix + r"-\d{5}", identifier):
                errors.append(f"{path.relative_to(ROOT)}: invalid id {identifier!r}")
            elif path.name != f"{identifier.split('-')[1]}-{prefix}.md":
                errors.append(f"{path.relative_to(ROOT)}: filename does not match {identifier}")
            if identifier in records:
                errors.append(f"duplicate id: {identifier}")
            if data.get("status") not in VALID_STATUSES:
                errors.append(f"{path.relative_to(ROOT)}: invalid status {data.get('status')!r}")
            if not data.get("title"):
                errors.append(f"{path.relative_to(ROOT)}: missing title")
            if data.get("order") and not re.fullmatch(r"[1-9]\d*", data["order"]):
                errors.append(f"{path.relative_to(ROOT)}: order must be a positive integer")
            if data.get("pr") and not re.fullmatch(r"https://[^\s|]+/pull/\d+", data["pr"]):
                errors.append(f"{path.relative_to(ROOT)}: pr must be a full PR URL")
            if archived(path) and data.get("status") not in TERMINAL:
                errors.append(f"{path.relative_to(ROOT)}: archived record is not terminal")
            if "prd" in data:
                errors.append(f"{path.relative_to(ROOT)}: obsolete prd parent field")
            records[identifier] = (path, data)

    for identifier, (path, data) in records.items():
        prefix = identifier.split("-")[0]
        parent_field, parent_prefix = {"TICKET": ("epic", "EPIC"), "TASK": ("ticket", "TICKET")}.get(prefix, ("", ""))
        for field in ("epic", "ticket"):
            if field in data and field != parent_field:
                errors.append(f"{identifier}: unexpected parent field {field}")
        if prefix == "TASK" and data.get("kind") not in {"feature", "bug", "chore"}:
            errors.append(f"{identifier}: kind must be feature, bug, or chore")
        if parent_field:
            parent = data.get(parent_field, "")
            standalone = prefix == "TASK" and data.get("kind") in {"bug", "chore"} and parent_field in data and not parent
            if not standalone and (not parent.startswith(parent_prefix + "-") or parent not in records):
                errors.append(f"{identifier}: missing or invalid {parent_field} parent {parent!r}")
            elif parent in records and records[parent][1].get("status") in TERMINAL and data.get("status") not in TERMINAL:
                errors.append(f"{identifier}: unfinished child of terminal {parent}")
        if prefix != "TASK" and blockers(data):
            errors.append(f"{identifier}: executable blocked_by edges belong to TASKs")
        for blocker in blockers(data):
            if not blocker.startswith("TASK-") or blocker not in records:
                errors.append(f"{identifier}: unknown or non-TASK blocker {blocker}")

    visiting: set[str] = set()
    visited: set[str] = set()

    def visit(identifier: str) -> None:
        if identifier in visiting:
            errors.append(f"dependency cycle at {identifier}")
            return
        if identifier in visited or identifier not in records:
            return
        visiting.add(identifier)
        for blocker in blockers(records[identifier][1]):
            visit(blocker)
        visiting.remove(identifier)
        visited.add(identifier)

    for identifier in records:
        visit(identifier)

    for path in markdown_documents():
        if path.name.startswith("_"):
            continue
        for target in LINK.findall(path.read_text(encoding="utf-8")):
            destination = target.partition("#")[0]
            if not destination.endswith(".md") or destination.startswith(("/", "http:", "https:", "mailto:")):
                continue
            if not (path.parent / destination).resolve().is_file():
                errors.append(f"{path.relative_to(ROOT)}: broken local Markdown link {target}")

    ignored = subprocess.run(
        ["git", "-c", f"safe.directory={ROOT.resolve()}", "check-ignore", "-q", ".runs/planning-check"],
        cwd=ROOT, check=False,
    )
    if ignored.returncode != 0:
        errors.append(".runs/ must be gitignored")

    updates: dict[Path, str] = {}
    if not errors:
        try:
            updates = complete_parents(records)
        except ValueError as exception:
            errors.append(str(exception))
        if not writing and updates:
            errors.append("parent completion out of sync; run ./bin/planning-check --write")
        for path, sections in generated_views(records).items():
            original = (
                read_text(path) if path.exists() else
                f"# {path.parent.parent.name.title()} archive\n\n<!-- planning:records -->\n<!-- /planning:records -->\n"
            )
            text = updates.get(path, original)
            for name, content in sections.items():
                start, end = f"<!-- planning:{name} -->", f"<!-- /planning:{name} -->"
                if text.count(start) != 1 or text.count(end) != 1 or text.index(start) > text.index(end):
                    errors.append(f"{path.relative_to(ROOT)}: missing or duplicate generated markers for {name}")
                    continue
                before, rest = text.split(start, 1)
                _, after = rest.split(end, 1)
                eol = newline(original)
                text = f"{before}{start}{eol}{content.replace(chr(10), eol)}{eol}{end}{after}"
            if text != original or not path.exists():
                updates[path] = text
                if not writing:
                    errors.append(f"{path.relative_to(ROOT)}: stale view; run ./bin/planning-check --write")
    if errors:
        print("Planning validation failed:")
        for error in errors:
            print(f"- {error}")
        return 1
    for path, text in updates.items():
        path.parent.mkdir(parents=True, exist_ok=True)
        path.write_bytes(text.encode("utf-8"))
    active = sum(data["status"] not in TERMINAL for _, data in records.values())
    print(f"Planning validation passed (offline): {len(records)} records, {active} active; {len(updates)} files refreshed")
    return 0


if __name__ == "__main__":
    sys.exit(main())
