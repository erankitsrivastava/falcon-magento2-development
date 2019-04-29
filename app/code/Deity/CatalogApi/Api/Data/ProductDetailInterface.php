<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api\Data;

/**
 * Interface ProductDetailInterface
 *
 * @package Deity\CatalogApi\Api\Data
 */
interface ProductDetailInterface
{
    /**
     * Get product id
     *
     * @return int
     */
    public function getId(): int;
}
