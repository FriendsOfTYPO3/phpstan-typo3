<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\PHPStan\TYPO3\Type;

use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_ as StringNode;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

class QueryExecuteReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return Query::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'execute';
    }

    public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): \PHPStan\Type\Type
    {
        if (empty($methodCall->args)) {
            return new ObjectType(QueryResultInterface::class);
        }

        $firstArgument = $scope->getType($methodCall->args[0]->value);
        if (!$firstArgument instanceof ConstantBooleanType) {
            throw new \InvalidArgumentException(
                'Argument $returnRawQueryResult is not a boolean.',
                1584879250
            );
        }

        $returnRawQueryResult = $firstArgument->getValue();
        if ($returnRawQueryResult) {
            return new ArrayType(
                new StringType(),
                new MixedType()
            );
        }

        return new ObjectType(QueryResultInterface::class);
    }
}