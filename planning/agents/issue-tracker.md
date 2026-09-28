# Local issue tracking

EPICs own destinations, TICKETs own requirements, and TASKs own execution and verification. Individual records are
the status/dependency authority; refresh generated views with `./bin/planning-check --write` and validate with
`./bin/planning-check`. Do not mark done without required evidence or erase completed dependency edges.
Upstream planning IDs are provenance, not local status authority. See [conventions](../CONVENTIONS.md).
