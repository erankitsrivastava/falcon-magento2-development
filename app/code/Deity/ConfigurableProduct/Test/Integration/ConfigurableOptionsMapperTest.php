<?php
declare(strict_types=1);

namespace Deity\ConfigurableProduct\Test\Integration;

use Deity\CatalogApi\Api\Data\ProductDetailExtensionInterface;
use Deity\CatalogApi\Api\Data\ProductDetailInterface;
use Deity\CatalogApi\Api\ProductRepositoryInterface;
use Magento\Framework\App\State;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigurableOptionsMapperTest
 *
 * @package Deity\ConfigurableProduct\Test\Integration
 * @magentoAppArea webapi_rest
 */
class ConfigurableOptionsMapperTest extends TestCase
{

    /** @var \Magento\Framework\ObjectManagerInterface */
    protected $objectManager;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var State
     */
    private $state;

    protected function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->productRepository = $this->objectManager->create(ProductRepositoryInterface::class);
        $this->state = $this->objectManager->create(State::class);
    }

    /**
     * @param string $sku
     * @return ProductDetailInterface
     * @throws \Exception
     */
    private function getProductObjectFromAPI(string $sku)
    {
        /** @var \Deity\CatalogApi\Api\ProductRepositoryInterface $productApiInstance */
        $productApiInstance = $this->objectManager->create(ProductRepositoryInterface::class);
        return $productApiInstance->get($sku);
    }

    /**
     * @magentoDataFixture Magento/ConfigurableProduct/_files/product_configurable.php
     * @throws \Exception
     */
    public function testMap()
    {
        $productObject = $this->getProductObjectFromAPI('configurable');

        $this->assertEquals(
            10,
            $productObject->getPrice()->getRegularPrice(),
            "Configurable product price should match minimal price between simple products"
        );

        /** @var ProductDetailExtensionInterface $extensionAttributes */
        $extensionAttributes = $productObject->getExtensionAttributes();

        $this->assertTrue(
            method_exists(
                $extensionAttributes,
                'getConfigurableProductOptions'
            ),
            "Product API should return configurable product options"
        );

        $this->assertTrue(
            method_exists(
                $extensionAttributes,
                'getConfigurableProductLinks'
            ),
            "Product API should return configurable product links"
        );

        $productLinks = $extensionAttributes->getConfigurableProductLinks();
        $this->assertEquals(2, count($productLinks), 'Configurable product should have 2 simple products');
        /** @var \Magento\ConfigurableProduct\Api\Data\OptionInterface[] $producConfigurableOptions */
        $producConfigurableOptions = $extensionAttributes->getConfigurableProductOptions();

        $this->assertEquals(1, count($producConfigurableOptions), "Product should have one option");

        $configurableOption = array_pop($producConfigurableOptions);
        /** @var \Magento\ConfigurableProduct\Api\Data\OptionValueInterface[] $values */
        $values = $configurableOption->getValues();
        $this->assertEquals(2, count($values), "Product option should have 2 values");

        $value = array_pop($values);
        /** @var \Magento\ConfigurableProduct\Api\Data\OptionValueExtensionInterface $valueExtensionAttribute */
        $valueExtensionAttribute = $value->getExtensionAttributes();

        $this->assertTrue(
            method_exists(
                $valueExtensionAttribute,
                'getInStock'
            ),
            "Value extension attribute should have in_stock field"
        );

        $inStock = $valueExtensionAttribute->getInStock();

        $this->assertTrue(
            is_array($inStock),
            "in stock should be an array of values"
        );

        $this->assertEquals(1, count($inStock), "Option should have a product in stock");

        $this->assertTrue(
            method_exists(
                $valueExtensionAttribute,
                'getLabel'
            ),
            "Value extension attribute should have label field"
        );
    }
}
