# Local web runtime

Run `./bin/up` and open <http://localhost:8088/>. Nginx serves the Yii front controller through the PHP-FPM `app`
service. Use `./bin/down` to stop the full runtime, `./bin/exec php -v` for an arbitrary container command,
`./bin/composer install` for Composer, `./bin/console help` for the Yii-native console entry point, and
`./bin/phpunit` for the PHPUnit integration suite.

`./bin/build` is the noninteractive local and hosted gate. It validates the public Composer boundary, local
authority artifacts, and native Yii/Twig hello-world integration, then verifies a disposable production install.
