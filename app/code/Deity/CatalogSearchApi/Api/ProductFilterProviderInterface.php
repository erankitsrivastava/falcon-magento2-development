<?php
declare(strict_types=1);

namespace Deity\CatalogSearchApi\Api;

use Magento\Catalog\Model\Layer;

/**
 * Interface ProductFilterProviderInterface
 *
 * @package Deity\CatalogSearchApi\Api
 */
interface ProductFilterProviderInterface
{
    /**
     * Get filter list
     *
     * @param Layer $layer
     * @return \Deity\CatalogApi\Api\Data\FilterInterface[]
     */
    public function getFilterList(Layer $layer): array;
}
