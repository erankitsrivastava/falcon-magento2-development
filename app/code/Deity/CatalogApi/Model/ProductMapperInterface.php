<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Model;

use Deity\CatalogApi\Api\Data\ProductDetailInterface;
use Magento\Catalog\Api\Data\ProductInterface;

/**
 * Interface ProductMapperInterface
 *
 * @package Deity\CatalogApi\Model
 */
interface ProductMapperInterface
{
    /**
     * Perform mapping of magento product to falcon product
     *
     * @param ProductInterface $product
     * @param ProductDetailInterface $falconProduct
     */
    public function map(ProductInterface $product, ProductDetailInterface $falconProduct): void;
}
