<?php
declare(strict_types=1);

namespace Deity\CatalogSearchApi\Test\Api;

use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class SearchAutocompleteTest
 *
 * @package Deity\CatalogSearchApi\Test\Api
 */
class SearchAutocompleteTest extends WebapiAbstract
{
    private const AUTOCOMPLETE_REST_PATH = '/V1/falcon/catalog-search/autocomplete?q=#query';

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogSearchApi/Test/_files/searchable_products.php
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogSearchApi/Test/_files/search_reindex.php
     */
    public function testProductAutocomplete()
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace('#query', 'simple', self::AUTOCOMPLETE_REST_PATH),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        $response = $this->_webApiCall($serviceInfo);

        $this->assertEquals(3, count($response), 'Three items expected to be returned');
    }
}
