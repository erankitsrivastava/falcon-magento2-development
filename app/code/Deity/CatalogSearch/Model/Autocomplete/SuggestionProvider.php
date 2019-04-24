<?php
declare(strict_types=1);

namespace Deity\CatalogSearch\Model\Autocomplete;

use Deity\CatalogSearchApi\Api\Data\AutocompleteItemInterfaceFactory as ItemFactory;
use Deity\CatalogSearchApi\Model\Autocomplete\DataProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfig;
use Magento\Framework\Stdlib\StringUtils as StdlibString;
use Magento\Search\Helper\Data;
use Magento\Search\Model\Query;
use Magento\Search\Model\QueryFactory;
use Magento\Store\Model\ScopeInterface;

/**
 * Class SuggestionProvider
 *
 * @package Deity\CatalogSearch\Model\Autocomplete
 */
class SuggestionProvider implements DataProviderInterface
{
    /**
     * Autocomplete limit
     */
    const CONFIG_AUTOCOMPLETE_LIMIT = 'catalog/search/autocomplete_limit';

    public const AUTOCOMPLETE_TYPE_SUGGESTION = 'suggestion';

    /**
     * Query factory
     *
     * @var QueryFactory
     */
    private $queryFactory;

    /**
     * Autocomplete result item factory
     *
     * @var ItemFactory
     */
    private $itemFactory;

    /**
     * Limit
     *
     * @var int
     */
    private $limit;

    /**
     * @var Query
     */
    private $query;

    /**
     * @var StdlibString
     */
    private $string;

    /**
     * @var Data
     */
    private $queryHelper;

    /**
     * @param QueryFactory $queryFactory
     * @param ItemFactory $itemFactory
     * @param ScopeConfig $scopeConfig
     * @param StdlibString $string
     * @param Data $queryHelper
     */
    public function __construct(
        QueryFactory $queryFactory,
        ItemFactory $itemFactory,
        ScopeConfig $scopeConfig,
        StdlibString $string,
        Data $queryHelper
    ) {
        $this->string = $string;
        $this->queryHelper = $queryHelper;
        $this->queryFactory = $queryFactory;
        $this->itemFactory = $itemFactory;

        $this->limit = (int) $scopeConfig->getValue(
            self::CONFIG_AUTOCOMPLETE_LIMIT,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param string $query
     * @return \Deity\CatalogSearchApi\Api\Data\AutocompleteItemInterface[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getAutocompleteItemsForQuery(string $query): array
    {
        $collection = $this->get($query)->getSuggestCollection();
        $query = $this->queryFactory->get()->getQueryText();
        $result = [];
        foreach ($collection as $item) {
            $resultItem = $this->itemFactory->create([
                'title' => $item->getQueryText(),
                'url' => $item->getQueryText(),
                'type' => self::AUTOCOMPLETE_TYPE_SUGGESTION
            ]);
            if ($resultItem->getTitle() == $query) {
                array_unshift($result, $resultItem);
            } else {
                $result[] = $resultItem;
            }
        }
        return ($this->limit) ? array_splice($result, 0, $this->limit) : $result;
    }

    private function get(string $query)
    {
        if (!$this->query) {
            $maxQueryLength = $this->queryHelper->getMaxQueryLength();
            $minQueryLength = $this->queryHelper->getMinQueryLength();
            $rawQueryText = $this->getRawQueryText($query);
            $preparedQueryText = $this->getPreparedQueryText($rawQueryText, $maxQueryLength);
            $query = $this->queryFactory->create()->loadByQueryText($preparedQueryText);
            if (!$query->getId()) {
                $query->setQueryText($preparedQueryText);
            }
            $query->setIsQueryTextExceeded($this->isQueryTooLong($rawQueryText, $maxQueryLength));
            $query->setIsQueryTextShort($this->isQueryTooShort($rawQueryText, $minQueryLength));
            $this->query = $query;
        }
        return $this->query;
    }

    /**
     * Retrieve search query text
     *
     * @param string $queryText
     * @return string
     */
    private function getRawQueryText(string $queryText)
    {
        return ($queryText === null || is_array($queryText))
            ? ''
            : $this->string->cleanString(trim($queryText));
    }

    /**
     * @param string $queryText
     * @param int|string $maxQueryLength
     * @return string
     */
    private function getPreparedQueryText($queryText, $maxQueryLength)
    {
        if ($this->isQueryTooLong($queryText, $maxQueryLength)) {
            $queryText = $this->string->substr($queryText, 0, $maxQueryLength);
        }
        return $queryText;
    }

    /**
     * Check if query is Too Long
     *
     * @param string $queryText
     * @param int|string $maxQueryLength
     * @return bool
     */
    private function isQueryTooLong($queryText, $maxQueryLength)
    {
        return ($maxQueryLength !== '' && $this->string->strlen($queryText) > $maxQueryLength);
    }

    /**
     * Check if query is too short
     *
     * @param string $queryText
     * @param int|string $minQueryLength
     * @return bool
     */
    private function isQueryTooShort($queryText, $minQueryLength)
    {
        return ($this->string->strlen($queryText) < $minQueryLength);
    }
}
