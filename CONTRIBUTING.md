# Contributing

Read `AGENTS.md`, `ARCHITECTURE.md`, `planning/README.md`, and the focused local planning rules before proposing a
change. Create or update one repository-local ticket, keep work to a vertical slice, update behavior documentation,
and run `./bin/build` before requesting review.

For a fresh checkout, use `./bin/up` followed by `./bin/composer install`; all development commands run in the
repository Docker services, so host PHP and Composer versions are not prerequisites. Use `./bin/down` to stop the
complete Compose runtime. Do not publish tags, packages, templates, or distributions as part of a code change.
