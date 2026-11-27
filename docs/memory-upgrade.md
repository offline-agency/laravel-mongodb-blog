# Memoria upgrade PHP

## Ultima esecuzione: 2026-11-27, PHP 8.6 (port a Laravel 13)
- Modalità: **LEGACY**, PHP supportati 8.4, 8.5, 8.6. Motivo: Laravel 6 non supporta PHP 8.4; CI su PHP 7.3; vincolo `php ^7.2`.
- Applicazione Laravel (`type: project`), non fork. Branch: `chore/php-8.6-upgrade`.
- Binari Herd: php84 (8.4.28), php85 (8.5.13), php86 (8.6.0), tutti con `ext-mongodb`. Nessun driver di coverage.

## Origine di laravel-mongo-auto-sync
- **Usato: branch della PR** (non ancora su Packagist: ultima release 3.1.1). `composer.json` ha il repository `vcs` `https://github.com/offline-agency/laravel-mongo-auto-sync` e richiede `"dev-chore/php-8.6-upgrade as 4.0.0"`.
- Fonte: [PR #101](https://github.com/offline-agency/laravel-mongo-auto-sync/pull/101), aperta. Il merge pubblica 4.0.0.
- **Passaggio a `^4.0` ancora in sospeso.** Alla prossima esecuzione, se 4.0.0 è su Packagist e il branch richiede ancora la PR: (1) `^4.0` e rimozione del `vcs`; (2) rigenerare `composer.lock` con php84; (3) test su 8.4/8.5/8.6; (4) commit `chore(deps): require laravel-mongo-auto-sync ^4.0` e push; (5) `gh pr ready`; (6) poi Step 4, 6, 7.

## Gate: VIA LIBERA (con auto-sync PENDING)
Albero risolto su 8.4, 8.5 e 8.6. Framework target: Laravel 13 (13.44.1 nel lock).

| Pacchetto | Prima | Dopo | Verdetto |
|---|---|---|---|
| php | ^7.2 | ^8.4 | BUMP |
| laravel/framework | ^6.20 | ^13.0 | BUMP |
| offline-agency/laravel-mongo-auto-sync | ^1.0 (lock 1.20) | dev-chore/php-8.6-upgrade as 4.0.0 | PENDING (offline-agency/laravel-mongo-auto-sync#101) |
| jenssegers/mongodb | 3.6.7 (transitivo) | mongodb/laravel-mongodb 5.12.2 (via auto-sync) | sparisce con auto-sync 4.0 |
| laravel/ui | assente | ^4.6 | aggiunto (i trait `AuthenticatesUsers` ecc. sono usciti dal framework) |
| laravel/tinker | ^1.0 | ^3.0 | BUMP |
| fzaninotto/faker | ^1.9 | fakerphp/faker ^1.24 | REPLACE (abbandonato) |
| fideloper/proxy | ^4.0 | rimosso | REPLACE (integrato nel framework) |
| facade/ignition | ^1.4 | rimosso | non più necessario su Laravel 13 |
| nunomaduro/collision | ^3.0 | ^8.0 | BUMP |
| phpunit/phpunit | ^8.0 | ^12.0 | BUMP (12.5.45) |
| mockery/mockery | ^1.0 | ^1.6 | BUMP |
| ext-mongodb, ext-json | * | * | OK |

## Passi del port (skeleton Laravel 13 in /tmp, nessuna salita major per major)
Commit sul branch:
1. `chore(laravel): port the application to Laravel 13 on PHP 8.4-8.6` (dipendenze, `composer.lock` generato con php84, `bootstrap/app.php`, config, rotte, modelli, helper, seeder).
2. `test: add feature tests for every route`.
3. `ci: test on PHP 8.4, 8.5 and 8.6 with a MongoDB service`.
4. `docs: document PHP 8.4-8.6 and Laravel 13 requirements and the MongoDB test setup`.
5. `docs: update upgrade memory`.

Nota: i commit 1 e 2 non sono verdi da soli (il primo non ha test, il secondo li aggiunge); l'insieme è verificato dal commit 3 in poi.

Cosa è stato portato:
- Rotte: `[Controller::class, 'method']`, stessi URI, nomi, ordine e middleware (restano `/admin/admin/categories…`, i `{$id}` letterali e il catch-all `/{slug}`). `routes/api.php` mantenuta (`api/user`, gruppo `api` con `throttle:60,1`).
- `bootstrap/app.php` sostituisce kernel HTTP/console, middleware e provider (Auth, Event, Route, Broadcast). `redirectUsersTo('/home')` replica il vecchio `RedirectIfAuthenticated`. Rimossi: `TrustProxies` (proxies null = già il default), `config/broadcasting.php`, `config/hashing.php`, `config/view.php`, `routes/channels.php`.
- Config dal nuovo skeleton, con i default precedenti: cache `file`, sessione `file`, coda `sync`, mail `smtp`, DB di default `mongodb` + connessione `mongodb` invariata, `fallback_locale` `en_US`, disco `local` con `serve` = false (nessuna rotta `storage/{path}`).
- `.env.example`: `CACHE_DRIVER` → `CACHE_STORE`, `BROADCAST_DRIVER` → `BROADCAST_CONNECTION`.
- `App\User`: `$collection` → `$table`; **`MongoSyncTrait` non esiste più in auto-sync 4.0**, sostituito dai trait che usa `MDModel` (`Helper, MainMongoTrait, ModelAdditionalMethod, PlainMongoTrait, RelationshipMongoTrait`). Non è documentato in `docs/v4/upgrading.md` della PR #101: da segnalare ai maintainer.
- Namespace `Jenssegers\Mongodb\…` → `MongoDB\Laravel\…` nei docblock dei modelli e in `User`.
- Date: laravel-mongodb 5 restituisce `Carbon` per i campi data. `formatDate()` e `fixDate()` accettano `UTCDateTime|DateTimeInterface` e producono la stessa stringa.
- Seeder: `database/seeds` → `database/seeders` (`Database\Seeders`), `DB::collection()` → `DB::table()`. `UserFactory` riscritta in forma a classe.
- Deprecazioni del repo corrette: `$faker->name` / `->safeEmail` → metodi `name()` / `safeEmail()` (faker 1.14+).

## Problemi incontrati
- Porta 27017 occupata dal container `mongodb_` di un altro progetto: ho creato `blog-mongo` (mongo:8.0) su **27018** senza toccare l'altro. Per i test locali: `MONGO_PORT=27018`. La CI usa 27017.
- PHP 7.4 con il sandbox: servono `-d pcre.jit=0`.
- `MongoSyncTrait` mancante (vedi sopra).
- Le viste admin degli articoli sono template statici: i test asseriscono sui dati passati alla vista (`assertViewHas`), non sull'HTML.

## Test
- 31 test, 98 asserzioni, verdi su PHP 8.4, 8.5 e 8.6 con MongoDB 8.0 (Docker). Installazione pulita da clone con php84, senza `--ignore-platform-reqs`.
- Il test `GET /` disabilitato è stato ripristinato. Coperte: pagine pubbliche, login/logout, admin (dashboard, indice/crea/modifica articoli, indice/crea/store/destroy categorie), redirect degli ospiti, seeder, comando `drop:collection`, helper di data.
- Nessun `--prefer-lowest` (la CI originale non lo faceva).
- Copertura non misurata (nessun driver).

### Rotte lasciate senza test (bug presenti anche su master)
| Rotta | Motivo |
|---|---|
| `POST admin/articles/store` | `$arr['title']` non definito (`ArticleCrudController::store`) |
| `POST admin/articles/{id}` | stesso `$arr['title']` non definito in `update` |
| `POST admin/articles/{id}/delete` | dopo `destroyWithSync()` restituisce la vista inesistente `admin.article.index` |
| `GET admin/admin/categories/{$id}/edit` | `{$id}` è letterale: il controller non riceve `$id` (ArgumentCountError) |
| `POST admin/admin/categories/{$id}` | idem |
| `GET api/user` | guard `api` token senza utenti: non coperta |

## Elenco bug trovati e NON corretti
1. Rotte categorie: segmenti `{$id}` letterali e prefisso doppio `/admin/admin/categories…`.
2. `ArticleCrudController`: `$arr['title']` non definito in `store`/`update`; `update` fa redirect alla rotta inesistente `admin.article.index`; `destroy` restituisce la vista inesistente `admin.article.index`.
3. `CategoryCrudController`: `index` ordina per `publication_date` (campo inesistente su Category); `store` richiede `media_path` nella request ma il form non lo invia; `store`/`update`/`destroy` restituiscono la vista invece di un redirect; `destroy` fa solo `save()` e non `destroyWithSync()`.
4. `HomeController::categoryDetail` non risponde 404 se la categoria non esiste.
5. Catch-all `/{slug}` dichiarato prima di `/category/{slug}`.
6. `app/Http/Controllers/ArticleController.php`: file morto con namespace `Admin` sbagliato per PSR-4 e riferimenti a classi inesistenti (`BackendController`, `PublishArticleJob`, `State`…); Composer lo segnala a ogni `dump-autoload`.
7. I guest middleware reindirizzano a `/home`, che non esiste (404).
8. Controller e viste di registrazione, verifica email e reset password non hanno rotte.
9. Il comando `drop:collection` è registrato sia dall'app sia dal pacchetto auto-sync.
10. Le viste admin non mostrano dati (template statici).
11. L'opzione di connessione `database` in `config/database.php` (authSource legacy) non è stata provata con un MongoDB autenticato.

## Deprecazioni vendor
- PHP 8.6: `is_integer()` deprecata in `mongodb/mongodb` (`Operation/Find.php:182`, `Operation/CountDocuments.php:123`). Nessuna su 8.4 e 8.5 (con `E_ALL`, `LOG_DEPRECATIONS_WHILE_TESTING=true`).

## Quality checks
Pint e Larastan/PHPStan non sono installati nel repository (né negli script composer): saltati, nessun file di configurazione aggiunto.

## Branch / PR
`chore/php-8.6-upgrade`. PR in bozza (draft), da non marcare pronta finché auto-sync 4.0.0 non è su Packagist. Chiude #3, sostituisce #6.

## Follow-up aperti
1. Passare a `^4.0` quando 4.0.0 è su Packagist (procedura sopra), poi `gh pr ready`.
2. Segnalare ai maintainer di auto-sync che `MongoSyncTrait` non esiste più (non nella guida di upgrade).
3. Verificare la CI su PHP 8.6 (disponibilità di `setup-php` per 8.6 e di `ext-mongodb`).
4. Decidere se correggere i bug elencati; richiedono modifiche di comportamento, fuori dallo scopo del port.
5. Il lavoro di discoverability (metadata `composer.json`, README, ecc.) va fatto dopo.
