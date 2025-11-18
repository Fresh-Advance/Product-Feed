<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Tests\Unit\Transformer;

use FreshAdvance\ProductFeed\DTO\Product;
use FreshAdvance\ProductFeed\Transformer\ProductToArrayTransformer;
use FreshAdvance\ProductFeed\Transformer\ProductToArrayTransformerInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ProductToArrayTransformerTest extends TestCase
{
    #[Test]
    public function transformsProductToArray(): void
    {
        $product = new Product(
            productId: $productId = uniqid('product_'),
            name: $name = uniqid('name_'),
            brand: $brand = uniqid('brand_'),
            shortDescription: $shortDescription = uniqid('short_'),
            longDescription: $longDescription = uniqid('long_'),
            price: $price = uniqid('price_'),
            weight: $weight = uniqid('weight_'),
            category: $category = uniqid('category_'),
            imageUrl: $imageUrl = uniqid('image_'),
            url: $url = uniqid('url_'),
            availability: $availability = uniqid('availability_'),
            searchEnabled: $searchEnabled = (bool)rand(0, 1),
            updatedTime: $updatedTime = date('Y-m-d H:i:s')
        );

        $transformer = $this->getSut();
        $result = $transformer->toArray($product);

        $this->assertSame($productId, $result['id']);
        $this->assertSame($brand, $result['brand']);
        $this->assertSame($name, $result['name']);
        $this->assertSame($shortDescription, $result['short_description']);
        $this->assertSame($longDescription, $result['long_description']);
        $this->assertSame($price, $result['price']);
        $this->assertSame($weight, $result['weight']);
        $this->assertSame($category, $result['category']);
        $this->assertSame($imageUrl, $result['image_url']);
        $this->assertSame($url, $result['url']);
        $this->assertSame($availability, $result['availability']);
        $this->assertSame($searchEnabled, $result['enable_search']);
        $this->assertSame($updatedTime, $result['last_updated']);
    }

    private function getSut(): ProductToArrayTransformerInterface
    {
        return new ProductToArrayTransformer();
    }
}
