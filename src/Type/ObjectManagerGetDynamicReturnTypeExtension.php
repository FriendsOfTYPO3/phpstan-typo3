<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\PHPStan\TYPO3\Type;

use TYPO3\CMS\Extbase\Object\ObjectManager;

class ObjectManagerGetDynamicReturnTypeExtension extends ObjectManagerInterfaceGetDynamicReturnTypeExtension
{
    public function getClass(): string
    {
        return ObjectManager::class;
    }
}
