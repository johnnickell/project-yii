# Local Development Runtime Contract

**Labels:** `wayfinder:grilling`
**Mode:** HITL
**Status:** Closed
**Gate:** —
**Map:** [Yii AccessControl Starter Application](../yii-access-control-application-map.md)
**Depends on:** —

## Question

What complete, reproducible, project-owned local runtime contract should the Yii starter promise to contributors,
while leaving shared ingress and isolated TASK/worktree execution to Fight Agent OS?

## Must decide

- Compose topology for MySQL, Redis, Nginx, PHP-FPM, PHP-CLI Messenger workers, Cron scheduling, and Mercure SSE,
  including which processes share an image and which require distinct service lifecycles.
- Checked-in `.env.example` names and safe local defaults, required secrets, shared hostname ingress, and the
  ownership boundary between the project stack and upstream isolated TASK/worktree execution.
- Named versus bind-mounted volumes, ownership and permission behavior, cache/vendor/client dependency treatment,
  database and Redis durability, and project-owned teardown semantics.
- Service health checks and dependency readiness, distinguishing container health, application readiness,
  migration readiness, worker readiness, and Mercure reachability.
- Thin Compose startup and teardown through repository-owned `./bin/up` and `./bin/down`, separated from explicit
  first-run setup, dependency installation, migrations, fixtures/bootstrap, and application verification through
  repository-owned commands such as `./bin/composer` and `./bin/exec`.
- Nginx-to-FPM routing, public document root, development asset serving, CLI/worker configuration parity, Cron
  invocation cadence, and local TLS/CORS expectations if any.
- The exact local-only readiness journey and cleanup guarantees; no production topology or readiness claim.
- Swagger UI and operational dashboards remain local-only by default and fail closed outside development.

## Acceptance evidence

- A service/port/volume/health matrix with explicit project ownership and an upstream execution-isolation boundary.
- Bootstrap, steady-state, failure, restart, and teardown sequences that can become executable acceptance journeys.
- Decisions for stale containers, conflicting ports, missing secrets, unhealthy dependencies, and interrupted setup.

## Resolution boundary

This ticket settles the local-development runtime contract and the seams later tickets may implement. It does not
create Compose files, start containers, choose production infrastructure, publish images, or implement application
behavior. Package capability and Symfony wire-contract questions remain with their owning tickets.

## Confirmed resolution

The maintainer confirmed the following contract after the decision interview. This closes a planning decision,
not runtime implementation or verification. Two interview corrections are authoritative: the project owns one
stack, not a stack per worktree; and `bin/up` stays a thin Compose wrapper, not a readiness orchestrator.
The question and scope above have been reconciled to those corrections.

### Ownership and upstream contract

Yii owns its application stack, private network, configuration, data, and explicit lifecycle commands. Fight
Agent OS owns shared hostname ingress and the future provisioning, isolation, recovery, and retirement of
TASK execution environments. Do not implement worktree-derived stacks, execution sandboxes, or automatic
preview environments in this starter slice. Compose naming alone is not a security isolation claim.

Upstream planning inspected for this decision:

- `fight-agent-os/planning/wayfinder/tickets/WF-023-define-local-runtime-and-shared-ingress-topology.md`
  (Closed): shared `nginx-proxy` ingress, canonical HTTPS, independently operated project stacks, and only each
  project's ingress container on the external ingress network.
- `fight-agent-os/planning/tickets/00032-TICKET.md`: isolated TASK execution and recovery. Its TASK-00138 is still
  qualifying the sandbox boundary; TASK-00140 owns provisioning/recovery and is not complete.

These references identify upstream ownership and accepted planning, not installed capabilities. This decision
neither provisions upstream infrastructure nor requires Yii to implement Agent OS's execution system.

### Service, exposure, storage, and health matrix

The normal Yii stack comprises Nginx, FPM, MySQL, Redis, Mercure, a Messenger worker, Cron, and Mailpit, with
separate container lifecycles. FPM, Messenger workers, and Cron share the PHP application image and configuration.
CLI and Node operations run on demand, not as additional always-running services; shared ingress is external.

