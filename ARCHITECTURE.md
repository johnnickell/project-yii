# Architecture

`johnnickell/project-yii` is a Yii 3 application composition. `App\\` owns the framework-facing composition:
configuration providers, dependency injection, web and console entry points, presentation, and future adapters.

`johnnickell/fight-common` and `johnnickell/fight-access-control` are consumed only through their published
Composer contracts. Never copy their Domain or Application source, depend on unpublished internals, or introduce a
Fight bundle. HTTP, security, Active Record, queue transport, and operations are declared starter seams; no login,
persistence workflow, client, or realtime behavior is established by this bootstrap.

Generated caches and runtime artifacts belong under `var/`, with tool caches under `var/cache/<tool>`. `.runs/`
contains local coordination scratch and is never committed.
