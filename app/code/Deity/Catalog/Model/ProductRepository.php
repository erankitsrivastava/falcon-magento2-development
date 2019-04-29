<?php
declare(strict_types=1);

namespace Deity\Catalog\Model;

use Deity\CatalogApi\Api\Data\ProductDetailInterface;
use Deity\CatalogApi\Api\Data\ProductDetailInterfaceFactory;
use Deity\CatalogApi\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface as MagentoProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class ProductRepository
 *
 * @package Deity\Catalog\Model
 */
class ProductRepository implements ProductRepositoryInterface
{

    /**
     * @var ProductDetailInterfaceFactory
     */
    private $productDetailFactory;

    /**
     * @var MagentoProductRepositoryInterface
     */
    private $magentoRepository;

    /**
     * ProductRepository constructor.
     * @param ProductDetailInterfaceFactory $productDetailFactory
     * @param MagentoProductRepositoryInterface $magentoRepository
     */
    public function __construct(
        ProductDetailInterfaceFactory $productDetailFactory,
        MagentoProductRepositoryInterface $magentoRepository
    ) {
        $this->productDetailFactory = $productDetailFactory;
        $this->magentoRepository = $magentoRepository;
    }

    /**
     * Get product info
     *
     * @param string $sku
     * @return \Deity\CatalogApi\Api\Data\ProductDetailInterface
     * @throws NoSuchEntityException
     */
    public function get(string $sku): ProductDetailInterface
    {
        $productObject = $this->magentoRepository->get($sku);

        return $this->productDetailFactory->create(
            ['id' => (int)$productObject->getId()]
        );
    }
}
