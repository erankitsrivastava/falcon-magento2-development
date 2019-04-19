<?php
declare(strict_types=1);

namespace Deity\CatalogSearchApi\Model\Autocomplete;

/**
 * Interface DataProviderInterface
 *
 * @package Deity\CatalogSearchApi\Model\Autocomplete
 */
interface DataProviderInterface
{
    /**
     * @param string $query
     * @return \Deity\CatalogSearchApi\Api\Data\AutocompleteItemInterface[]
     */
    public function getAutocompleteItemsForQuery(string $query): array;
}
