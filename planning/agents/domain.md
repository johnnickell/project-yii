# Yii architecture boundary

Yii owns configuration-provider composition, DI bindings, HTTP and console entry points, Twig presentation, and
future framework adapters. Shared Fight packages remain Composer dependencies; copied Domain/Application trees and
unpublished-package internals are prohibited.
