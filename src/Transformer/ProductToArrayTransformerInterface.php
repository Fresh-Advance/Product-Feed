<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

namespace FreshAdvance\ProductFeed\Transformer;

use FreshAdvance\ProductFeed\DTO\ProductInterface;

/**
 * @phpstan-type ProductAsArray array{
 *     id: string,
 *     brand: string,
 *     name: string,
 *     short_description: string,
 *     long_description: string,
 *     price: string,
 *     weight: string,
 *     category: string,
 *     image_url: string,
 *     url: string,
 *     availability: string,
 *     enable_search: bool,
 *     last_updated: string
 * }
 */
interface ProductToArrayTransformerInterface
{
    /**
     * @return ProductAsArray
     */
    public function toArray(ProductInterface $product): array;
}
