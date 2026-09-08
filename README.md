# Project Yii

Public Yii 3 application composition for Fight libraries. The starter consumes Fight Common and Fight AccessControl
only through their public Composer packages and keeps Yii configuration, dependency injection, HTTP, console, and
presentation composition local.

Run `./bin/up`, then open <http://localhost:8088/> for the hello-world runtime. Use `./bin/build` before review;
GitHub Actions delegates to that exact command for pushes and pull requests targeting `develop`, `main`, and
`release/**`.

## Commands

- `./bin/console list` runs the Yii-native console.
- `./bin/phpunit` runs the PHPUnit integration suite in the application container.
- `./bin/build` is the complete noninteractive quality gate used locally and by GitHub Actions.

This repository is MIT-licensed source, not a release or package-distribution claim. Do not add copied shared
source, credentials, production data, tags, Packagist publication, template enablement, or create-project
distribution without a separately adopted local ticket.

## Deployment security

Configure these environment variables in every deployment: `FIGHT_HMAC_PUBLIC` for the nonblank HMAC identity,
`FIGHT_HMAC_PRIVATE` for the 32-byte hexadecimal HMAC secret, and `FIGHT_JWT_SECRET` for the 32-byte hexadecimal
JWT secret. `FIGHT_JWT_ALGORITHM` selects the JWT algorithm and defaults to `HS256` when unset.
