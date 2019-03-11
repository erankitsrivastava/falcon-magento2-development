<?php
declare(strict_types=1);

namespace Deity\Catalog\Model;

use Deity\CatalogApi\Api\Data\FilterInterface;
use Deity\CatalogApi\Api\Data\FilterInterfaceFactory;
use Deity\CatalogApi\Api\Data\FilterOptionInterface;
use Deity\CatalogApi\Api\Data\FilterOptionInterfaceFactory;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Filter\AbstractFilter;
use Magento\Catalog\Model\Layer\Filter\Item;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\State\InitException;

/**
 * Class ProductFilterProvider
 *
 * @package Deity\Catalog\Model
 */
class ProductFilterProvider implements \Deity\CatalogApi\Api\ProductFilterProviderInterface
{

    /**
     * @var Layer\FilterList
     */
    private $filterList;

    /**
     * @var FilterInterfaceFactory;
     */
    private $filterFactory;

    /**
     * @var FilterOptionInterfaceFactory
     */
    private $filterOptionFactory;

    /**
     * @var string[]
     */
    private $filterValues = [];

    /**
     * ProductFilterProvider constructor.
     * @param Layer\FilterList $filterList
     * @param FilterInterfaceFactory $filterFactory
     * @param FilterOptionInterfaceFactory $filterOptionFactory
     */
    public function __construct(
        Layer\FilterList $filterList,
        FilterInterfaceFactory $filterFactory,
        FilterOptionInterfaceFactory $filterOptionFactory
    ) {
        $this->filterList = $filterList;
        $this->filterOptionFactory = $filterOptionFactory;
        $this->filterFactory = $filterFactory;
    }

    /**
     * @inheritdoc
     */
    public function getFilterList(Layer $layer, ?SearchCriteriaInterface $searchCriteria): array
    {
        if (!$layer->getCurrentCategory()->getIsAnchor()) {
            //if category is not marked is_anchor, do not return filter data
            return [];
        }

        $this->presetFilterValues($searchCriteria);
        
        /** @var AbstractFilter[] $magentoFilters */
        $magentoFilters = $this->filterList->getFilters($layer);
        $resultFilters = [];
        foreach ($magentoFilters as $magentoFilter) {
            if (!$magentoFilter->getItemsCount()) {
                continue;
            }
            $filterInitData = [];
            $filterInitData['label'] = (string)$magentoFilter->getName();
            if ($magentoFilter->getRequestVar() == 'cat') {
                $filterInitData['code'] = $magentoFilter->getRequestVar();
                $filterInitData['type'] = 'int';
                $filterInitData['attributeId'] = 0;
            } else {
                $filterInitData['code'] = $magentoFilter->getAttributeModel()->getAttributeCode();
                $filterInitData['type'] = $magentoFilter->getAttributeModel()->getBackendType();
                $filterInitData['attributeId'] = (int)$magentoFilter->getAttributeModel()->getAttributeId();
            }
            /** @var FilterInterface $filterObject */
            $filterObject = $this->filterFactory->create($filterInitData);
            $this->processSelectedOptionsForFilter($magentoFilter, $filterObject);
            $magentoOptions = $magentoFilter->getItems();
            /** @var Item $magentoOption */
            foreach ($magentoOptions as $magentoOption) {
                /** @var FilterOptionInterface $filterOption */
                $filterOption =$this->filterOptionFactory->create(
                    [
                        FilterOptionInterface::LABEL => (string)$magentoOption->getData('label'),
                        FilterOptionInterface::VALUE => $magentoOption->getValueString(),
                        FilterOptionInterface::COUNT => (int)$magentoOption->getData('count')
                    ]
                );
                $filterObject->addOption($filterOption);
            }

            $resultFilters[] = $filterObject;
        }
        return $resultFilters;
    }

    /**
     * Process selected items for selected filter
     *
     * @param AbstractFilter $magentoFilter
     * @param FilterInterface $filterObject
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function processSelectedOptionsForFilter(AbstractFilter $magentoFilter, FilterInterface $filterObject)
    {
        if (isset($this->filterValues[$filterObject->getCode()])) {
            foreach ($this->filterValues[$filterObject->getCode()] as $filterValue) {
                if ($magentoFilter->getRequestVar() == 'cat') {
                    $layer = $magentoFilter->getLayer();
                    $categoryObject = $layer->getCurrentCategory()->getChildrenCategories()->getItemById($filterValue);
                    if ($categoryObject === null) {
                        throw new InitException(__('Given category filter is not available'));
                    }
                    $filterLabel = $categoryObject->getName();
                } else {
                    $filterLabel = $magentoFilter
                        ->getAttributeModel()
                        ->getSource()
                        ->getOptionText($filterValue);
                }
                /** @var FilterOptionInterface $filterOption */
                $filterOption =$this->filterOptionFactory->create(
                    [
                        FilterOptionInterface::LABEL => (string)$filterLabel,
                        FilterOptionInterface::VALUE => (string)$filterValue,
                        FilterOptionInterface::COUNT => 0,
                        FilterOptionInterface::IS_SELECTED => true
                    ]
                );
                $filterObject->addOption($filterOption);
            }
        }
    }

    /**
     * Parse Filter Selected values
     *
     * @param SearchCriteriaInterface|null $searchCriteria
     * @return $this
     */
    private function presetFilterValues(?SearchCriteriaInterface $searchCriteria)
    {
        if ($searchCriteria === null) {
            return $this;
        }

        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $this->filterValues[$filter->getField()][] = $filter->getValue();
            }
        }

        return $this;
    }
}
