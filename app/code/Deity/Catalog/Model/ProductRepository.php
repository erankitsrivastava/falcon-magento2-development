<?php
declare(strict_types=1);

namespace Deity\Catalog\Model;

use Deity\CatalogApi\Api\Data\ProductDetailInterface;
use Deity\CatalogApi\Api\Data\ProductDetailInterfaceFactory;
use Deity\CatalogApi\Api\MediaGalleryProviderInterface;
use Deity\CatalogApi\Api\ProductImageProviderInterface;
use Deity\CatalogApi\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface as MagentoProductRepositoryInterface;
use Magento\Catalog\Model\Product;
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
     * @var ProductImageProviderInterface
     */
    private $imageProvider;

    /**
     * @var MediaGalleryProviderInterface
     */
    private $mediaGalleryProvider;

    /**
     * ProductRepository constructor.
     * @param ProductDetailInterfaceFactory $productDetailFactory
     * @param MediaGalleryProviderInterface $mediaGalleryProvider
     * @param ProductImageProviderInterface $productImageProvider
     * @param MagentoProductRepositoryInterface $magentoRepository
     */
    public function __construct(
        ProductDetailInterfaceFactory $productDetailFactory,
        MediaGalleryProviderInterface $mediaGalleryProvider,
        ProductImageProviderInterface $productImageProvider,
        MagentoProductRepositoryInterface $magentoRepository
    ) {
        $this->mediaGalleryProvider = $mediaGalleryProvider;
        $this->imageProvider = $productImageProvider;
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
        /** @var Product $productObject */
        $productObject = $this->magentoRepository->get($sku);

        $mainImage = $this->imageProvider->getProductImageTypeUrl($productObject, 'product_page_image_large');
        $imageResized = $this->imageProvider->getProductImageTypeUrl($productObject, 'product_list_thumbnail');

        $mediaGalleryInfo = $this->mediaGalleryProvider->getMediaGallerySizes($productObject);

        return $this->productDetailFactory->create(
            [
                ProductDetailInterface::ID_FIELD_KEY => (int)$productObject->getId(),
                ProductDetailInterface::SKU_FIELD_KEY => (string)$productObject->getSku(),
                ProductDetailInterface::IS_SALABLE_FIELD_KEY => (int)$productObject->getIsSalable(),
                ProductDetailInterface::NAME_FIELD_KEY => (string)$productObject->getName(),
                ProductDetailInterface::TYPE_ID_FIELD_KEY => (string)$productObject->getTypeId(),
                ProductDetailInterface::IMAGE_FIELD_KEY => $mainImage,
                ProductDetailInterface::IMAGE_RESIZED_FIELD_KEY => $imageResized,
                ProductDetailInterface::MEDIA_GALLERY_FIELD_KEY => $mediaGalleryInfo
            ]
        );
    }
}
