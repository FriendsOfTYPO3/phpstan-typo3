<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\PHPStan\TYPO3\Type;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicStaticMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\Type;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class GeneralUtilityDynamicReturnTypeExtension implements DynamicStaticMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return GeneralUtility::class;
    }

    public function isStaticMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'makeInstance';
    }

    public function getTypeFromStaticMethodCall(MethodReflection $methodReflection, StaticCall $methodCall, Scope $scope): Type
    {
        if (empty($methodCall->args)) {
            return new ObjectWithoutClassType();
        }

        /** @var Arg $argument */
        $argument = $methodCall->args[0];

        /** @var Expr $argumentValue */
        $argumentValue = $argument->value;

        if (!$argumentValue instanceof ClassConstFetch) {
            return new ObjectWithoutClassType();
        }
        /** @var ClassConstFetch $argumentValue */

        $class = $argumentValue->class;

        if (!$class instanceof Name) {
            return new ObjectWithoutClassType();
        }
        /** @var Name $class */

        $className = $class->toString();

        return new ObjectType($className);
    }
}
