<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Test\Api;

use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class ProductApiTest
 *
 * @package Deity\CatalogApi\Test\Api
 */
class ProductApiTest extends WebapiAbstract
{
    const PRODUCT_API_ENDPOINT = '/V1/falcon/products/:sku';

    /**
     * @param string $productSku
     * @return array
     */
    private function getProductData($productSku)
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':sku', $productSku, self::PRODUCT_API_ENDPOINT),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        return $this->_webApiCall($serviceInfo);
    }

    /**
     * @magentoApiDataFixture Magento/Catalog/_files/product_simple.php
     */
    public function testProductApiExist()
    {
        try {
            $resposeData = $this->getProductData('simple');
        } catch (\Exception $e) {
            $this->fail("Product data response expected");
        }
    }

}
