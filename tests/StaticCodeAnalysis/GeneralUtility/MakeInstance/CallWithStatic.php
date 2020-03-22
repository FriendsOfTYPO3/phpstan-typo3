<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\PHPStan\TYPO3\Tests\StaticCodeAnalysis\GeneralUtility\MakeInstance;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class CallWithStatic
{
    public function create(): self
    {
        return GeneralUtility::makeInstance(static::class);
    }
}
