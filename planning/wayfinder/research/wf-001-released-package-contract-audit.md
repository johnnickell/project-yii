# WF-001 — Released package contract audit

## Question

What public consumer surface is actually shipped by Common 1.4.0 and AccessControl 0.8.0, and which boundaries
belong in the Yii starter's subsequent persistence, security, async and HTTP decisions?

## Short answer

The approved installed releases supply reusable contracts, not an application. AccessControl owns User and Agent
lifecycle/authority and synchronous use cases; Yii must supply persistence, current authentication context,
entry-point authorization, cryptography/provider infrastructure, scheduling and transport presentation. Common
supplies primitives, ports and optional adapters. Neither package turns its classes, messages or schemas into routes.

The complete static declaration census is [public surface](wf-001-public-surface.md); the documentary search is
[consumer documents](wf-001-consumer-documents.md). Together with the capability tables below, they give every
inventoried declaration and documented capability a disposition. This resolves WF-001's research question, **not**
consumer integration or downstream decisions. No login, database adapter, worker, API, MCP endpoint or UI was built.

## Subject and method

Audit date: 2026-10-25. Target: `johnnickell/project-yii`, local HEAD
`f8d41eefa2eb3b4cb550ab7bb940248be30b2d38`; the audit concerns these installed released artifacts only:

| Package | Root constraint / installed version | Exact source and dist reference |
| --- | --- | --- |
| `johnnickell/fight-common` | `^1.4` / `v1.4.0` | `87b9580c072f3149d574e09865daa79620c92118` |
| `johnnickell/fight-access-control` | `^0.8` / `v0.8.0` | `319738255e682d587642b67856039f882b9588d6` |

1. Cross-checked root `composer.json`, `composer.lock` and `vendor/composer/installed.json`: exact versions and
   references agree, and installation source is `dist`. No package update or installation was performed.
2. Downloaded the two public GitHub archives at those exact references into ignored research scratch. Compared all
   installed `src/`, `docs/`, opt-in `openapi/`, root README/CHANGELOG/LICENSE/Composer files, Common's exported
   `tests/TestCase/`, and AccessControl's behavioral requirement against archive bytes: **807 Common and 498
   AccessControl files matched, zero mismatches or extra local files in the enumerated trees**. This checks the
   cited research subject, not a new release certification, signature attestation or registry-latest claim.
3. Parsed every PHP file in production autoload roots, Common's Composer-exported TestCase root, and AccessControl's
   opt-in schema directory using the already installed PHP AST parser. Enumerated namespaces, named
   classes/interfaces/traits/enums/functions and directly declared public methods/properties/constants/enum cases.
   Public promoted properties are included. Anonymous implementations and inherited-member repeats are not separate
   public declarations; signatures and inheritance remain traceable to their owning immutable source.
4. Reconciled **1,106 files, 1,124 unique named declarations, 4,817 direct public members**, with no unclassified
   declaration. Production source alone is Common 656 files / 668 declarations and AccessControl 428 / 428.
   Common's additional 14 exported TestCase files contain 21 declarations; AccessControl's seven schema anchors
   contain seven declarations and its eighth file is the bootstrap. All **125 unique named value schemas** are indexed.
5. Inventoried all **135 shipped `docs/**/*.md` files**, plus consumer root entrypoints. Read the relevant composition,
   release-delta and authority guides and traced the claims below to released source. Guide indexing/heading inspection
   is not line-by-line execution of every example. The census is exhaustive declaration accounting, not a security
   assessment of every method body or verification of every optional adapter's behavior.

The static appendices are dated research evidence, not a generated planning view, product test, certification file,
support matrix, receipt reader or new gate. Extraction scripts/downloads stay in ignored `.runs/notes/WF-001/`.
The committed source links and method/counts above make the findings independent of that scratch surviving.

## Compatibility and ownership envelope

Both libraries require PHP **>=8.5** and are MIT-licensed. AccessControl declares Common **^1.3**, which admits
installed 1.4.0. Common's mandatory dependencies are PSR contracts; most framework/provider packages are optional.
Its require-dev framework versions are not consumer requirements. In particular, the locked Yii DB/View versions
and the actual prior compatibility observations remain in the [stable baseline](../../../docs/stable-package-baseline.md).
This audit neither broadens that evidence to MySQL nor adopts Common's own development dependency graph.

AccessControl pre-v1 minors can break API and persisted state. Only the current contract is supported: no inferred
hydration defaults, historical readers, migration/backfill service, data reset or mixed-version deployment follows.
New releases require their own adoption decision; this audit does not assert these remain registry-latest.

