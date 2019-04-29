<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Data;

use Deity\CatalogApi\Api\Data\ProductDetailInterface;

/**
 * Class ProductDetail
 *
 * @package Deity\Catalog\Model\Data
 */
class ProductDetail implements ProductDetailInterface
{

    /**
     * @var int
     */
    private $id;

    /**
     * ProductDetail constructor.
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }


    /**
     * Get product id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
