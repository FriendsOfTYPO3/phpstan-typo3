# TYPO3 extension for PHPStan

TYPO3 CMS class reflection extension for PHPStan &amp; framework-specific rules

This extension provides the following features:

* Provides correct return type for `\TYPO3\CMS\Core\Context\Context->getAspect()`.
* Provides correct return type for `\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance()`.
* Provides correct return type for `\TYPO3\CMS\Extbase\Object\ObjectManagerInterface->get()`.
* Provides correct return type for `\TYPO3\CMS\Extbase\Object\ObjectManager->get()`.

<details>
  <summary>Details on GeneralUtility::makeInstance()</summary>

  Dynamic return types are returned for:

  * `GeneralUtility::makeInstance(\TYPO3\CMS\Core\DataHandling\DataHandler::class)`
  * `GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\DataHandling\\DataHandler')`
  * `GeneralUtility::makeInstance(self::class)`
  * `GeneralUtility::makeInstance(static::class)`
</details>

<details>
  <summary>Details on ObjectManagerInterface::get() and ObjectManager::get()</summary>

  Dynamic return types are returned for:

  * `ObjectManager->get(\TYPO3\CMS\Core\DataHandling\DataHandler::class)`
  * `ObjectManager->get('TYPO3\\CMS\\Core\\DataHandling\\DataHandler')`
  * `ObjectManager->get(self::class)`
  * `ObjectManager->get(static::class)`
</details>

## Installation & Configuration

To use this extension, require it in [Composer](https://getcomposer.org/):

```
composer require friendsoftypo3/phpstan-typo3 --dev
```

Once installed, put this into your `phpstan.neon` config:

```
includes:
    - vendor/friendsoftypo3/phpstan-typo3/extension.neon
```
