<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\PHPStan\TYPO3\Tests\Fixtures;

use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$object = GeneralUtility::makeInstance(DataHandler::class);
$object->storeLogMessages = true;
