<?php
declare(strict_types=1);

namespace Deity\CatalogSearch\Model\Data;

use Deity\CatalogSearchApi\Api\Data\AutocompleteItemExtensionInterface;
use Deity\CatalogSearchApi\Api\Data\AutocompleteItemInterface;
use Magento\Framework\Api\ExtensionAttributesFactory;

/**
 * Class AutocompleteItem
 *
 * @package Deity\CatalogSearch\Model\Data
 */
class AutocompleteItem implements AutocompleteItemInterface
{

    public const AUTOCOMPLETE_TYPE_SUGGESTION = 'suggestion';

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $url;

    /**
     * @var AutocompleteItemExtensionInterface
     */
    private $extensionAttributes;

    /**
     * @var ExtensionAttributesFactory
     */
    private $extensionAttributesFactory;

    /**
     * AutocompleteItem constructor.
     * @param string $title
     * @param string $url
     * @param ExtensionAttributesFactory $extensionAttributesFactory
     */
    public function __construct(
        string $title,
        string $url,
        ExtensionAttributesFactory $extensionAttributesFactory
    ) {
        $this->title = $title;
        $this->url = $url;
        $this->extensionAttributesFactory = $extensionAttributesFactory;
    }


    /**
     * Get item title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get item Url
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Get item type
     *
     * @return string
     */
    public function getType(): string
    {
        return self::AUTOCOMPLETE_TYPE_SUGGESTION;
    }

    /**
     * Get extension attributes
     *
     * @return \Deity\CatalogSearchApi\Api\Data\AutocompleteItemExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        if (!$this->extensionAttributes) {
            $this->extensionAttributes = $this->extensionAttributesFactory->create(AutocompleteItemInterface::class);
        }

        return $this->extensionAttributes;
    }

    /**
     * Set extension attributes
     *
     * @param \Deity\CatalogSearchApi\Api\Data\AutocompleteItemExtensionInterface $extensionAttributes
     * @return \Deity\CatalogSearchApi\Api\Data\AutocompleteItemInterface
     */
    public function setExtensionAttributes(AutocompleteItemExtensionInterface $extensionAttributes): AutocompleteItemInterface
    {
        $this->extensionAttributes = $extensionAttributes;
        return $this;
    }
}
