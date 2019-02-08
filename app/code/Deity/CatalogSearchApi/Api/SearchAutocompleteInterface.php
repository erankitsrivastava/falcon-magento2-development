<?php
declare(strict_types=1);

namespace Deity\CatalogSearchApi\Api;

/**
 * Interface SearchAutocompleteInterface
 *
 * @package Deity\CatalogSearchApi\Api
 * @api
 */
interface SearchAutocompleteInterface
{
    /**
     * Main search method
     *
     * @param string $q
     * @return []
     */
    public function search($q);
}
