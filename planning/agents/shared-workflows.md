# Shared engineering and workflow binding

For execution and substantive planning, use the installed Agent OS guidance by default with the Yii substitutions
below. [AGENTS.md](../../AGENTS.md), [architecture](../../ARCHITECTURE.md), accepted local records and
[planning conventions](../CONVENTIONS.md) own target policy. This binding adopts instructions, not a claim that
all existing code conforms. For read-only next, use only AGENTS' routing entry and
[lightweight routing](../CONVENTIONS.md#lightweight-work-routing); do not preload this document.

## Resolve sources, not installations

The invoked skill's supplied location identifies the **provider**; the selected Git repository identifies the
**target**, always Yii here. Resolve provider links relative to that actual skill, including nested references.
Never select provider planning because IDs happen to match. Keep shared resources at the provider; do not copy
skills/catalogs, add symlinks, guess a private checkout path or change settings to find them.

These are source locators relative to the resolved provider root, not paths inside Yii:

| Shared authority | Provider resource to load when applicable |
| --- | --- |
| Target/context resolution | `docs/engineering/PROJECT_CONTEXT.md` |
| Engineering defaults and focused quality checks | `docs/engineering/STANDARDS.md`, `docs/engineering/QUALITY.md` |
| Implementation and readable next-step handoffs | `docs/engineering/EXECUTION.md`, `docs/engineering/HANDOFFS.md` |
| Independent acceptance, behavioral QA and publication | `docs/engineering/REVIEW.md`, `docs/engineering/QA.md`, `docs/engineering/LANDING.md` |
| Coordination, roles and evidence layout | `.pi/skills/build/SKILL.md`, `docs/engineering/TEAM.md`, `docs/engineering/SCRATCH.md` |
| Native tools and optional discovery | `harness/pi/FILE_SCREENING_RULE.md` |

Follow the owning skill's references on demand, not a mandatory preload of every checklist. A missing optional
profile/CONTEXT alone is not a blocker. For a missing required skill, template or source, name the exact logical
resource, affected action and provider/target owner; hold that action. Do not invent a fallback or install it.
An unresolved conflict needs the local requirement owner's decision, not a silent weakening of accepted policy.

## Engineering applicability and exceptions

Apply shared ownership/layering, human-first deep interfaces, constructor injection, CQRS intent/fact naming,
safe transport Views/errors, server-side authority, PHP/naming and meaningful contract-test principles.
Dependencies point Adapter → Application → Domain where owned layers exist. Use public package contracts directly;
no aliases around package use cases, bypassed configured buses or speculative handlers/layers for symmetry.
Changes require an accepted local TASK, not a standards-driven bulk refactor.

Yii's [architecture](../../ARCHITECTURE.md) and [domain boundary](domain.md) replace provider application ADR,
foundation and composition examples. Yii owns configuration providers, DI, native routing, HTTP/console entrypoints,
Twig and selected adapters. Do not import Slim Action registration, Symfony Console composition, provider container
modules, PostgreSQL, frontend, TLS/export setup or managed-runtime assumptions. Shared separation of policy,
orchestration and presentation applies without requiring provider file layouts or renaming public contracts.
No local ADR is implied by a provider ADR; propose a scoped local decision when one is actually needed.

**Stronger Yii test policy:** retain exact **100% Unit-only owned-production statement coverage**, including missing
source-file and coverage-ignore rejection, without baselines or suppressions. Combined-suite coverage or the
provider's practical Adapter target cannot substitute. Product suites protect owned PHP contracts and meaningful
package integrations, including PHP providers and composition declarations in the denominator. The requirement
owner clarified for TASK-00009: “wiring-only means bin/* scripts or infrastructure tests, not PHP code. Cover the
PHP code”. Direct tests of those PHP contracts are permitted; do not exclude or manipulate PHP source coverage.
Do not test wrappers, infrastructure/tooling, planning/build/coverage/static-analysis mechanisms, tests of tests,
receipts or framework certification in product suites. Validate those directly with their owning commands;
never seed invalid planning/source fixtures to prove detection. A genuinely distinct acceptance conflict needs
its files, evidence and owning TASK recorded, not revival of the resolved PHP-provider hold.

[TASK-00008](../tasks/00008-TASK.md) owns stable packages/PHPCS compatibility;
[TASK-00009](../tasks/00009-TASK.md) owns gate/test-tooling replacement, including obsolete machinery retirement.
Instruction adoption repairs neither. Do not restore certification infrastructure or historical candidate lanes.
[bin/build](../../bin/build) verifies in the running Compose `app` service through named fail-fast PHP phases.
Prepare explicitly with `./bin/up` and `./bin/composer install --no-interaction --prefer-dist --no-progress`.
The gate never builds images or installs dependencies. It retains planning, FPM, Composer validation, PHP syntax,
PHPCS, PHPStan, Deptrac, Rector and all configured PHPUnit suites once. Unit `CoversClass` attribution alone
contributes to exact coverage; boundary/journey suites declare `CoversNothing`. PHPUnit includes uncovered `src/`
files and disables coverage-ignore metadata; the gate rejects ignore directives and uses the installed standard
`coverage-check` at 100. Disposable no-dev installation/boot is retired, not equivalent to the retained dev-graph
HTTP/console journeys. Keep runtime/tool caches in `var/`; do not import provider cache paths.

## Target resource substitutions

| Provider reference or need | Yii-owned source/substitution |
| --- | --- |
| README, architecture, foundation, ownership ADR, product context | [README](../../README.md), [ARCHITECTURE](../../ARCHITECTURE.md), accepted selected EPIC/TICKET/TASK and relevant [ADRs](../adr/README.md); no `planning/FOUNDATION.md` is required or fabricated |
| Planning, Board, phases and completion | [README](../README.md), [CONVENTIONS](../CONVENTIONS.md), [Board](../tasks/BOARD.md), [Roadmap](../ROADMAP.md), [Wayfinder inventory](../wayfinder/README.md); map tables/frontiers are authored locally, not provider-generated |
| EPIC / TICKET / TASK templates | Local [EPIC](../epics/_EPIC_TEMPLATE.md), [TICKET](../tickets/_TICKET_TEMPLATE.md), [TASK](../tasks/_TASK_TEMPLATE.md) templates; check live/archive identity allocation |
| Wayfinder map / decision templates | Local [map](../wayfinder/_MAP_TEMPLATE.md) and [decision](../wayfinder/tickets/_WAYFINDER_TICKET_TEMPLATE.md); apply [map-wide planning order](../CONVENTIONS.md#map-wide-planning-order) |
| PR body template | No local `.github/pull_request_template.md` exists. Use the body contract below, not a provider application's template |
| Runtime, checks and setup | [Contributing](../../CONTRIBUTING.md), [runtime refresh/isolation](../../README.md#container-runtime-refresh), repository `./bin/build`, `./bin/phpunit`, `./bin/up`, `./bin/down`, `./bin/composer`, `./bin/exec` |
| QA report template and review schema | Resolved provider `docs/engineering/QA_REPORT_TEMPLATE.md` and `REVIEW.md`; substitute Yii identity/requirements and canonical base-owned paths |

The PR body states scope/behavior, TASK and accepted candidate, verification commands/counts/warnings, independent
Review/QA identity and evidence, limitations, and pending publication/checks/human review. Use an accessible,
sanitized Before/After table only for applicable visual evidence; non-visual work records behavioral evidence and
visual N/A. Local ignored paths alone are not published PR evidence. Title: `TASK-NNNNN — <TASK title>`, base:
`develop`. Missing publication/upload capability holds that effect; never invent a URL or commit private evidence.

Before routing, run `./bin/planning-check --check-delivery` under
[live handoff verification](../CONVENTIONS.md#live-handoff-verification). Land runs this bounded read-only mode
at intake, pre-publication and final handoff; verify its newly published subject directly before setting
`delivery: awaiting-merge` and its exact `pr` URL. Never infer a marker from historical prose, `done` or a PR link.
At authorized Work, Land or substantive planning intake run `./bin/planning-check --reconcile-delivery` before
edits. Only that explicit write mode retires verified ended markers/evidence and refreshes views. Read-only
routing resolves integrated merges without writes or routine cleanup; uncertainty holds the exact owner/input.
Preserve the existing manual authored-handoff procedure in conventions. Offline planning/build success is not
evidence that a published PR remains OPEN; no live mode grants publication, fetch, merge or credential setup.

For delegated stages, the coordinator records a private reusable project context: exact target/provider resources,
Git root/remote/common directory, selected checkout/canonical base, branch/base/head, local records/templates,
substitutions/exclusions, commands, completion/hosted policy and evidence paths. Every fresh child reads that
context and target instructions before its provider skill, verifies Git identity, and applies these substitutions
through nested references. A changed target, binding or requirement needs reconciled context/evidence first.

## Skill entry points and boundaries

Use supplied skill/package metadata to locate `.pi/skills/<name>/SKILL.md` at the provider. Skills are guidance,
not permissions. Load the full skill when invoked; recommendations only need its available synopsis/binding.
`next` and `build` have `disable-model-invocation: true`: explicit operator commands are supported even when
omitted from the automatic skill list. If not discoverable, ask for the owning skill's supplied location or
operator-authorized one-process `--skill` loading; do not copy/install it or silently invoke it.

| Category | Target-bound handoff / stop boundary |
| --- | --- |
| `next` | Bounded local routing, one read-only validator, all active work or first executable ready TASK; recommend and stop. It grants no Build/work authority |
| `work`, `review`, `qa`, `land`, `build` | Follow the delivery section below and the owning provider contracts; target identity never changes |
| `grill`, `to-tickets`, `to-tasks` | One approved EPIC interview, accepted requirement decomposition, then accepted vertical TASK decomposition; local templates/approval checkpoints, no implementation side effect |
| `wayfinder`, `fix` | One authored unblocked decision, or bounded diagnosis/proposed standalone bug TASK. Decision closure/draft diagnosis is not implementation acceptance or repair authority |
| `adopt`, `audit`, `architecture`, `security-audit` | Scoped comparison, assessment or options against accepted Yii rules/public contracts; disclose sampling/uncertainty. No source repair, automatic records, scanner install or publication |
| `research` | Cited primary-source evidence to the owning local decision; durable Wayfinder notes in `planning/wayfinder/research/`, task execution evidence in canonical task runs |
| `design`, `prototype`, `design-review` | Disposable evidence under `.runs/prototypes/<scope>/`, separate independent design verdict; no production promotion or implementation authorization |
| `graphify` | Existing graph is derived navigation, not planning/source authority. Verify claims in source; no implicit graph build, hook/watch setup, install or external processing |
| `writing-for-agents` | Required for instruction edits; reconcile callers/authorities, precise triggers/stops, direct normal/failure walkthroughs and readable handoffs |
| `release` | Unavailable for mutation without separately accepted local release scope and explicit effect authority; no tags, signing, Packagist, template/create-project enablement or deployment |

## Canonical evidence and delivery

Resolve the canonical base through Git common-directory and registered-worktree metadata, not a matching remote
or guessed directory. The [local evidence layout](../CONVENTIONS.md#execution-evidence) supersedes the old dated
coordinate-build rule **for new runs only**. Never relocate, delete or rewrite historical/in-flight evidence to
conform. Runtime remains `var/`, not `.runs/`. Preserve another owner's reports and unrelated checkout changes.

New TASK branches start at `develop` as `feature/task-NNNNN-<slug>` after explicit main-checkout versus isolated
worktree choice; preserve an existing authorized branch. Follow [local completion](../CONVENTIONS.md#automatic-parent-completion)
and explicit-only archiving. End handoffs with a concrete `Next:` command/owner using real IDs; a recommendation
never dispatches a stage.

- **Work:** implements only accepted scope, verifies and commits owned changes, then stops for independent Review.
  Read current canonical reports for revisions; missing reports are normal on first implementation.
- **Review:** an independent non-contributor writes canonical base `.runs/reviews/<TASK-ID>/review.md` atomically
  and reads it back. Use version 3 identity (TASK, branch, full base/head, status, verdict), separate Spec/Standards
  passes, every TASK criterion and evidence/limitations. Version 2 is usable only with matching identity/snapshot.
  Stale/wrong-subject/ambiguous reports need reconciliation or reassessment, never a guessed acceptance.
- **QA:** independently challenges reviewed observable behavior, including executable instructions without a UI.
  Only QA writes `.runs/qa/<TASK-ID>/qa.md`, preserving run history and publishing initial INCOMPLETE for reruns.
  Verify subject/runtime, scenarios and evidence; PASS, FAIL, INCOMPLETE and criterion-specific N/A are distinct.
  Current FAIL beats an older accept/PASS; missing tools/proof is not N/A or a license to repair source.
- **Land:** after applicable independent acceptance and QA, performs authorized non-force publication/reconciliation
  and ownership-proven cleanup. Preserve report bytes and provenance bridges; semantic changes need new Review
  and affected QA. An explicit Land invocation authorizes its bounded publication, not approval, merge, force
  push, archive, release or deployment. Work/Review/QA alone never imply publication authority.
- **Build coordination:** explicit invocation delegates those stages, owned commits, non-force push/PR and suitable
  sanitized capture uploads for one TASK, not implementation by the coordinator or self-acceptance. Use the owning
  provider monitor, fresh independent sessions and a private base-owned `.runs/build/<TASK-ID>/` ledger/active lock.
  Record authority, checkout, exact pinned per-role models/thinking, sessions, reports, effects and continuation.
  Default allowance is five shared repair cycles (explicit positive allowance at most ten). Initial implementation
  is not a repair. Record consumption before repair; QA failure, fresh directories or resume never reset it.
  Reuse the original ledger/pins; reconcile uncertain child liveness before continuation. Exhaustion needs human
  authority; evidence recovery does not consume a repair but unchanged failures are a prerequisite hold.

Run focused owning checks, refresh authorized planning with `./bin/planning-check --write`, validate read-only,
run `git diff --check` and the full current `./bin/build` before each commit/PR. Use AGENTS' screen/log/exit-file
recipe for long runs, with unique TASK-owned names; only a real exit file `0` plus inspected logs proves a pass.
Record commands, counts, denominator, warnings, skips and omissions. A green gate is not independent acceptance.
Keep TASK in progress until acceptance and required verification are complete; closure rolls eligible TICKET/EPIC
parents up in the same operation, with refreshed views, not a separate closeout approval.

Hosted CI is optional unless an accepted local requirement explicitly requires it; a workflow file alone adds no
acceptance prerequisite. Preserve actual required hosted/merge checks and report them separately from local proof.
Before acceptance, missing **required** hosted proof alone routes to authorized draft Land then focused independent
Review, not empty repair commits. For accepted candidate A and permitted administrative closeout B, Land verifies
the A → B bridge and required final-head delivery proof without rewriting Review or looping through status commits.
An existing literal-final-head acceptance contradiction needs the requirement owner's amendment before completion;
never reinterpret it silently or use A's run for B. Missing publication authority is a human handoff.

## Pi and optional harness capabilities

Harness installation and skill discovery are separate. The provider harness manifest may supply extensions but
not skills; no project-local `.pi/` copy is required. Resolve actual available skills from supplied metadata,
configured resources or explicit operator paths. Consult the installed Pi documentation for the active version
(`skills.md`, `packages.md`, `configuration.md`, `cli.md`, `security.md`; lifecycle: `extensions.md`, `json.md`).
Pi discovers context from its agent/working/parent directories, not arbitrary nested directories merely because
an agent reads their files. Follow explicit local references such as `planning/agents/`; do not assume auto-load.

Jev enrollment/setup is optional and independent of instruction adoption. Ordinary native read/write/edit/bash
need no Jev setup, disclosure, screening receipt or per-command confirmation. Use concretely needed direct reads;
prefer available optional relevance tools for unfamiliar discovery under the provider rule. Preserve actual
operator-selected tool policy and OS denials; never change settings, credentials, models or executor to bypass
one. Skills and role labels do not enforce native tool permissions.

Fresh processes/roles separate context, not OS permissions, filesystem, credentials or network. Installed JSON mode,
fresh session directories and notification-only `agent_settled` observation do not promise sandboxing, managed
durability or an observer pane. A walkthrough proves instruction routing only, not live delegated execution,
provider-reported model identity or independent Review/QA. No installation, enrollment, paid qualification child,
source disclosure or model change follows merely from adopting or verifying these instructions.
