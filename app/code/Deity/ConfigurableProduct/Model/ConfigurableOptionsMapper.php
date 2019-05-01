<?php
declare(strict_types=1);

namespace Deity\ConfigurableProduct\Model;

use Deity\CatalogApi\Api\Data\ProductDetailInterface;
use Deity\CatalogApi\Model\ProductMapperInterface;
use Magento\Catalog\Api\Data\ProductExtension;
use Magento\Catalog\Api\Data\ProductInterface;

/**
 * Class ConfigurableOptionsMapper
 *
 * @package Deity\ConfigurableProduct\Model
 */
class ConfigurableOptionsMapper implements ProductMapperInterface
{

    /**
     * Perform mapping of magento product to falcon product
     *
     * @param ProductInterface $product
     * @param ProductDetailInterface $falconProduct
     */
    public function map(ProductInterface $product, ProductDetailInterface $falconProduct): void
    {
        /** @var ProductExtension $extensionAttributes */
        $extensionAttributes = $product->getExtensionAttributes();

        $falconExtensionAttributes = $falconProduct->getExtensionAttributes();

        $falconExtensionAttributes->setConfigurableProductOptions($extensionAttributes->getConfigurableProductOptions());
        $falconExtensionAttributes->setConfigurableProductLinks($extensionAttributes->getConfigurableProductLinks());

        $falconProduct->setExtensionAttributes($falconExtensionAttributes);
    }
}