| Service or role | Exposure | Files and state | Health and verification boundary |
| --- | --- | --- | --- |
| Shared proxy (Agent OS-owned prerequisite) | Shared 80/443; HTTPS hostname is canonical | Upstream-owned network, routing, and certificates | Explicit browser verification; Yii never starts/stops or replaces it |
| Project Nginx | Only Yii service joining shared external ingress; no default published host port | Read-only public files; serves `public/` and built `public/dist/` | Compose service health; explicit application/HTTPS journey |
| PHP-FPM | Project-private FastCGI | Required source/dependencies; scoped writable application runtime paths | Compose service health; separate configuration/application/schema checks |
| MySQL | Project-private; no default host port | Project-owned durable named volume | Compose health/dependency conditions; explicit migrations and schema verification |
| Redis | Project-private; no default host port | Persistence follows roles selected by WF-007, not an assumption that all state is disposable | Compose health; role-specific checks owned by later application work |
| Mercure | Project-private hub, browser SSE through project ingress | Required local secret/configuration material; further state policy follows WF-007 | Compose health; authenticated publication/subscription verification follows WF-007 |
| Messenger worker | Project-private; one process initially | Required application files/configuration and post-commit collaborators | Service health plus explicit consumption/failure verification; WF-007 owns semantics |
| Cron scheduler | Project-private; one scheduler process initially | Required application files/configuration and job-lock capability | Service health plus explicit scheduling/overlap verification |
| Mailpit | Private SMTP; mailbox UI only through explicitly restricted development access | Disposable, explicitly clearable mailbox | Service health; explicit captured-mail journey, never real delivery by default |
| Node tooling | On-demand pinned containerized commands; no default persistent service | Checkout-visible frontend dependencies and generated assets | Explicit install/build/watch command results |

The shared ingress contract uses configurable `<name>.localhost` hostnames and canonical HTTPS rather than
per-project public ports. Hostname/certificate/trust enrollment belongs upstream and remains an explicit operator
operation. Only project Nginx joins that ingress network; databases, caches, PHP, workers, Cron, Mailpit, and
Mercure remain private. Browser application/API/SSE traffic goes through project ingress, avoiding an unnecessary
cross-origin development topology; detailed authentication/proxy trust and event behavior remain with WF-005/WF-007.

A missing external network is a Compose prerequisite failure. A missing proxy, route, or certificate is diagnosed
by explicit browser verification; neither running containers nor a successful `bin/up` proves browser readiness.
Do not create a replacement proxy/network, silently select a public port, stop another project, or mutate host
DNS/trust. CLI/test checks may run when their own dependencies are available even if browser ingress is not.

### Commands, readiness, and recovery

`./bin/up` remains a simple wrapper around `docker compose up`, preserving the current intent of
`docker compose up --build --detach "$@"`. It reports Compose's result. It does not install dependencies,
generate secrets, migrate schemas, create administrators, repair state, or coordinate application/browser
readiness. `./bin/down` delegates normal project teardown to Compose and preserves durable volumes by default.

Service health checks and dependency conditions live in Compose. Application/configuration, schema, worker,
scheduler, and browser acceptance checks run separately through explicit repository-owned commands and
verification. Do not create a custom startup/readiness controller. Diagnostic output must distinguish these
layers instead of equating container existence with successful application operation.

First-run setup is explicit and documented, using repository commands including `./bin/composer` and `./bin/exec`.
A documented bootstrap command may coordinate an approved setup sequence, but normal `bin/up` never acquires
those side effects. Migrations remain explicit, including when setup is resumed. Administrator bootstrap and
fixtures remain WF-004's decisions; startup never creates an administrator or silently seeds or resets data.

Pin runtime image and tool releases; do not use floating `latest` tags or automatic upgrades. Implementation
selects and records exact compatible versions and probe settings. Use bounded restart-on-failure policies;
persistent failures remain observable. Restarts never imply upgrades, migrations, dependency installation,
secret rotation, or data repair. Interrupted setup preserves completed work and reports how to resume safely;
there is no automatic destructive reset/rebuild loop or cleanup of ambiguous resources.

### Files, persistence, secrets, and teardown

Bind-mount editable source and keep installed dependencies and generated development files visible in their
checkout locations: `vendor/`, `client/node_modules/`, and frontend build output, ignored where appropriate.
Runtime files belong under `var/`, with tool caches under `var/cache/<tool>`. WF-009 decides whether built frontend
assets are committed. Use compatible host ownership for write-producing commands; do not leave root-owned
checkout files or apply blanket permission fixes. Each service receives only the mounts it requires; project
Nginx needs read-only public files, not the entire source tree.

