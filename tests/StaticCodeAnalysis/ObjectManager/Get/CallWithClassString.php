<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\PHPStan\TYPO3\Tests\StaticCodeAnalysis\ObjectManager\Get;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
$object = $objectManager->get('TYPO3\\CMS\\Core\\DataHandling\\DataHandler');
$object->storeLogMessages = true;
