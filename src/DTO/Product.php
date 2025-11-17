<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\DTO;

final class Product implements ProductInterface
{
    /**
     * @SuppressWarnings("PHPMD.ExcessiveParameterList")
     */
    public function __construct(
        private readonly string $productId = '',
        private readonly string $name = '',
        private readonly string $brand = '',
        private readonly string $shortDescription = '',
        private readonly string $longDescription = '',
        private readonly string $price = '',
        private readonly string $weight = '',
        private readonly string $category = '',
        private readonly string $imageUrl = '',
        private readonly string $url = '',
        private readonly string $availability = '',
        private readonly bool $searchEnabled = false,
        private readonly string $updatedTime = ''
    ) {
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }

    public function getLongDescription(): string
    {
        return $this->longDescription;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function getWeight(): string
    {
        return $this->weight;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getAvailability(): string
    {
        return $this->availability;
    }

    public function isSearchEnabled(): bool
    {
        return $this->searchEnabled;
    }

    public function getUpdatedTime(): string
    {
        return $this->updatedTime;
    }
}
