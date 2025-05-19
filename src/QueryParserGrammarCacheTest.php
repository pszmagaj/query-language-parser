<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser;

use BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage\CarQueryParserFactory;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

final class QueryParserGrammarCacheTest extends TestCase
{
    public function testGrammarIsCachedBetweenParseCalls(): void
    {
        $parser = (new CarQueryParserFactory())->create();

        $reflection = new ReflectionProperty(QueryParser::class, 'grammar');
        $reflection->setAccessible(true);

        $parser->parse('brand=bmw');
        $firstGrammar = $reflection->getValue($parser);

        $parser->parse('brand=audi');
        $secondGrammar = $reflection->getValue($parser);

        Assert::assertSame($firstGrammar, $secondGrammar);
    }

    public function testCacheCanBeCleared(): void
    {
        $parser = (new CarQueryParserFactory())->create();

        $reflection = new ReflectionProperty(QueryParser::class, 'grammar');
        $reflection->setAccessible(true);

        $parser->parse('brand=bmw');
        $firstGrammar = $reflection->getValue($parser);

        $parser->clearGrammarCache();

        $parser->parse('brand=audi');
        $secondGrammar = $reflection->getValue($parser);

        Assert::assertNotSame($firstGrammar, $secondGrammar);
    }
}
