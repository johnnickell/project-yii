---
id: T-00002
prd: PRD-00002
title: Adopt Fight Common 1.2
status: done
blocked_by: T-00004
---

# Adopt Fight Common 1.2

## Outcome

Resolve the completed Yii platform profile through Yii's Composer installation, run lowest/latest booted journeys,
and commit the canonical support receipt.

## Acceptance Criteria

- [x] The exact Fight Common candidate and Composer-resolved version are recorded with its reference.
- [x] Lowest/latest journeys boot every selected service from T-00004 through Yii configuration and providers.
- [x] The receipt records the working Symfony Messenger fallback separately from stable Yii Queue, which is unavailable.
- [x] `evidence/framework-support/receipt-v1.json`, `./bin/planning-check`, and `./bin/build` are canonical gates before receipt commit.

## Verification

Run documented lowest/latest Composer and booted journeys, receipt canonicalization, `./bin/planning-check`, and `./bin/build`.

Verified 2026-09-05: focused and full booted journeys, exact latest/lowest package graphs with unchanged lock digests,
production `--no-dev` boot, independent receipt digests, exact-candidate `StarterSupportReceiptAuthority`, planning
validation, and the repository-owned detached canonical repair build.

Repair verification on 2026-09-07 additionally covers granular native Yii routing/View, shared PSR-15/17 HTTP,
HMAC/JWT/password/validation, Yii PSR-16 plus Fight PSR-6 cache, Yii PSR-3 plus shared observability, and persistence
composition. PR #4 carries this receipt with T-00004 under the approved exception recorded in PRD-00002.

Final correction verification on 2026-09-08 removes deployable HMAC/JWT defaults, proves lazy fail-closed credential
resolution, keeps deterministic credentials inside tests and verification only, and restores exclusive security-test
ownership without changing the receipt, exact dependency candidates, or previously accepted capability journeys.

Certified dependency evidence on 2026-09-08 regenerates the lowest lock digest and support receipt after fixing
JWT encoding and decoding to HS256. Both Composer locks contain no installed `yiisoft/mailer` or
`yiisoft/session` package; they are forbidden unselected Yii dependencies, and Symfony Mailer remains the selected
fallback. Focused receipt-authority and framework-profile tests passed against the regenerated evidence.
## Supersession

Fight Common's 2026-09-09 authorship-only history rewrite supersedes the commit identity recorded above without
changing its source tree: `4a798b1db8fdb5e4af7d0ba8c98a88ac53c50c16 -> fad24ae9fdcf4ac00fa55c59ef7d35f7c7531911`. T-00006 owns the fresh consumer locks,
receipt digests, and verification for the rewritten identity; the original certification remains historical fact.
