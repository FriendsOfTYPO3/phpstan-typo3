<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\PHPStan\TYPO3\Tests\Unit\Type;

use FriendsOfTYPO3\PHPStan\TYPO3\Type\GeneralUtilityDynamicReturnTypeExtension;

use PhpParser\Lexer\Emulative;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\ParserFactory;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\ObjectType;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class GeneralUtilityDynamicReturnTypeExtensionTest extends TestCase
{
    /**
     * @var GeneralUtilityDynamicReturnTypeExtension
     */
    private $extension;

    protected function setUp(): void
    {
        $this->extension = new GeneralUtilityDynamicReturnTypeExtension();
    }

    public function testGetClass(): void
    {
        static::assertSame(GeneralUtility::class, $this->extension->getClass());
    }

    /**
     * @return \Generator<int,array<string|bool>>
     */
    public function dataIsMethodSupported(): \Generator
    {
        yield ['makeInstance', true];
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

        self::assertSame($expectedResult, $this->extension->isStaticMethodSupported($methodReflection->reveal()));
    }

    public function testGetTypeFromMethodCall(): void
    {
        $lexer = new Emulative(['usedAttributes' => []]);
        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7, $lexer);

        $code = <<<CODE
<?php

\$object = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\DataHandling\DataHandler::class);
CODE;

        $expressions = $parser->parse($code);
        static::assertIsArray($expressions);

        /** @var array $expressions */
        reset($expressions);

        /** @var Expression $expression */
        $expression = current($expressions);

        /** @var Assign $assignment */
        $assignment = $expression->expr;

        /** @var StaticCall $staticCall */
        $staticCall = $assignment->expr;

        /** @var ObjectType $type */
        $type = $this->extension->getTypeFromStaticMethodCall(
            $this->prophesize(MethodReflection::class)->reveal(),
            $staticCall,
            $this->prophesize(Scope::class)->reveal()
        );

        static::assertInstanceOf(ObjectType::class, $type);
        static::assertSame(DataHandler::class, $type->getClassName());
    }
}
