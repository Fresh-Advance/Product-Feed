<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Transformer;

use FreshAdvance\ProductFeed\DTO\ProductInterface;

class ProductToArrayTransformer implements ProductToArrayTransformerInterface
{
    public function toArray(ProductInterface $product): array
    {
        return [
            'id' => $product->getProductId(),
            'brand' => $product->getBrand(),
            'name' => $product->getName(),
            'short_description' => $product->getShortDescription(),
            'long_description' => $product->getLongDescription(),
            'price' => $product->getPrice(),
            'weight' => $product->getWeight(),
            'category' => $product->getCategory(),
            'image_url' => $product->getImageUrl(),
            'url' => $product->getUrl(),
            'availability' => $product->getAvailability(),
            'enable_search' => $product->isSearchEnabled(),
            'last_updated' => $product->getUpdatedTime(),
        ];
    }
}
