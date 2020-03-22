<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\PHPStan\TYPO3\Tests\Fixtures;

use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

class ObjectManagerGetCallWithSelf
{
    public function foo(ObjectManagerInterface $objectManager): self
    {
        return $objectManager->get(self::class);
    }

    public function bar(ObjectManager $objectManager): self
    {
        return $objectManager->get(self::class);
    }
}
