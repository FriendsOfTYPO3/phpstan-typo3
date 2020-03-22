<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\PHPStan\TYPO3\Tests\StaticCodeAnalysis\ObjectManager\Get;

use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

class CallWithStatic
{
    public function foo(ObjectManagerInterface $objectManager): self
    {
        return $objectManager->get(static::class);
    }

    public function bar(ObjectManager $objectManager): self
    {
        return $objectManager->get(static::class);
    }
}
