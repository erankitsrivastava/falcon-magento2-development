<?php
declare(strict_types=1);

namespace Deity\UrlRewrite\Model\Data;

use Deity\UrlRewriteApi\Api\Data\UrlRewriteInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

/**
 * @package Deity\UrlRewrite
 */
class UrlRewrite extends AbstractExtensibleObject implements UrlRewriteInterface
{
    /**
     * @inheritdoc
     */
    public function getEntityType(): string
    {
        return (string)$this->_get(self::ENTITY_TYPE);
    }

    /**
     * @inheritdoc
     */
    public function setEntityType(string $entityType): void
    {
        $this->setData(self::ENTITY_TYPE, $entityType);
    }

    /**
     * @inheritdoc
     */
    public function getEntityId(): int
    {
        return (int)$this->_get(self::ENTITY_ID);
    }

    /**
     * @inheritdoc
     */
    public function setEntityId(int $id): void
    {
        $this->setData(self::ENTITY_ID, $id);
    }

    /**
     * @return string
     */
    public function getCanonicalUrl(): string
    {
        return (string)$this->_get(self::CANONICAL_URL);
    }

    /**
     * @param string $url
     * @return void
     */
    public function setCanonicalUrl(string $url): void
    {
        $this->setData(self::CANONICAL_URL, $url);
    }
}
