# Local Docker, worktrees and build evidence

Use these commands when preparing, validating or retiring this repository. Python 3, Git, Docker with native
Compose v2 `!override` support, and the target's normal dependency access are prerequisites. Worktrunk is needed
only for guarded source removal and must support `remove --foreground --format=json --yes`.

## Select the checkout

`bin/up` and `bin/down` without a selector always operate on the canonical main checkout. From an isolated
registered worktree, explicitly select it for lifecycle commands. Tooling, Composer and focused wrappers inside
that worktree select its project automatically. Do not set ambient `COMPOSE_FILE` or `COMPOSE_PROJECT_NAME` to
choose a target; the wrapper selects files, project and environment explicitly.

```sh
./bin/up                                      # Main stack
./bin/down                                    # Main down, named data volumes retained
./bin/up --worktree /absolute/registered/path  # One isolated stack
./bin/down --worktree /absolute/registered/path
```

Git registrations resolve paths, branches and unambiguous short names. Ambiguity fails before effects. Each
worktree's project hashes the canonical repository and exact checkout path, so branches may change without
renaming its resources. Worktree ports bind dynamically on loopback; inspect the actual published port with
`./bin/runtime docker compose ps`. Compose health is not proof of an application/browser journey. No shared
ingress enrollment, custom DNS, certificate enrollment, database migrations or imported main data is implicit.
Already-enrolled local projects maintain their host binding and HTTPS preview during startup as described below.

From the selected isolated checkout prepare dependencies/runtime with:

```sh
./bin/up --worktree "$PWD"
./bin/composer install --no-interaction --prefer-dist --no-progress
./bin/build
./bin/build --force                           # Deliberate fresh application gate
./bin/exec php --version
./bin/runtime inspect
```

`bin/up --worktree PATH --build-only` builds images without starting the stack. This repository's tooling
service is `app`. Composer/quality commands retain the local project's dependency and coverage policy.
The app image maps its `systemuser` account to the invoking host UID/GID through local build arguments.
Preparation does not recursively change checkout ownership: canonical `var/worktrees/` configuration stays
private to its host owner, and application dependency/cache files use that same identity.
Main and worktrees have separate images, networks, source mounts and generated output. The native Git common
directory is mounted read-only so linked-worktree locators function without a metadata copy.

## Two caches with different lifetimes

Docker's ordinary shared builder retains downloaded layers and build cache across worktrees. Lifecycle down
never runs a machine-wide prune, clears a shared builder or force-removes an immutable image ID. Isolated down
removes only reserved task image tags, project containers, private networks and disposable named volumes.
Main down preserves named data volumes. Unknown resource labels and foreign consumers, including stopped
containers, hold the affected teardown instead of being treated as disposable.

A normal `bin/build` first runs offline planning validation freshly. The checkout-local `var/build.json` points
to the original successful full-gate receipt and log under ignored `.runs/build/`. Reuse requires identical
source, installed dependencies/lockfile, governing instructions, environment, actual image/container/runtime
identities and generated artifacts, plus unchanged original evidence. Ordinary planning record Markdown is
excluded from application inputs; planning conventions, agent instructions, ADRs, templates and executable
planning inputs remain covered. Symlink targets and bytes/modes are covered. Missing, changed or unreadable
evidence cannot qualify a hit. Concurrent builds refuse the same checkout's lock.

A miss executes the entire existing gate and reports its reason and original exit. `--force` invalidates the
pointer before fresh execution; failures never publish a success. Runtime recreation invalidates reuse even
when image layers are cached. Report fresh planning separately from the named original application receipt;
a cache hit does not provide independent Review/QA acceptance or permit publication/merge by itself.

Ignored `var/worktrees/<project>/` in canonical main contains generated Compose configuration and a locator,
not a second resource inventory or a worker/PID admission ledger. Application boot never depends on `.runs/`.
Keep evidence while consumed; record final results in the existing work record/PR before retiring exact owned
scratch and generated output. Preserve unknown/unique data and unrelated sessions. No new TASK record is
required for this explicitly authorized rollout.

## Retire Docker before source

Run the public `bin/down --worktree PATH` first, independently of source/delivery holds. Repeating it uses
native Docker inventories; unavailable inventory is an error. Preserve the locator until source retirement.
Do not delete source with raw Git/rm, force Worktrunk or bypass its hooks.

After required independent acceptance, exact publication verification and durable outcome recording, run from
the canonical safe checkout:

```sh
./bin/runtime finish --worktree /absolute/registered/path   --outcome /absolute/surviving/outcome.md --published-oid FULL_OID   --pr https://github.com/OWNER/REPOSITORY/pull/NUMBER
```

The outcome must survive outside disposable source. Finish invokes down first and checks the exact live PR,
ref and published OID. The shared guarded remover then requires clean tracked/untracked source, no needed
ignored/private data, no active source consumers and no filesystem protections. It rechecks effective
pre/post-remove hooks in both canonical and target contexts. Only absent hooks or the exact repository hook
in `.config/wt.toml` are admitted; unknown/user/changed hooks retain source without suppressing Docker down.
Native per-invocation consent never persists trust or skips a hook. A canonical checkout missing the supported
`bin/down` must adopt it through normal delivery before removal; do not patch an active main checkout to
circumvent that hold.

