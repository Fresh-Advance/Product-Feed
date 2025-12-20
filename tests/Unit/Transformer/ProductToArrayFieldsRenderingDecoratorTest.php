<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Tests\Unit\Transformer;

use FreshAdvance\ProductFeed\DTO\ProductInterface;
use FreshAdvance\ProductFeed\Transformer\ProductToArrayTransformerInterface;
use FreshAdvance\ProductFeed\Transformer\ProductToArrayFieldsRenderingDecorator;
use OxidEsales\EshopCommunity\Internal\Framework\Templating\TemplateRendererInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ProductToArrayFieldsRenderingDecoratorTest extends TestCase
{
    #[Test]
    public function longDescriptionIsProcessedThroughTemplateRenderer(): void
    {
        $originalLongDescription = uniqid('original_long_description_');
        $renderedLongDescription = uniqid('rendered_long_description_');
        $productId = uniqid('product_');

        $originalTransformerResult = [
            'id' => $productId,
            'brand' => uniqid('brand_'),
            'name' => uniqid('name_'),
            'short_description' => uniqid('short_'),
            'long_description' => $originalLongDescription,
            'price' => uniqid('price_'),
            'weight' => uniqid('weight_'),
            'category' => uniqid('category_'),
            'image_url' => uniqid('image_'),
            'url' => uniqid('url_'),
            'availability' => uniqid('availability_'),
            'enable_search' => (bool)rand(0, 1),
            'last_updated' => date('Y-m-d H:i:s'),
        ];

        $productStub = $this->createStub(ProductInterface::class);

        $originalTransformerMock = $this->createMock(ProductToArrayTransformerInterface::class);
        $originalTransformerMock->method('toArray')
            ->with($productStub)
            ->willReturn($originalTransformerResult);

        $templateRendererMock = $this->createMock(TemplateRendererInterface::class);
        $templateRendererMock->expects($this->once())
            ->method('renderFragment')
            ->with($originalLongDescription, $productId . '_long_description', [])
            ->willReturn($renderedLongDescription);

        $sut = $this->getSut(
            originalTransformer: $originalTransformerMock,
            templateRenderer: $templateRendererMock
        );

        $result = $sut->toArray($productStub);

        $this->assertSame($renderedLongDescription, $result['long_description']);
    }

    #[Test]
    public function otherFieldsRemainUnchanged(): void
    {
        $innerTransformerResult = [
            'id' => $id = uniqid('product_'),
            'brand' => $brand = uniqid('brand_'),
            'name' => $name = uniqid('name_'),
            'short_description' => $shortDescription = uniqid('short_'),
            'long_description' => uniqid('original_'),
            'price' => $price = uniqid('price_'),
            'weight' => $weight = uniqid('weight_'),
            'category' => $category = uniqid('category_'),
            'image_url' => $imageUrl = uniqid('image_'),
            'url' => $url = uniqid('url_'),
            'availability' => $availability = uniqid('availability_'),
            'enable_search' => $searchEnabled = (bool)rand(0, 1),
            'last_updated' => $updatedTime = date('Y-m-d H:i:s'),
        ];

        $productStub = $this->createStub(ProductInterface::class);

        $originalTransformerStub = $this->createStub(ProductToArrayTransformerInterface::class);
        $originalTransformerStub->method('toArray')->willReturn($innerTransformerResult);

        $templateRendererStub = $this->createStub(TemplateRendererInterface::class);
        $templateRendererStub->method('renderFragment')->willReturn(uniqid('rendered_'));

        $sut = $this->getSut(
            originalTransformer: $originalTransformerStub,
            templateRenderer: $templateRendererStub
        );

        $result = $sut->toArray($productStub);

        $this->assertSame($id, $result['id']);
        $this->assertSame($brand, $result['brand']);
        $this->assertSame($name, $result['name']);
        $this->assertSame($shortDescription, $result['short_description']);
        $this->assertSame($price, $result['price']);
        $this->assertSame($weight, $result['weight']);
        $this->assertSame($category, $result['category']);
        $this->assertSame($imageUrl, $result['image_url']);
        $this->assertSame($url, $result['url']);
        $this->assertSame($availability, $result['availability']);
        $this->assertSame($searchEnabled, $result['enable_search']);
        $this->assertSame($updatedTime, $result['last_updated']);
    }

    private function getSut(
        ProductToArrayTransformerInterface $originalTransformer,
        TemplateRendererInterface $templateRenderer
    ): ProductToArrayTransformerInterface {
        return new ProductToArrayFieldsRenderingDecorator(
            originalTransformer: $originalTransformer,
            templateRenderer: $templateRenderer
        );
    }
}
