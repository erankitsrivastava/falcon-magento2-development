<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Model;

use Deity\CatalogApi\Api\Data\ProductDetailInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class ProductMapperChain
 *
 * @package Deity\CatalogApi\Model
 */
class ProductMapperChain implements ProductMapperInterface
{

    /**
     * @var ProductMapperInterface[]
     */
    private $mappers;

    /**
     * ProductMapperChain constructor.
     * @param ProductMapperInterface[] $mappers
     * @throws LocalizedException
     */
    public function __construct(array $mappers = [])
    {
        foreach ($mappers as $mapper) {
            if (!$mapper instanceof  ProductMapperInterface) {
                throw new LocalizedException(
                    __('Product Mapper must implement ProductMapperInterface.')
                );
            }
        }

        $this->mappers = $mappers;
    }

    /**
     * Perform mapping of magento product to falcon product
     *
     * @param ProductInterface $product
     * @param ProductDetailInterface $falconProduct
     */
    public function map(ProductInterface $product, ProductDetailInterface $falconProduct): void
    {
        foreach ($this->mappers as $mapper) {
            $mapper->map($product, $falconProduct);
        }
    }
}
