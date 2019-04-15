<?php
declare(strict_types=1);

namespace Deity\CatalogSearch\Model;

use Deity\CatalogSearchApi\Api\SearchAutocompleteInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\State;
use Magento\Search\Model\AutocompleteInterfaceFactory;
use Magento\Search\Model\AutocompleteInterface;

/**
 * Class CatalogSearchAutocomplete
 *
 * @package Deity\CatalogSearch\Model
 */
class CatalogSearchAutocomplete implements SearchAutocompleteInterface
{
    /**
     * @var AutocompleteInterfaceFactory
     */
    protected $autocompleteFactory;
    /**
     * @var RequestInterface
     */
    protected $request;
    /**
     * @var State
     */
    protected $state;

    /**
     * CatalogSearchAutocomplete constructor.
     *
     * @param Context $context
     * @param AutocompleteInterfaceFactory $autocomplete
     * @param State $state
     */
    public function __construct(
        Context $context,
        AutocompleteInterfaceFactory $autocomplete,
        State $state
    ) {
        $this->autocompleteFactory = $autocomplete;
        $this->request = $context->getRequest();
        $this->state = $state;
    }

    /**
     * @inheritdoc
     */
    public function search(string $q): array
    {
        if (!$this->request->getParam('q', false)) {
            return [];
        }
        return $this->state->emulateAreaCode(
            'frontend',
            function (AutocompleteInterface $autocompleteObject) {
                $responseData = [];
                foreach ($autocompleteObject->getItems() as $resultItem) {
                    $responseData[] = $resultItem->toArray();
                }
                return $responseData;
            },
            [$this->autocompleteFactory->create()]
        );
    }
}
