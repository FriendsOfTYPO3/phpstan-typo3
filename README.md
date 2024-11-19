# TYPO3 extension for PHPStan

> [!NOTE]
> `friendsoftypo3/phpstan-typo3` is obsolete and has been retired.
> The package dependency should be removed from TYPO3 project or extension development: `composer rem --dev friendsoftypo3/phpstan-typo3`.
> See below for transition options.

## Migrating away from friendsoftypo3/phpstan-typo3 since TYPO3 core v12

### Switch to saschaegerer/phpstan-typo3

[Sascha's package saschaegerer/phpstan-typo3](https://packagist.org/packages/saschaegerer/phpstan-typo3) can be an
alternative.

### Remove package and rely on core annotations

* The list of global TYPO3 specific constants shrunk over time and phpstan finds more of the remaining ones by default.
  phpstan may mumble about constants `LF` and `CR` not being defined. They can be made known to phpstan like this in
  a `phpstan.neon` config file:
  ```
    parameters:
      bootstrapFiles:
      - phpstan.bootstrap.php
  ```
  File `phpstan.bootstrap.php` then contains:
  ```
    <?php
    define('LF', chr(10));
    define('CR', chr(13));
  ```
* `GeneralUtility::makeInstance()`,  `Context->getAspect()`, `Query->execute()` and `QueryInterface->execute()`
  have proper method annotations since TYPO3 v12, the phpstan extension classes are not needed anymore and
  phpstan "understands" return values of these methods out of the box.
* `ObjectManagerInterface->get()` and `ObjectManager->get()` extensions have been removed from TYPO3 since v12
  and are thus obsolete.

## TYPO3 CMS class reflection extension for PHPStan &amp; framework-specific rules

This extension provides the following features:

* Provides correct return type for `\TYPO3\CMS\Core\Context\Context->getAspect()`.
* Provides correct return type for `\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance()`.
* Provides correct return type for `\TYPO3\CMS\Extbase\Object\ObjectManagerInterface->get()`.
* Provides correct return type for `\TYPO3\CMS\Extbase\Object\ObjectManager->get()`.
* Provides correct return type for `\TYPO3\CMS\Extbase\Persistence\Generic\Query->execute()`.
* Provides correct return type for `\TYPO3\CMS\Extbase\Persistence\QueryInterface->execute()`.

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

## FAQ

> I found this extension and the one from Sascha (`saschaegerer/phpstan-typo3`). Why are there two extensions and which should I use?

Well, this package has one specific purpose. It's made to help making the TYPO3 core phpstan max level compatible. To achieve this, the core team needs to be able to have its own extension which can be quickly adjusted as soon as the core itself changes. If for example, a new core version is released, the core team can quickly raise the dependency constraints for `typo3/cms-core` and `typo3/cms-extbase` which cannot be done when working with Sascha's package.

Also, Sascha's package contains dynamic return type providers that are not needed (yet) to make the core more compatible with phpstan.

To sum it all up: There is no competition between both extensions and this extension should not be used by users but only by the TYPO3 core.
