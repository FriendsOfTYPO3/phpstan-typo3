# TYPO3 extension for PHPStan

TYPO3 CMS class reflection extension for PHPStan &amp; framework-specific rules

## Configuration

To use this extension, require it in [Composer](https://getcomposer.org/):

```
composer require friendsoftypo3/phpstan-typo3 --dev
```

Once installed, put this into your `phpstan.neon` config:

```
includes:
    - vendor/friendsoftypo3/phpstan-typo3/extension.neon
```