Common's 1.4 additions are opt-in (strict pagination/JSON snapshots, value types, application-failure categories and
safe errors). Its documented StreamId equality/hash/JSON change is a specific minor compatibility exception;
serialized hash collections need explicit migration when used. Legacy 1.x APIs remain callable but deprecated,
including old serialization/adapter names and standalone UnitOfWork commit; they are not the new integration path.
Sources: [Common changelog](https://github.com/johnnickell/fight-common/blob/87b9580c072f3149d574e09865daa79620c92118/CHANGELOG.md),
[Common Composer contract](https://github.com/johnnickell/fight-common/blob/87b9580c072f3149d574e09865daa79620c92118/composer.json),
[AccessControl changelog](https://github.com/johnnickell/fight-access-control/blob/319738255e682d587642b67856039f882b9588d6/CHANGELOG.md),
[AccessControl Composer contract](https://github.com/johnnickell/fight-access-control/blob/319738255e682d587642b67856039f882b9588d6/composer.json).

## Classification rules

- **HTTP boundary candidate:** a real consumer workflow to account for in WF-008. This is not a choice of URL,
  HTTP method, permission name, response or enablement; Symfony-led wire decisions remain authoritative.
- **CLI:** operator/code-configuration workflow, not a decorative administration endpoint. CLI still requires
  trusted authority, validated input and the same atomic fences as every other writer.
- **Worker:** protected delivery, recovery, expiry or maintenance execution. A public message/schema does not make
  worker admission a public API. Authoritative domain commands remain synchronous under the accepted map.
- **Composition-only:** values, ports, configured handlers, middleware, factories, facts, safe projections and
  reusable infrastructure. No independent endpoint, policy grant or requirement to enable every optional component.
- **Unsupported:** either unavailable in the package, explicitly excluded by this map, internal, test-only, or a
  different framework's integration. The reason matters; target exclusion is not a claim of package absence.

Every declaration has one primary disposition in the census. Reused workflows (for example initial-admin invitation
from a CLI) retain their own scenario boundary rather than inventing a second package use case. The tables below
summarize capability families; the census enumerates every concrete message/type instead of hiding them in counts.

## AccessControl capabilities

Prefixes below are `Fight\AccessControl\Domain\AccessControl` and
`Fight\AccessControl\Application\AccessControl`. Canonical constructor/payload detail is in the linked source census.

| ID | Released workflow / owning public entries | Disposition and obligation |
| --- | --- | --- |
| AC01 | `InvitePendingUser`, `CorrectPendingInvitation`, `RetryInvitationDelivery`, `ResendInvitationDelivery`, `FindInvitationDeliveryStatus`; their registered handlers and grant Views | HTTP candidates for protected invitation administration. Initial administrator invitation belongs to WF-004's separate CLI bootstrap, never a public bootstrap route. Correction is private-invitation-only; retry preserves generation, resend revokes/reissues. |
| AC02 | `User\Security\AuthenticationService::{activate,login,refresh,logout,changePassword,resetPassword,confirmEmail}`; `TokenSet`, `RefreshResult`, `AuthenticationTokenPolicy` | HTTP candidates, **synchronous methods**, not serializable secret-bearing Commands or queued work. Results require an explicit safe transport projection; `TokenSet` is not a browser response DTO. |
| AC03 | `ListActiveSessions`, `RevokeSession`, `SessionView`, `SessionAdministrationAuthorization`, session repository/revocation service | Protected HTTP candidates; distinguish own/current/other-user sessions and required cross-user reason. Logout is AC02, not an administrative revoke of the current session. |
| AC04 | `AuthenticationContextProvider`, `CurrentPrincipalProvider`, `SecurityContext`, `AuthenticatedAuthority`, User/Agent principal values | Composition-only current authority, not caller-supplied Roles/Permissions and not an alternate login endpoint. User provider and Agent provider are request-local. See internal-resolver documentation gap below. |
| AC05 | `DeliverUserInvitation`, `DeliverPasswordReset`, `DeliverEmailChange`; due/expired discovery and exact-generation expiry Commands; delivery status Views and provider/cipher ports | Worker for invocation/discovery/expiry; safe status reads are protected HTTP candidates. Optional post-commit subscribers accelerate work, not guarantee it. Aggregate state, claim tokens, material and provider invocation stay composition-only. |
| AC06 | `RequestPasswordReset`, `RequestEmailChange`, `CancelEmailChange`; their grants, credentials, repositories and typed failures | HTTP candidates for request/cancellation; reset redemption/email confirmation use AC02. Reset requests retain generic no-work behavior. Email reservation does not replace the current identity before confirmation. |
| AC07 | `GetUserById`, `ListUsers`, `DisableUser`, `EnableUser`, `DeleteUser`, `RestoreUser`, `AssignRoleToUser`, `RemoveRoleFromUser`, `UserView` | Protected HTTP candidates. Disable/delete cancel authority; enable/restore do not restore sessions or reset authority. Soft deletion retains identity and canonical-email ownership. Pending deletion is not a supported shortcut. |
| AC08 | `CreateCustomRole`, `RenameCustomRole`, `RemoveCustomRole`, `GrantPermissionToCustomRole`, `RevokePermissionFromCustomRole`; Role/Permission detail/list Queries | Protected HTTP candidates. Managed policy is separate; no generic Permission CRUD or managed-Role editor is supplied. Rename/remove bind displayed `expected_revision`; conflicts need refresh and reconfirmation, never silently substituted tokens. |
| AC09 | `PreviewManagedPolicy`, `ReconcileManagedPolicy`, definitions/plans and `ManagedPolicyPlanner` | CLI for version-controlled complete policy preview/apply; planner/definitions are composition-only. Preview reserves nothing; apply replans and fences current references. Stable IDs must not regenerate on startup. |
| AC10 | `AgentProvisioningService::provision`, `AgentCredentialRotationService::rotate`, `AgentCredentialLifecycleService::revoke`; retained keys/requests/issuance/result values | Protected synchronous HTTP candidates, not ordinary serializable create/rotate Commands. Fresh current scope, exact original request/destination and durable operation correlation are mandatory. Safe confirmed/indeterminate results never contain a reusable raw secret. |
| AC11 | `UpdateAgent`, `GrantPermissionToAgent`, `RevokePermissionFromAgent`, `ReplaceAgentPermissions`; `GetAgentById`, `ListAgents`, `GetAgentOperation`, `GetAgentProfile` | Protected HTTP candidates. Name update is void with an input-derived acknowledgement, not latest-state proof. Full replacement binds Permission-assignment revision. Operation status absence is indeterminate. Self-profile targets derive from the authenticated principal, never arbitrary caller authority. |
| AC12 | `CurrentAgentPrincipalProvider::resolve`, `SignedAgentRequest`, `RequiresAgentPermission`, `AgentToolPermissionCatalog`, `AgentToolAvailability`, `GetAgentProfileTool`, `UpdateAgentProfileTool`, profile Permission definitions | Composition-only signed Agent authentication and optional protected MCP Tools. Tools are `agent.profile.read` / `agent.profile.update`; no implicit REST/MCP endpoint or all-API Agent permission. Reuse the same registry/provider/request inside one request, never principals across requests. |
| AC13 | `AgentCredentialDeliveryService::deliver`, `AgentDeliveryRecoveryService::{recover,nextRunAt}`, `AgentDeliveryMaintenanceService::{rewrap,expire,cleanup}`; due/maintenance/key-reference Queries | Worker. Separate claim/admission/acknowledgement, fixed destination/sink, current authority, bounded passes and independent scheduled recovery. Diagnostic zero key references is not permission to destroy a key. |
| AC14 | `RegisterUser`, `RecoverPendingRegistration` and their handlers/facts | **Unsupported in this map:** package capabilities exist, but self-registration is explicitly excluded. Keep inventory visible, do not wire anonymous registration or expand scope through package availability. Registration-origin persistence/provider contracts still must not be misread as optional defaults. |
| AC15 | `ProvisionFeatures`, `ValidateFeaturePreparation`, candidate reference discovery and safe preparation results | CLI for candidate setup/preparation; references and scanner ports are composition-only. One atomic provisioning pass; failed/incomplete discovery is not an empty complete inventory. Preparation is not availability, authorization or deployment permission. |
| AC16 | `CreateFeature`, `SetFeatureStatus`, `SetFeaturePermission`, `RemoveFeature`, `GetFeatureById`, `ListFeatures`; `FeatureFlag`, `FeatureAvailability`, reference/name/status/revision values | Protected HTTP management candidates; runtime evaluation/metadata are composition-only. No automatic method interception. Candidate/current discovery scopes differ; deletion checks actual current references. Feature availability never authorizes the underlying action. |
| AC17 | All Domain repositories, audit evidence, consumer authorization/cipher/generator/clock ports, aggregates, value types, exceptions, Commands' configured handlers and post-commit facts | Composition-only unless a workflow above explicitly identifies a boundary. Consumer infrastructure implements the ports; no production Adapter layer ships. Audit storage is required atomic evidence, not merely post-commit logging. Events are not automatically SSE payloads. |
| AC18 | `openapi/bootstrap.php` and seven opt-in anchors / 125 value schemas | Composition-only scan resources; **actual release path is `openapi/`, not `resources/openapi/`**. Catalog includes worker/sensitive-input-related schemas and optional response envelopes, not routes, security policy or permission to expose them. |

Principal sources for these dispositions:
[behavioral requirements](https://github.com/johnnickell/fight-access-control/blob/319738255e682d587642b67856039f882b9588d6/planning/tickets/00001-TICKET.md),
[current User delivery](https://github.com/johnnickell/fight-access-control/blob/319738255e682d587642b67856039f882b9588d6/docs/credential-delivery.md),
[Permission tiers](https://github.com/johnnickell/fight-access-control/blob/319738255e682d587642b67856039f882b9588d6/docs/permission-tiers.md),
[Role revisions](https://github.com/johnnickell/fight-access-control/blob/319738255e682d587642b67856039f882b9588d6/docs/role-revisions.md),
[Agent integration](https://github.com/johnnickell/fight-access-control/blob/319738255e682d587642b67856039f882b9588d6/docs/agent-integration.md),
[Agent current contract](https://github.com/johnnickell/fight-access-control/blob/319738255e682d587642b67856039f882b9588d6/docs/agent-current-contract.md),
[Agent MCP composition](https://github.com/johnnickell/fight-access-control/blob/319738255e682d587642b67856039f882b9588d6/docs/agent-mcp-authorization.md),
[Feature declarations](https://github.com/johnnickell/fight-access-control/blob/319738255e682d587642b67856039f882b9588d6/docs/feature-references.md),
[Feature preparation](https://github.com/johnnickell/fight-access-control/blob/319738255e682d587642b67856039f882b9588d6/docs/feature-preparation.md),
[OpenAPI composition](https://github.com/johnnickell/fight-access-control/blob/319738255e682d587642b67856039f882b9588d6/docs/openapi-composition.md).
The [document inventory](wf-001-consumer-documents.md) links the additional owning guides for each family.

## Security and operational obligations carried forward

### User authentication and recovery

- `AuthenticationService` directly receives passwords and grants with sensitive-parameter protection. Do not invent
  `Login`/`Activate`/`ConfirmEmail` secret-bearing queue messages from older planning vocabulary. Supply all required
  User, activation/reset/email/session and audit repositories plus clocks, throttle, credential generator, password
  ports, token encoder/policy and one shared transactional Unit of Work; inspect its current constructor, not an
  old guide's abbreviated example.
- JWT decoding in Common checks signatures, **not** expiration, not-before, issuer/audience or complete identity.
  Yii must validate its chosen token claims before building `AuthenticationContext`; then the request-local User
  provider rechecks ACTIVE User, session ownership/usability, authentication version and exact Role/Permission state.
  A valid signature or caller Role list is not current authority. Browser refresh-cookie/CSRF/CORS policy remains WF-005.
- Refresh has a typed rotated/conflict result and terminal rejection. A conflict emits no new credential; replay
  outside the configured conflict window is not a harmless retry. Token lifetimes are Application policy, not Domain
  constants. Password reset and confirmed email change revoke sessions; delivery status does not prove redemption
  eligibility. Keep raw credentials, digests, ciphertext and diagnostic exceptions out of responses/logs/events.
- Persist required `ActivationOrigin`, exact grant/delivery revisions, claim/lease/history and reset
  `RecoveryAuthority` (issuing authentication version and canonical-email revision). Never infer these from current
  User state during hydration. Required User-before-reset-grant fences couple validation, cancellation/material
  destruction, mutation and audit. Provider invocation is outside transactions and is at-least-once with exact
  delivery-ID idempotency. A previously admitted late provider call cannot revive cancelled authority.

### Principals, policy and Agent operations

- Users derive Roles/Permissions from current authoritative storage; Agents hold direct `ADMIN_SAFE` Permissions,
  **not Roles**. `ROLE_SUPER_ADMIN` is a reserved authoritative managed identity, not a string bypass. Consumer
  entry-point policy protects all dispatch, query, CLI, worker, direct bus and no-op paths. Actor IDs and revision
  tokens are provenance/state expectations, not authorization. Last-admin/target/delegation policy remains consumer-owned.
- Role revision is a persisted random-incarnation/sequence token, including in complete-state CAS and registration
  snapshot comparison. Every writer participates in final identity/name/tier/reference fences through transaction
  completion. Managed-policy preview/apply need the same complete Permission/Role/User/Agent planner inputs; missing
  storage/reference data is not permission to remove or promote policy.
- Agent signed-request authentication needs the current Agent/Permission repositories, HMAC verifier and independent
  secret decipher, trusted clock, atomic nonce consumer and transactional Unit of Work. It consumes replay identity
  and rechecks current credential/assignment authority. Common's generic single-key HMAC authenticator is not an
  implementation of these richer multi-Agent contracts or their storage/nonce fences.
- Agent provision/rotation retain the original scoped operation key and canonical request before invocation;
  marker **2**, storage **1**, destination **1**, positive monotonic generation and explicit reconciliation evidence
  are contract inputs, not guessed defaults. Exact registered destination revisions, stable-slot ordering,
  original-operation correlation, separate authentication encryption and bounded delivery copies must survive restart.
- Claim is not permission to decrypt; confirmed bounded admission permits the fixed sink outside database
  transactions, followed by a distinct current-authority receipt acknowledgement. Preserve permanent deduplication
  tombstones and slot high-water evidence after material cleanup. No raw-secret getter, automatic secret reissue,
  arbitrary destination URL, general enrollment broker, or continuing activation/use authority is supplied.
- Consumer-held cryptographic keys, actual sink idempotency/receipt verification, concurrent writer fences,
  external restoration witness, all-writer admission and activation/use checks require their own real-composition
  proof. Package tests and this census do not establish them. WF-001 does not authorize production restore tooling.

### Transactions, notifications and realtime

`TransactionalUnitOfWork::commitTransactional(callable): mixed` is the supported shared contract. AccessControl's
handlers/services require it, not the legacy standalone `commit()` interface. The Yii adapter is a composition
option already selected locally; constructor compatibility is not MySQL/concurrency qualification. One real
connection must cover every participant and required audit, reject nested execution and avoid silent callback replay.

Pre-commit failure and post-commit publication failure differ. A thrown post-commit fault does not undo committed
state. Unknown/lost commit acknowledgement needs current persisted-state reconciliation, not guessed rollback or
blind replay. Recoverable Agent provision/rotation alone have their typed committed-result publication warning;
other operations do not inherit it. Safe constant failure notifications do not sanitize the original rethrown fault.
No generic outbox, guaranteed fact replay, exactly-once mail or distributed provider transaction ships.

Workers may perform post-commit mail/realtime publication and scheduled recovery/expiry, but cannot become an
asynchronous substitute for authoritative commands. Common's private publisher is not an authorized subscription
service. WF-007 must define client-safe event projection, topic authorization, retry/deduplication, scheduler bounds
and signal/failure handling against Symfony's realtime contract, without forwarding identity-bearing package facts raw.
Sources: [operation outcomes](https://github.com/johnnickell/fight-access-control/blob/319738255e682d587642b67856039f882b9588d6/docs/operation-outcomes.md),
[Agent delivery](https://github.com/johnnickell/fight-access-control/blob/319738255e682d587642b67856039f882b9588d6/docs/agent-credential-delivery.md),
[Agent recovery](https://github.com/johnnickell/fight-access-control/blob/319738255e682d587642b67856039f882b9588d6/docs/agent-delivery-recovery.md),
[Agent maintenance](https://github.com/johnnickell/fight-access-control/blob/319738255e682d587642b67856039f882b9588d6/docs/agent-delivery-maintenance.md),
[cohort contract](https://github.com/johnnickell/fight-access-control/blob/319738255e682d587642b67856039f882b9588d6/docs/agent-operation-cohorts.md),
[restoration safety](https://github.com/johnnickell/fight-access-control/blob/319738255e682d587642b67856039f882b9588d6/docs/agent-restoration-safety.md).

## Common capabilities

All Common families are inventoried; availability is not a requirement to add unrelated starter features. Their
primary disposition is composition-only unless stated otherwise. Exact concrete symbols, legacy names and source
locations are in the [census](wf-001-public-surface.md); the [document inventory](wf-001-consumer-documents.md) links
the owning component guides.

| ID | Released family | Disposition / consumer boundary |
| --- | --- | --- |
| C01 | Values (strings/JSON, identifiers, email/URI/IP/phone, decimal/currency/money, calendar/time/ranges/duration), collections, comparators, types, specifications, utilities, exceptions and 13 helper functions | Composition-only; no endpoints for each utility and no payment/timezone/network policy implied. Preserve strict versus legacy construction and StreamId migration behavior. |
| C02 | HMAC signing/authentication, nonce ports, password hashing/validation, token encoder/decoder, legacy AI/webhook operation types | Composition-only security primitives; current Agent authentication is AC12, not generic HMAC wiring. Deprecated AI/webhook names are not a new public operations API. |
| C03 | JSend semantic envelopes/status, PSR-17 factories, JSON middleware, application-failure categories and safe/legacy HTTP error policies | Composition-only response/error adaptation. Safe handlers are opt-in; old arbitrary-message errors are not redaction. WF-003 must map failures explicitly rather than importing package messages/statuses wholesale. |
| C04 | Pagination/ResultSet, repository ports, narrow transactional UoW, Yii/other persistence adapters and Doctrine value mappings | Composition-only; new composition uses narrow transactions and strict caller pagination. Domain repository interfaces remain AccessControl's contracts; no generated consumer schema/ORM mapping is supplied. |
| C05 | Event-sourced aggregates/streams, mapping/upcasting, event stores, aggregate repositories, projections/checkpoints and durable publication/cursors/failure records | Composition-only optional subsystem, not evidence that AccessControl uses event sourcing or that its notifications have an outbox. No event-store management API implied. |
| C06 | Message IDs/envelopes/metadata, Command/Query/Event ports, configured routing/pipelines/filters/dispatchers, canonical Messenger serialization/buses | Composition-only; canonical transport-neutral worker message handlers are Worker boundaries into configured synchronous buses, never direct package-handler bypasses. Preserve envelope identity and detached metadata. |
| C07 | Domain serialization contracts and canonical Application JSON/PHP serializers | Composition-only; never deserialize untrusted PHP/object types or serialize secret-bearing authentication/provider objects merely because another type supports a serializer. |
| C08 | Framework-free container helpers and selected bounded Yii service providers | Composition-only; Yii owns provider list, lifetimes and collaborators. Do not replace Yii DI, import a framework bundle or register every optional capability by discovery. |
| C09 | Application validation/rules/errors/coordinator/attributes | Composition-only request/use-case validation; attributes alone do not run Yii validation. Clear per-operation state and independently allowlist safe public field errors. |
| C10 | Read-through cache, mutable cache capability, PSR-6/PSR-16 adapters | Composition-only; selected cache storage/policy stays Yii-owned. No cache-clear administration endpoint implied. |
| C11 | HTTP service/client, PSR-18 bridge, Guzzle promises/factories and logging decorator | Composition-only outbound transport; no user-provided fetch/proxy API. Bound timeouts/addresses and redact outbound credentials in the consuming composition. |
| C12 | Mail message/factory/attachments/transports and public/private socket publishers | Composition-only infrastructure, used by Worker effects when selected. Symfony Mailer fallback and private Mercure do not prove email idempotency, topic authorization or subscriber authentication. |
| C13 | Process descriptor/builder/runner, scheduler/jobs/locks/timezone and mail/log notification seams | Composition-only configured runtime; CLI scheduled entry/worker invocation is consumer-owned. Do not expose shell/cron control as generic HTTP operations or treat process retry as safe business replay. |
| C14 | FileStorage registry/Flysystem and filesystem contracts/adapters | Composition-only selected storage/filesystem policy, no arbitrary filesystem endpoint. Preserve Symfony Filesystem fallback; no inference of credential-sink receipt/order guarantees from generic file storage. |
| C15 | FileTransfer resources/FTP/SFTP/logging/null and SMS transport/factory/message providers | Composition-only optional capabilities, not initial product journeys. Optional provider dependencies, credentials, destinations, contact consent and logging policy are consumer-owned; none were enabled by this audit. |
| C16 | URL generation, template engines/helpers/native Yii View/Twig/portable engine composition | Composition-only. Yii owns routes, template roots and presentation; helper/engine classes are not independent API operations. Retain local accepted View selection rather than stale prototype prose. |
| C17 | Health aggregation/checks/results, metrics and audit ports/implementations/filters | Composition-only operational evidence; public/private diagnostic exposure remains a consumer decision. Null/logging audit adapters do not satisfy AccessControl's required atomic durable audit repository by name alone. |
| C18 | Optional FightCommon PHPCS standard/sniffs | CLI development tooling, already selected locally; no application endpoint or runtime feature. Do not restore framework certification mechanisms. |
| C19 | MCP semantic responder/registries/Tool discovery/invocation, protected input/atomic confirmation, Resources/Skills, bounded metadata/cursors/results, progress/cancellation and PSR HTTP/SSE adapters | Composition-only optional protocol integration; no automatic API/MCP server or arbitrary resource acquisition. Supported protocol is bounded `2026-07-28`; no composed Prompts, subscriptions, Skill directory RPC or URL elicitation guarantee. Protected Agent Tools use AC12's fresh authority. |
| C20 | OAuth resource metadata/scopes/requirements/validated claims/validator/handoff/diagnostics and guarded PSR adapters | Composition-only optional resource-server boundary, not token issuance or business authorization. Generic JWT signature decoding is insufficient; real validator checks exact issuer/audience/time/type/algorithm/key/scopes. No fallback from failed Bearer to HMAC. |
| X01 | Type-level `@internal` declarations (10 Common, four AccessControl) | Unsupported as independent public use cases; retained explicitly in the census so public PHP visibility is not mistaken for a supported application boundary. Resolver wiring ambiguity is recorded below. |
| X02 | Other-framework-only adapters and unselected native Mail/Filesystem prototypes | Unsupported in the selected Yii composition, not a blanket assertion that the package lacks other-framework support. Portable selected Symfony fallbacks remain composition-only above. No stable Yii Queue adapter ships. |
| X03 | Composer-exported Common TestCase helpers and fixture declarations | Unsupported as product runtime API; inventoried because Composer exports that namespace. Package test fixtures/conformance hooks are not production adapters or consumer qualification. |

Key Common sources beyond its changelog and Composer contract:
[Auth](https://github.com/johnnickell/fight-common/blob/87b9580c072f3149d574e09865daa79620c92118/docs/auth.md),
[Yii composition](https://github.com/johnnickell/fight-common/blob/87b9580c072f3149d574e09865daa79620c92118/docs/frameworks/yii/index.md),
[repositories](https://github.com/johnnickell/fight-common/blob/87b9580c072f3149d574e09865daa79620c92118/docs/repositories.md),
[messaging](https://github.com/johnnickell/fight-common/blob/87b9580c072f3149d574e09865daa79620c92118/docs/messaging.md),
[safe errors](https://github.com/johnnickell/fight-common/blob/87b9580c072f3149d574e09865daa79620c92118/docs/safe-http-errors.md),
[MCP](https://github.com/johnnickell/fight-common/blob/87b9580c072f3149d574e09865daa79620c92118/docs/components/mcp/index.md).

## Documentation gaps and unsupported boundaries

| Finding | Evidence and disposition / owner |
| --- | --- |
| AccessControl says 0.8.0 is prepared/unpublished and 0.7.0 latest | Installed dist/lock metadata and matching exact archive are the audit subject; stale README/release prose does not turn it into a development branch. Package documentation owner should reconcile publication prose through its own workflow. No Yii package edit/release is authorized. |
| Yii map and WF-003 named `resources/openapi/` | The released tree, Composer suggestion, guide and bootstrap all name `openapi/`. Correct only this source-location fact in local planning; retain one consumer-owned OpenAPI 3.1 generation pass. swagger-php is optional and is not installed in Yii by this research. |
| User resolver is both documented wiring and annotated internal | Released README requires `CurrentPrincipalProvider` with `AuthoritativePrincipalResolver`; the provider constructor requires it, while the resolver's class PHPDoc says `@internal`. Treat it as package-owned implementation, not a direct transport/domain API. Before relying on a promised stable external binding, WF-006 needs the AccessControl owner's clarification of the supported composition seam. Do not copy it, alias it or claim arbitrary internal invocation is supported. |
| Older behavioral vocabulary and shortened snippets | Umbrella requirements mention older principal/command wording; Common Auth examples shorten constructor composition and one JWT example labels failures too broadly. Released declarations and explicit current security warnings own actual signatures. No `ConfirmPasswordResetDelivery` bypass remains; JWT signature decoding does not check time/issuer/audience. |
| Common Yii guide still calls View a prototype | Existing local architecture/TASK-00008 evidence selects native Yii View, with Symfony Mailer/Filesystem fallbacks. Keep that accepted local selection; no package-wide framework-support assertion or native adapter reselection follows. |
| No consumer production infrastructure | AccessControl has no database schema/production repositories, ciphers, provider, authorization policy, queue, enrollment broker, HTTP routes, UI or complete OpenAPI document. Common adapters provide individual mechanisms, not those missing application policies. WF-004–WF-008 own their decisions and real integration proof. |
| No test evidence promotion | Shipped guides describe controlled package tests and consumer-bindable scenarios, sometimes in test-only namespaces. None were run as consumer qualification here. Actual MySQL races/restart/rollback, provider idempotency, cryptography/sink/restore behavior, authorization and browser protections remain unverified. Do not recreate retired Yii certification artifacts to represent those gaps. |

The internal finding is directly inspectable in
[resolver PHPDoc and implementation](https://github.com/johnnickell/fight-access-control/blob/319738255e682d587642b67856039f882b9588d6/src/Application/AccessControl/Authorization/Service/AuthoritativePrincipalResolver.php#L24)
and the
[provider constructor](https://github.com/johnnickell/fight-access-control/blob/319738255e682d587642b67856039f882b9588d6/src/Application/AccessControl/Authorization/Service/CurrentPrincipalProvider.php#L25).
This is a documentary support-boundary ambiguity, not a demonstrated product defect or a reason to infer insecure behavior.

## Completeness and downstream handoff

- Symbol accounting: **1,124 unique declarations**, each assigned exactly once to AC01–AC18, C01–C20 or X01–X03;
  **4,817 directly declared public members** listed under those owners, no duplicate declaration identities, all
  1,106 scoped PHP files parsed. Types marked internal remain visible but explicitly excluded from independent API use.
- Schema accounting: **125/125 unique named components** indexed with immutable sources; scan-only anchors/loader
  are composition-only, including schemas for worker-only and map-excluded operations.
- Documentation accounting: **135/135 `docs/` Markdown paths** classified, with historical/retired/contributor-only
  material distinguished. The workflow tables cover User, Agent, policy, Features, delivery, MCP and every Common
  component family; no source declaration is used as a substitute for a consumer journey.
- No broader correctness claim: this is not independent technical Review/QA, exhaustive method-body/security audit,
  package release acceptance, consumer test execution or a product build result. It defines the facts/limits for
  subsequent planning, not a gate that future builds must maintain.

| Owner / next decision | Traceable input and remaining decision |
| --- | --- |
| WF-003 — Yii ADR and Shared OpenAPI Contract | Use actual `openapi/` anchors and safe explicit response projections; keep one Yii document. Needs an immutable accepted Symfony wire contract before the planned prototype, not guessed URLs/statuses from package schemas. |
| WF-004 — Persistence and administrator bootstrap | Implement current repository ports and persisted fields/CAS/fences on one real connection; inventory every writer and durable audit. Existing initial-admin invitation is CLI-only; this research supplies no SQL/schema choice. |
| WF-005 — Authentication and account security | Synchronous secret methods, strict token claim policy, current-authority binding, typed refresh outcomes, cookie/CSRF/abuse controls and safe error mapping; no Common decoder shortcut. |
| WF-006 — Principal and authorization mapping | User versus direct-Permission Agent authority, exact-current reads, protected alternate entry points, stable seed IDs and resolver-support clarification from AccessControl owner. |
| WF-007 — Async and realtime | Exact bounded delivery/expiry/maintenance work, independent scheduled recovery, confirmed-commit versus notification outcomes, safe event projection and private subscription policy. No implied outbox or exactly-once promise. |
| WF-008 — Complete HTTP operation matrix | Account for each concrete candidate message/service method from the census; do not expose composition/worker-only APIs or map-excluded registration. Choose the final operation contract only with the upstream wire decisions. |

WF-001 can close with these documented boundaries and open downstream inputs. WF-002 was already Closed.
WF-003 is the next decision in authored order, but its external gate is **not evidenced by a linked immutable
accepted Symfony wire artifact here**. This is an input gap, not a claim about another repository's actual progress.
No available decision frontier is asserted until the Symfony contract owner provides that artifact and the Yii map
owner records satisfaction. Do not start WF-003's prototype or advance decomposition from this census alone.

Next: the Symfony wire-contract owner supplies the accepted immutable OpenAPI/wire artifact (repository, full
revision, artifact path and acceptance reference); the Yii map owner verifies the gate, then
`/skill:wayfinder work WF-003` resumes the next decision.
