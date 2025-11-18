<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

namespace FreshAdvance\ProductFeed\Service;

use FreshAdvance\ProductFeed\Transformer\ProductToArrayTransformerInterface;

/**
 * @phpstan-import-type ProductAsArray from ProductToArrayTransformerInterface
 */
interface ProductServiceInterface
{
    /**
     * @return array<ProductAsArray>
     */
    public function getProductsArrayForJson(): array;
}