MySQL and other service state whose loss is not permitted use project-owned durable named volumes. Application
uploads and local secrets also survive normal shutdown and rebuild. Redis durability must be decided from its
actual cache/queue/coordination roles in WF-007 before implementation of those roles. Normal teardown removes
this project's containers and private network but retains data. Destructive reset is a separate explicit action
that previews its exact targets and requires confirmation. It never removes shared ingress or another project's
resources; uncertain/stale resource ownership requires inspection rather than automatic deletion.

`.env.example` documents configuration names and safe non-secret defaults, not usable credentials. Explicit
setup generates missing project-specific development secrets into ignored, owner-readable files, preserving
existing values. Give each service only the secrets it needs. Missing/invalid required secrets prevent affected
application readiness and yield secret-safe diagnostics. Rotation is explicit. These credentials are local-only,
never production defaults, and do not appear in Git, images, or logs.

### Background work, frontend, and development operations

Run one Messenger worker and one Cron scheduler initially. Cron invokes due work once per minute; job-level
locking prevents overlap of the same job without serializing unrelated jobs. Scaling is explicit and bounded,
not automatic. WF-007 owns transport, retry policy, lock implementation, scheduled-job semantics, and realtime
behavior. Authoritative domain commands remain synchronous; workers handle the map's post-commit effects.

Use pinned containerized Node tooling for explicit install/build/watch commands and serve built assets through
project Nginx. Normal startup neither installs dependencies nor builds frontend assets. A future opt-in service
for automatic frontend reloads is allowed as a later decision, but is not part of this initial stack.

Mailpit captures invitation, activation, and reset messages without external delivery credentials or accidental
real mail. Its credential-bearing mailbox is disposable and explicitly clearable; UI access must be restricted
and development-only, never automatically exposed through shared ingress. Swagger UI and any operational UI
also fail closed outside development; detailed access controls follow their owning HTTP/security decisions.

Use structured, secret-safe stdout/stderr logs, Compose health, and explicit application checks. Do not add an
initial Grafana, Prometheus, Loki, or other monitoring/logging platform. This is a local-development contract,
not a production topology or readiness claim.

### Acceptance journeys for implementation handoff

These are required future verification sequences, not tests executed while closing this decision.

| Journey | Explicit sequence and expected evidence |
| --- | --- |
| First setup | Install locked dependencies, generate only missing local secrets, run approved frontend setup/build and explicit migrations/bootstrap commands as applicable; each command reports its own result without exposing secrets |
| Steady state | Run thin `bin/up`; inspect Compose health; separately verify configuration/schema, HTTP/API/assets through shared HTTPS, worker consumption, scheduler activity, captured mail, and authorized SSE once owning workflows exist |
| Missing prerequisite/failure | Exercise missing ingress network/proxy, hostname/certificate conflicts, missing secrets, stale schema, and unhealthy dependencies; report the failing layer without automatic host mutation, public-port fallback, migration, reset, or unrelated teardown |
| Interrupted setup/restart | Interrupt an explicit setup step and resume deliberately; preserve prior data/secrets and report remaining work; restart/rebuild does not reinstall, migrate, rotate, seed, or silently repair data |
| Normal teardown | Run `bin/down`; verify project containers/private network stop or are removed while durable data, uploads, secrets, shared ingress, and unrelated resources remain; explicit restart can reuse retained state |
| Destructive reset | Preview and confirm exact project-owned disposable targets; refuse ambiguous ownership and preserve shared resources; mailbox clearing is also explicit |

### Follow-up ownership and completion limits

- WF-004 owns persistence adapters, schema workflows, fixtures, and administrator bootstrap.
- WF-005 owns authentication and browser/proxy security controls.
- WF-007 owns messaging, Redis role/durability, retry, scheduling/locking, and Mercure semantics.
- WF-009 owns copied-client tooling details and generated-asset tracking.
- Exact image pins, service identifiers, environment keys, probe settings, and restricted development-UI routing
  are implementation details within this contract and later accepted requirements, not permission to begin work.
- Shared-ingress enrollment and sandbox provisioning remain upstream dependencies, not Yii implementation scope.

The service matrix and journeys satisfy this decision's planning evidence. No containers were started, no host
or runtime configuration was changed, and no application acceptance journey was claimed as verified. This
resolution creates no EPIC, requirement TICKET, implementation TASK, release, or distribution authorization.
