<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Model;

use Magento\Catalog\Model\Product;

/**
 * Interface ProductUrlPathProviderInterface
 *
 * @package Deity\CatalogApi\Model
 */
interface ProductUrlPathProviderInterface
{
    /**
     * Get product url path
     *
     * @param Product $product
     * @param int|null $categoryId
     * @return string
     */
    public function getProductUrlPath(Product $product, ?int $categoryId): string;
}
