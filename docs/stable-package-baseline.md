# Stable Fight package baseline

TASK-00008 implementation snapshot, verified locally on 2026-10-24; independent acceptance is separate.
Yii consumes published Composer archives, not neighboring checkouts or development aliases.

## Installed artifacts

[Packagist Common metadata](https://repo.packagist.org/p2/johnnickell/fight-common.json) and
[AccessControl metadata](https://repo.packagist.org/p2/johnnickell/fight-access-control.json) still list these as
the newest stable releases at execution. No newer out-of-envelope release was observed.

| Package | Root constraint | Installed version | Source and dist reference |
| --- | --- | --- | --- |
| `johnnickell/fight-common` | `^1.4` | `v1.4.0` | `87b9580c072f3149d574e09865daa79620c92118` |
| `johnnickell/fight-access-control` | `^0.8` | `v0.8.0` | `319738255e682d587642b67856039f882b9588d6` |

Both releases require PHP >=8.5; AccessControl requires Common `^1.3`, permitting 1.4.0. The consumer platform
remains PHP 8.5.4; the actual wrapper runtime is PHP 8.5.12. Root VCS repositories and Fight development
pins/aliases are removed. Resolution uses Packagist and ordinary platform requirements.

The bounded update changes only those two production packages and `symfony/http-foundation` from 8.1.6 to 8.1.9
(reference `7850897fb8659f98491b03be79ac48600bdaf9ab`). The latter clears
[CVE-2026-108082](https://symfony.com/cve-2026-108082), reported during resolution; final Composer audit is clear.
Consumer development additions are Slevomat 8.31.1 (`0a40807a48873948bfa7ffce2a4e69ba40cf5e76`) and its
transitive installer 1.2.1 (`963f0c67bffde0eac41b56be71ac0e8ba132f0bd`). The installer plugin is explicitly
disabled: the consumer ruleset loads the installed standard directly, without plugin discovery.
PHPCS remains 4.0.4. No upstream require-dev graph is imported.

The Yii graph remains DB 1.3.0, DB SQLite 1.2.0, DI 1.4.1, View 11.0.1 and View Twig 3.0.0. Common's own
require-dev uses newer DB/View majors; those are not transitive consumer constraints. Actual selected signatures,
static analysis and local behavior pass against this locked graph; this is not a framework-support certification.

## Released contracts and migration findings

Sources are the exact installed `vendor/johnnickell/fight-common` and `vendor/johnnickell/fight-access-control`
archives. Tagged source links below identify the same immutable release subjects.

- [Common changelog](https://github.com/johnnickell/fight-common/blob/v1.4.0/CHANGELOG.md): 1.2 adds canonical
  messaging/transaction paths and immutable envelope metadata; 1.3 adds opt-in MCP; 1.4 adds opt-in value/error
  contracts and fixes validation cleanup. StreamId equality/JSON is an explicitly breaking minor exception.
  Yii has no StreamId, persisted hash collection, MCP or new safe-error-handler consumer in this slice.
  Existing selected middleware is retained rather than silently reselecting error policy.
- [AccessControl changelog](https://github.com/johnnickell/fight-access-control/blob/v0.8.0/CHANGELOG.md),
  [0.8 integration overview](https://github.com/johnnickell/fight-access-control/blob/v0.8.0/docs/release-0.8.0.md)
  and retired `docs/credential-delivery-v0.3-migration.md`: pre-v1 consumers must use current contracts, not
  historical readers or inferred defaults. Role revisions, recovery-authority persistence, shared-connection
  fences and changed recovery constructors matter to future workflow adoption. Yii currently has no
  AccessControl repositories, persisted Users/Roles, handlers or workflow routes to migrate.
- The AccessControl archive's README/0.8 overview still say “prepared, not yet published” and refer to 0.7 as
  latest published. That is stale publication prose, not the installed version: registry metadata, the versioned
  archive, lock and installed Composer metadata agree on 0.8.0 and the reference above. This documentation
  limitation remains visible for WF-001; Yii does not rewrite package documentation or infer support guarantees.
- Old development references were Common `fad24ae9fdcf4ac00fa55c59ef7d35f7c7531911` and AccessControl
  `4259ea0b77bc2e1d87e62ab70b64af63bae99477`. GitHub comparisons establish both are ancestors of the selected
  releases. Their large file lists are truncated by the API; they are orientation, not a complete public API audit.

### Transaction compatibility

Common's `Application\Repository\TransactionalUnitOfWork` exposes
`commitTransactional(callable $operation): mixed` and `isClosed(): bool`. Deprecated `UnitOfWork` extends it
and additionally requires `commit(): void`; the interfaces are not interchangeable in that direction.
`Adapter\Persistence\Yii\YiiTransactionalUnitOfWork(ConnectionInterface $connection)` implements only the
narrow transactional interface. The released `PersistenceServiceProvider` binds that exact interface to the
adapter using the consumer's shared connection.

AccessControl 0.8 has 45 imports of `TransactionalUnitOfWork` and zero imports of legacy `UnitOfWork` in its
production source. For example, `CreateCustomRoleHandler(RoleRepository, Clock, TransactionalUnitOfWork,
EventDispatcher)` and `AuthenticationService` require the narrow contract. The development-era constructor
mismatch is therefore absent in these releases. No legacy alias, extra transaction adapter or direct handler
invocation is introduced. Constructor inspection is not proof of an installed AccessControl workflow.

The Yii adapter returns the callback result, delegates commit/rollback to the connection, rejects nested
transactions and rejects a previously established connection after closure. Integration journeys prove commit,
exception rollback, nested rejection rolling back the outer write, transaction cleanup and successful reuse on
the actual configured SQLite connection. They do not prove MySQL isolation, concurrent writers, outbox delivery,
uncertain-commit recovery or the full AccessControl audit/fencing obligations. Success publication after commit
and failure after an external effect remain package/use-case obligations, not a guarantee from UnitOfWork alone.

### Yii, messaging and security signatures

Common no longer ships the development-only `YiiCapabilityConfiguration` helper. Yii's five affected providers
now supply the collaborators expected by the released bounded providers, which remain registered directly:

| Selected boundary | Exact released contract / consumer composition |
| --- | --- |
| Routing | `YiiUrlGenerator(UrlGeneratorInterface)`; `generate(string $name, array $parameters = [], array $query = [], bool $absolute = false): string`. Yii supplies native routes and generator. |
| View | `YiiTemplateEngine(ViewInterface, string $templatesPath)`; `render(string $template, array $data = []): string`. Released View provider references `fight.templates_path`; Yii supplies a scalar factory and the native View, retaining Twig WebView rendering. |
| HTTP client | Released provider exposes PSR-18 `ClientInterface` through `Psr18Client(HttpClient)`; Yii retains its configured Fight transport/Guzzle fallback. |
| Mail/files | Released Mail provider consumes Symfony `MailerInterface`; Filesystem provider selects Symfony Filesystem. Yii retains the null-DSN mail policy and filesystem fallback. Yii Mail/Session remain unselected. |
| Synchronous messaging | `RoutingCommandBus(CommandRouter)` implements `execute(Command): void` / `dispatch(CommandMessage): void`; the shared dispatcher implements `trigger(Event): void` / `dispatch(EventMessage): void`. Yii retains one router and one dispatcher per container. |
| Async fallback | `MessengerCommandBus(SenderInterface)` / `MessengerEventDispatcher(SenderInterface)` retain complete envelopes. `CommandMessageHandler(SynchronousCommandBus)` and `EventMessageHandler(SynchronousEventDispatcher)` dispatch through configured buses, not directly to handlers. No stable Yii Queue claim. |
| HMAC | `HmacRequestService(string $public, string $private, ?Closure $nonceGenerator = null)`; `signRequest(RequestInterface): RequestInterface`. `HmacAuthenticator(string $public, string $private, int $timeTolerance, ?NonceRepository = null)` declares `validate(ServerRequestInterface): bool`; rejection throws. The installed signature, not an older changelog's narrowed `true` description, is authoritative. |
| JWT/password | `JwtEncoder(string $hexSecret, string $algorithm = 'HS256')`, `encode(array $claims, DateTimeImmutable $expiration): string`; matching decoder `decode(string $token): array`. Password hasher/validator require algorithm and optional options. Yii retains HS256, Argon2id and fail-closed deployment credentials. |
| Scheduling | Released `Scheduler::withProcessRunner(Timezone, string $tempDirectory, ProcessRunner, ?LoggerInterface = null, ?MailService = null, string $fromEmail = ''): self` remains available. Yii retains UTC and its configured runtime root. |

## Coding standard and verification boundary

The following records TASK-00008's historical gate and scan scope. TASK-00009 replaces that lifecycle and removes
`scripts/verify-coverage.php`; current setup, scan/coverage ownership and intentionally retired production-install
proof are documented in [Contributing](../CONTRIBUTING.md#verification-contract).

`phpcs.xml.dist` imports Common's `src/Standards/Phpcs/ruleset.xml` exactly as documented in the installed
`docs/coding-standard.md`. Yii retains its existing scan scope: `src`, `tests`, and `scripts/verify-coverage.php`;
no exclusions, baselines, severity reductions or suppressions are added. Source/test formatting, docblocks,
camelCase test names, imports and line wrapping align with the shipped standard. Changes to the existing coverage
verifier are formatting only; replacement of that tooling and the gate lifecycle remains TASK-00009.

New behavioral evidence is limited to configured native-template rendering with caller data, scheduled-job
execution at the configured root, and nested transaction rollback/reuse. Existing tests retain HTTP/console,
credential rejection, messaging envelopes, cache, logging, storage and fallback journeys. Tool/configuration
checks run their owning commands; no certification tests or mechanisms are added.

At the TASK-00008 checkpoint, the complete `./bin/build` included disposable-image and no-dev production boot.
TASK-00008 does not implement TASK-00009's running-container gate or rewrite inherited suite attribution.
The complete local gate passes: Unit 28 tests / 85 assertions, Integration 58 / 230, Functional 2 / 5;
Unit-only owned-production statement coverage is exactly 237/237 with PCOV 1.0.12 on PHP 8.5.12. Planning,
PHPCS, PHPStan, Deptrac, Rector, FPM configuration and disposable no-dev HTTP/console boot pass. The inherited
PHPUnit bootstrap filters E_DEPRECATED/E_USER_DEPRECATED; no new suppression is introduced. Git ownership and
unwritable Composer-cache diagnostics remain visible nonfatal environment limitations, not skipped gate phases.
Implementation results and independent acceptance status live in [TASK-00008](../planning/tasks/00008-TASK.md).

## WF-001 continuity

These historical bounded compatibility findings were input to
[WF-001](../planning/wayfinder/tickets/WF-001-released-package-contract-audit.md), not its complete inventory.
TASK-00008 subsequently completed independent acceptance and QA and integrated through PR #15, as recorded in its
[current handoff](../planning/tasks/00008-TASK.md#current-integration--2026-10-25).
On 2026-10-25 WF-001 closed with its separate [released-contract audit and complete surface inventory](../planning/wayfinder/research/wf-001-released-package-contract-audit.md),
including User/Agent workflows, unsupported/internal boundaries, documentary gaps and unverified consumer obligations.
The map stays active; its next decision WF-003 needs the accepted immutable Symfony wire artifact. No login,
persistence workflow, endpoint, worker, client, native-adapter reselection or Common 2.0 adoption follows.
