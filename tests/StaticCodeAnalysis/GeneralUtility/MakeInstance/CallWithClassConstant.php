<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\PHPStan\TYPO3\Tests\StaticCodeAnalysis\GeneralUtility\MakeInstance;

use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$object = GeneralUtility::makeInstance(DataHandler::class);
$object->storeLogMessages = true;
