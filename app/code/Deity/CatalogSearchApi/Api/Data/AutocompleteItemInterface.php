<?php
declare(strict_types=1);

namespace Deity\CatalogSearchApi\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface AutocompleteItemInterface
 *
 * @package Deity\CatalogSearchApi\Api\Data
 */
interface AutocompleteItemInterface extends ExtensibleDataInterface
{

    /**
     * Get item title
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Get item Url
     *
     * @return string
     */
    public function getUrl(): string;

    /**
     * Get item type
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Get extension attributes
     *
     * @return \Deity\CatalogSearchApi\Api\Data\AutocompleteItemExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set extension attributes
     *
     * @param \Deity\CatalogSearchApi\Api\Data\AutocompleteItemExtensionInterface $extensionAttributes
     * @return \Deity\CatalogSearchApi\Api\Data\AutocompleteItemInterface
     */
    public function setExtensionAttributes(AutocompleteItemExtensionInterface $extensionAttributes): AutocompleteItemInterface;
}
