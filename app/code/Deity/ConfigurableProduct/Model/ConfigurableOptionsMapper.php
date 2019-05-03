<?php
declare(strict_types=1);

namespace Deity\ConfigurableProduct\Model;

use Deity\CatalogApi\Api\Data\ProductDetailInterface;
use Deity\CatalogApi\Model\ProductMapperInterface;
use Magento\Catalog\Api\Data\ProductExtension;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\CatalogInventory\Api\Data\StockStatusInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

/**
 * Class ConfigurableOptionsMapper
 *
 * @package Deity\ConfigurableProduct\Model
 */
class ConfigurableOptionsMapper implements ProductMapperInterface
{

    /**
     * @var StockRegistryInterface
     */
    private $stockRegistry;

    /**
     * ConfigurableOptionsMapper constructor.
     * @param StockRegistryInterface $stockRegistry
     */
    public function __construct(StockRegistryInterface $stockRegistry)
    {
        $this->stockRegistry = $stockRegistry;
    }

    /**
     * Perform mapping of magento product to falcon product
     *
     * @param ProductInterface $product
     * @param ProductDetailInterface $falconProduct
     */
    public function map(ProductInterface $product, ProductDetailInterface $falconProduct): void
    {
        if ($product->getTypeId() != Configurable::TYPE_CODE) {
            return;
        }
        /** @var ProductExtension $extensionAttributes */
        $extensionAttributes = $product->getExtensionAttributes();

        $falconExtensionAttributes = $falconProduct->getExtensionAttributes();

        /** @var \Magento\ConfigurableProduct\Api\Data\OptionInterface[] $configurableOptions */
        $configurableOptions = $extensionAttributes->getConfigurableProductOptions();

        /** @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable $typeInstance */
        $typeInstance = $product->getTypeInstance();
        $childProducts = $typeInstance->getUsedProducts($product);
        /** @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable\Attribute[] $attributes */
        $attributes = $typeInstance->getConfigurableAttributes($product);

        foreach ($attributes as $attribute) {
            $attribute->getAttributeId();
        }

        $configurableAttributeOptions = $typeInstance->getConfigurableOptions($product);

        $listOfStocks = [];
        foreach ($childProducts as $childProduct) {
            if ($this->stockRegistry->getProductStockStatus($childProduct->getId())
                    == StockStatusInterface::STATUS_IN_STOCK
            ) {
                $listOfStocks[] = $childProduct->getSku();
            }
        }

        foreach ($configurableOptions as $configurableOption) {
            $attributeData = $configurableAttributeOptions[$configurableOption->getAttributeId()];
            foreach ($configurableOption->getValues() as $value) {
                $valueIndex = $value->getValueIndex();
                $optionExtensionAttributes = $value->getExtensionAttributes();

                $matchSkus = array_keys(array_column($attributeData, 'value_index', 'sku'), $valueIndex);
                $inStockList = array_intersect($matchSkus, $listOfStocks);
                $optionExtensionAttributes->setInStock($inStockList);
                $optionExtensionAttributes->setLabel(
                    array_search($valueIndex, array_column($attributeData, 'value_index', 'option_title'))
                );
                $value->setExtensionAttributes($optionExtensionAttributes);
            }
        }

        $falconExtensionAttributes->setConfigurableProductOptions($configurableOptions);
        $falconExtensionAttributes->setConfigurableProductLinks($extensionAttributes->getConfigurableProductLinks());

        $falconProduct->setExtensionAttributes($falconExtensionAttributes);
    }
}