Native Worktrunk owns non-force source/ref removal. Verify source, registration, runtime and generated config
absence separately. An exact unchanged `retained_unmerged` local ref is reported as retained, not deleted or
failed cleanup. Never delete a remote head, stop a shared VM, strip protections or archive source routinely.
After successful retirement, repeat using the original absolute path; do not redirect through a surviving ref.

## Runtime-free qualification fixtures

An owning qualification caller can pass `runtime_free_fixture=True` to
`scripts/local_tooling/worktrunk.py`'s guarded `remove` with the fixture's canonical primary, exact linked
checkout and expected commit. This is for generated local Git fixtures. The caller must establish ownership.
The guard requires absent remotes, application declarations, runtime settings and project Docker resources,
successful native inventories and absent effective removal hooks. The existing source, ancestry, consumer
and protection checks still apply. Retry only with the original absolute path. Published delivery and
application or QA environments use the Docker-first path above.

## Local QA and shared HTTPS

Prepare dependencies and start the exact isolated checkout using the commands above. For a browser smoke
check, use the loopback URL reported by `./bin/runtime docker compose ps`. To preview the same running
checkout through shared HTTPS, run from that checkout with an absolute path to a Fight Agent OS supplier
checkout that supports framework previews.

```sh
./bin/ingress certificate --certificate-action setup --supplier "$supplier"
./bin/ingress preview --supplier "$supplier"
```

Operate the shared supplier separately. In Herd mode, render the owned include after certificate setup
and before preview. For a single framework, use `./bin/ingress herd-config --supplier "$supplier"`.
For multiple frameworks, render all consumers together using the supplier's documented command.
Inspect, install, syntax-check and reload the owned Herd include explicitly. See
`docs/engineering/RESOURCE_LIFECYCLE.md` in Fight Agent OS for details.

The framework owns its HTTP service and readiness. Stop this checkout and its gateway together using
`./bin/down --worktree "$PWD"`. Remove its preview leaf explicitly with
`./bin/ingress certificate --certificate-action remove --supplier "$supplier"` after teardown.

## Maintainer ownership

`etc/local-tooling.json` binds the local service set, gate, artifact paths and runtime probes. Update it whenever
those contracts change. `scripts/local_tooling` owns this checkout's adapters. Guarded native removal modules
(`resources.py`, `source.py`, `publication.py`, `worktrunk.py`) were adopted verbatim from Fight Agent OS
`scripts/resource_lifecycle` at `cbdf91562b2a5a8afb1c67cb83f24891f52015c5` (2026-11-02). Runtime selection and
build evidence are local adaptations, not copied provider application services. Changes require explicit
comparison and qualification; no private-path dependency, symlink or automatic synchronization is installed.
Validate these tools directly with native Compose/Worktrunk and normal gates, outside product test suites.

Local development builds set native `BUILDX_NO_DEFAULT_ATTESTATIONS=1` for the build command so cached executable layers retain a stable immutable image identity instead of a timestamped attestation index. Release/publication commands retain their own policy. Layers and actual image IDs remain verified; this does not permit publishing local images.

Use `./bin/runtime docker ps` to list all selected-checkout containers and their published ports, including plain-run tool previews. `docker compose ps` lists Compose services only.

## Automatic local domain registration

The reviewed local Fight installation owns its canonical `var/local-ingress/hosts.json`.
Configure discovery once with that supplier's `bin/local-domain install`, or set
`FIGHT_INGRESS_SUPPLIER` to its absolute checkout path for one invocation. Discovery
uses `fight/local-ingress.json` beneath `XDG_CONFIG_HOME` (default `~/.config`), never
a private author path. DockerProxy is optional; the standard supplier supports its
own loopback front door and scoped DNS. Unenrolled projects retain ordinary startup.

Enroll this app's chosen `local.{domain}` explicitly using the supplier's
`bin/local-domain plan --project-root /absolute/canonical/app --namespace local.example.com
--primary`, then apply the printed plan. `local.example.com` is illustrative; this
starter does not choose a public domain for its users. DNS, trust and project leaf
preparation remain separate explicit operator operations.

Once enrolled, `bin/up --worktree /exact/path --task TASK-NNNNN` updates the shared
local registry, including build-only preparation. Existing bindings remain stable;
new worktrees may infer a TASK only from `feature/task-NNNNN-*` and its actual record.
Normal startup then connects the running app through its exact HTTPS preview.
Main uses its explicitly assigned base name. Missing configured supplier, certificate
or routing prerequisites fail clearly. No fallback origin, silent CA enrollment,
shared service restart or database import occurs.

`bin/ingress` can use the same discovery pointer when `--supplier` is omitted.
The supplier's local-domain preview/remove operation retires bindings explicitly
before source retirement; `bin/down` stops runtime but retains names for restart.
Project leaves cover the base and one-level wildcard; use explicit certificate
`renew`/`restore` and refresh affected gateways, preserving the shared CA.

This adapter rollout was authorized with Fight Agent OS TASK-00589 on 2026-11-10.
Only local tooling changes; no framework product capability or dependency upgrade.
Validate with native commands and this repository's full gate. Independent acceptance,
publication and live installation remain separate from implementation.
