# Architecture

`johnnickell/project-yii` is a Yii 3 application composition. `App\\` owns the framework-facing composition:
configuration providers, dependency injection, web and console entry points, presentation, and future adapters.

`johnnickell/fight-common` and `johnnickell/fight-access-control` are consumed only through their published
Composer contracts. Never copy their Domain or Application source, depend on unpublished internals, or introduce a
Fight bundle. HTTP, security, Active Record, and operations are declared starter seams. Symfony Messenger is the
selected local asynchronous fallback; stable Yii Queue integration is explicitly unavailable rather than inferred.
No login, persistence workflow, client, or realtime behavior is established by this bootstrap.

The web entrypoint and behavioral tests share `ConfiguredApplicationFactory`. Project-owned bounded providers supply
policy and collaborators; Fight Common's Yii providers remain directly registered from the Composer dependency.
Yii's native router and View adapter ship. The shared PSR-15 JSON/JSend middleware and PSR-17 response factory run
inside Yii's real middleware dispatcher, while HMAC, JWT, password, validation, cache, logging, health, audit, and
metrics resolve through explicit public contracts. Persistence consumes the selected PSR-16 cache and PSR-3 logger;
it does not install competing cache or logging policy.

Fight Common T-00072 is authoritative for the native prototype outcomes. Yii Mail failed independent-part charset
and exact valid-CID behavior, so Symfony Mailer remains selected. Yii Files passed only recursive directory creation
in the 22-case suite, so Symfony Filesystem remains selected. Yii View passed the complete shared suite and ships.
Storage and scheduler state default to `var/storage/flysystem` and `var/runtime/scheduler` and can be overridden per boot.

Generated caches and runtime artifacts belong under `var/`, with tool caches under `var/cache/<tool>`. `.runs/`
contains local coordination scratch and is never committed.
