# Local Development Runtime Contract

**Labels:** `wayfinder:grilling`
**Mode:** HITL
**Status:** Open
**Gate:** —
**Map:** [Yii AccessControl Starter Application](../yii-access-control-application-map.md)
**Depends on:** —

## Question

What complete, reproducible, worktree-safe local runtime contract should the Yii starter promise to contributors?

## Must decide

- Compose topology for MySQL, Redis, Nginx, PHP-FPM, PHP-CLI Messenger workers, Cron scheduling, and Mercure SSE,
  including which processes share an image and which require distinct service lifecycles.
- Checked-in `.env.example` names and safe local defaults, required secrets, project/host port assignments, and
  collision behavior when multiple worktrees run concurrently.
- Named versus bind-mounted volumes, ownership and permission behavior, cache/vendor/client dependency treatment,
  database and Redis durability, and task-owned teardown semantics.
- Service health checks and dependency readiness, distinguishing container health, application readiness,
  migration readiness, worker readiness, and Mercure reachability.
- One-command bootstrap and teardown through repository-owned `./bin/up`, `./bin/down`, `./bin/composer`, and
  `./bin/exec`, including first-run dependency installation, migrations, fixtures/bootstrap, and useful failure output.
- Nginx-to-FPM routing, public document root, development asset serving, CLI/worker configuration parity, Cron
  invocation cadence, and local TLS/CORS expectations if any.
- The exact local-only readiness journey and cleanup guarantees; no production topology or readiness claim.
- Swagger UI and operational dashboards remain local-only by default and fail closed outside development.

## Acceptance evidence

- A service/port/volume/health matrix with explicit ownership and worktree isolation rules.
- Bootstrap, steady-state, failure, restart, and teardown sequences that can become executable acceptance journeys.
- Decisions for stale containers, conflicting ports, missing secrets, unhealthy dependencies, and interrupted setup.

## Resolution boundary

This ticket settles the local-development runtime contract and the seams later tickets may implement. It does not
create Compose files, start containers, choose production infrastructure, publish images, or implement application
behavior. Package capability and Symfony wire-contract questions remain with their owning tickets.
