<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser;

use BrandEmbassy\QueryLanguageParser\Grammar\QueryLanguageGrammarConfiguration;
use BrandEmbassy\QueryLanguageParser\Grammar\QueryLanguageGrammarFactory;
use Ferno\Loco\Grammar;
use Ferno\Loco\GrammarException;
use Ferno\Loco\ParseFailureException;

final class QueryParser
{
    /**
     * @var QueryLanguageGrammarConfiguration
     */
    private $grammarConfiguration;

    /**
     * @var QueryLanguageGrammarFactory
     */
    private $grammarFactory;

    /**
     * @var Grammar|null
     */
    private $grammar;


    public function __construct(
        QueryLanguageGrammarConfiguration $grammarConfiguration,
        QueryLanguageGrammarFactory $grammarFactory
    ) {
        $this->grammarConfiguration = $grammarConfiguration;
        $this->grammarFactory = $grammarFactory;
        $this->grammar = null;
    }


    /**
     * @return mixed|null
     *
     * @throws UnableToParseQueryException
     */
    public function parse(string $query)
    {
        try {
            if ($this->grammar === null) {
                $fields = $this->grammarConfiguration->getFields();
                $operators = $this->grammarConfiguration->getOperators();
                $this->grammar = $this->grammarFactory->create($fields, $operators);
            }

            return $this->grammar->parse($query);
        } catch (GrammarException | ParseFailureException $e) {
            throw UnableToParseQueryException::byOtherException($e);
        }
    }


    public function clearGrammarCache(): void
    {
        $this->grammar = null;
    }
}
