<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Tests\Unit\DTO;

use FreshAdvance\ProductFeed\DTO\Product;
use FreshAdvance\ProductFeed\DTO\ProductInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ProductTest extends TestCase
{
    #[Test]
    public function implementsInterface(): void
    {
        $product = new Product();

        $this->assertInstanceOf(ProductInterface::class, $product);
    }

    #[Test]
    public function usesDefaultEmptyValues(): void
    {
        $product = new Product();

        $this->assertSame('', $product->getProductId());
        $this->assertSame('', $product->getName());
        $this->assertSame('', $product->getBrand());
        $this->assertSame('', $product->getShortDescription());
        $this->assertSame('', $product->getLongDescription());
        $this->assertSame('', $product->getPrice());
        $this->assertSame('', $product->getWeight());
        $this->assertSame('', $product->getCategory());
        $this->assertSame('', $product->getImageUrl());
        $this->assertSame('', $product->getUrl());
        $this->assertSame('', $product->getAvailability());
        $this->assertFalse($product->isSearchEnabled());
        $this->assertSame('', $product->getUpdatedTime());
    }

    #[Test]
    public function returnsCorrectValues(): void
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

        $this->assertSame($productId, $product->getProductId());
        $this->assertSame($name, $product->getName());
        $this->assertSame($brand, $product->getBrand());
        $this->assertSame($shortDescription, $product->getShortDescription());
        $this->assertSame($longDescription, $product->getLongDescription());
        $this->assertSame($price, $product->getPrice());
        $this->assertSame($weight, $product->getWeight());
        $this->assertSame($category, $product->getCategory());
        $this->assertSame($imageUrl, $product->getImageUrl());
        $this->assertSame($url, $product->getUrl());
        $this->assertSame($availability, $product->getAvailability());
        $this->assertSame($searchEnabled, $product->isSearchEnabled());
        $this->assertSame($updatedTime, $product->getUpdatedTime());
    }
}
