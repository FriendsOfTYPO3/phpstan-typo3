<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\PHPStan\TYPO3\Tests\Unit\Type;

use FriendsOfTYPO3\PHPStan\TYPO3\Type\ContextGetAspectDynamicReturnTypeExtension;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\ObjectType;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Context\AspectInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\DateTimeAspect;
use TYPO3\CMS\Core\Context\LanguageAspect;
use TYPO3\CMS\Core\Context\TypoScriptAspect;
use TYPO3\CMS\Core\Context\UserAspect;
use TYPO3\CMS\Core\Context\VisibilityAspect;
use TYPO3\CMS\Core\Context\WorkspaceAspect;

class ContextGetAspectDynamicReturnTypeExtensionTest extends TestCase
{
    /**
     * @var ContextGetAspectDynamicReturnTypeExtension
     */
    private $extension;

    protected function setUp(): void
    {
        $this->extension = new ContextGetAspectDynamicReturnTypeExtension();
    }

    public function testGetClass(): void
    {
        static::assertSame(Context::class, $this->extension->getClass());
    }

    /**
     * @return \Generator<int,array<int, string|bool>>
     */
    public function dataIsMethodSupported(): \Generator
    {
        yield ['getAspect', true];
        yield ['foo', false];
    }

    /**
     * @dataProvider dataIsMethodSupported
     * @param string $method
     * @param bool $expectedResult
     */
    public function testIsMethodSupported(string $method, bool $expectedResult): void
    {
        $methodReflection = $this->prophesize(MethodReflection::class);
        $methodReflection->getName()->willReturn($method);

        self::assertSame($expectedResult, $this->extension->isMethodSupported($methodReflection->reveal()));
    }

    public function testGetTypeFromMethodCallReturnsAspectInterfaceAsDefault(): void
    {
        $scope = $this->prophesize(Scope::class)->reveal();
        $methodCall = $this->prophesize(Expr\MethodCall::class)->reveal();
        $methodReflection = $this->prophesize(MethodReflection::class)->reveal();

        /** @var ObjectType $type */
        $type = $this->extension->getTypeFromMethodCall($methodReflection, $methodCall, $scope);

        static::assertInstanceOf(ObjectType::class, $type);
        static::assertSame(AspectInterface::class, $type->getClassName());
    }

    /**
     * @return \Generator<int,array<int, string>>
     */
    public function getTypeFromMethodCallDataProvider(): \Generator
    {
        yield ['date', DateTimeAspect::class];
        yield ['visibility', VisibilityAspect::class];
        yield ['frontend.user', UserAspect::class];
        yield ['backend.user', UserAspect::class];
        yield ['workspace', WorkspaceAspect::class];
        yield ['language', LanguageAspect::class];
        yield ['typoscript', TypoScriptAspect::class];
        yield ['unknown', AspectInterface::class];
    }

    /**
     * @dataProvider getTypeFromMethodCallDataProvider
     * @param string $argumentValue
     * @param string $expectedClassName
     */
    public function testGetTypeFromMethodCall(string $argumentValue, string $expectedClassName): void
    {
        $string = $this->prophesize(String_::class)->reveal();
        $string->value = $argumentValue;

        $arg = $this->prophesize(Arg::class)->reveal();
        $arg->value = $string;

        $methodCall = $this->prophesize(Expr\MethodCall::class)->reveal();
        $methodCall->args = [$arg];

        /** @var ObjectType $type */
        $type = $this->extension->getTypeFromMethodCall(
            $this->prophesize(MethodReflection::class)->reveal(),
            $methodCall,
            $this->prophesize(Scope::class)->reveal()
        );

        static::assertInstanceOf(ObjectType::class, $type);
        static::assertSame($expectedClassName, $type->getClassName());
    }
}
