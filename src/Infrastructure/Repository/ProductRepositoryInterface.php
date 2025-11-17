<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

namespace FreshAdvance\ProductFeed\Infrastructure\Repository;

use FreshAdvance\ProductFeed\DTO\ProductInterface;

interface ProductRepositoryInterface
{
    /**
     * @return array<ProductInterface>
     */
    public function getProducts(): array;
}
