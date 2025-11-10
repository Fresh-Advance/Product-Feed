<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

namespace FreshAdvance\ProductFeed\DTO;

interface ProductInterface
{
    public function getProductId(): string;

    public function getName(): string;

    public function getBrand(): string;

    public function getShortDescription(): string;

    public function getLongDescription(): string;

    public function getPrice(): string;

    public function getWeight(): string;

    public function getCategory(): string;

    public function getImageUrl(): string;

    public function getUrl(): string;

    public function getAvailability(): string;

    public function isSearchEnabled(): bool;

    public function getUpdatedTime(): string;
}
