<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Tests\Unit\Service;

use FreshAdvance\ProductFeed\DTO\Product;
use FreshAdvance\ProductFeed\Infrastructure\Repository\ProductRepositoryInterface;
use FreshAdvance\ProductFeed\Service\ProductService;
use FreshAdvance\ProductFeed\Service\ProductServiceInterface;
use FreshAdvance\ProductFeed\Transformer\ProductToArrayTransformerInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ProductServiceTest extends TestCase
{
    #[Test]
    public function transformsProductsToArrayForJson(): void
    {
        $product1 = new Product(productId: uniqid('product_'));
        $product2 = new Product(productId: uniqid('product_'));
        $product3 = new Product(productId: uniqid('product_'));

        $repositoryStub = $this->createConfiguredStub(
            ProductRepositoryInterface::class,
            ['getProducts' => [$product1, $product2, $product3]]
        );

        $transformerSpy = $this->createMock(ProductToArrayTransformerInterface::class);
        $transformerSpy->expects($this->exactly(3))
            ->method('toArray')
            ->willReturnCallback(fn($product) => ['id' => $product->getProductId()]);

        $service = $this->getSut(
            repository: $repositoryStub,
            transformer: $transformerSpy
        );
        $result = $service->getProductsArrayForJson();

        $this->assertCount(3, $result);
        $this->assertSame($product1->getProductId(), $result[0]['id']);
        $this->assertSame($product2->getProductId(), $result[1]['id']);
        $this->assertSame($product3->getProductId(), $result[2]['id']);
    }

    #[Test]
    public function returnsEmptyArrayWhenNoProducts(): void
    {
        $repositoryStub = $this->createConfiguredStub(
            ProductRepositoryInterface::class,
            ['getProducts' => []]
        );

        $transformerSpy = $this->createMock(ProductToArrayTransformerInterface::class);
        $transformerSpy->expects($this->never())
            ->method('toArray');

        $service = $this->getSut(
            repository: $repositoryStub,
            transformer: $transformerSpy
        );
        $result = $service->getProductsArrayForJson();

        $this->assertSame([], $result);
    }

    private function getSut(
        ?ProductRepositoryInterface $repository = null,
        ?ProductToArrayTransformerInterface $transformer = null
    ): ProductServiceInterface {
        return new ProductService(
            repository: $repository ?? $this->createStub(ProductRepositoryInterface::class),
            transformer: $transformer ?? $this->createStub(ProductToArrayTransformerInterface::class)
        );
    }
}
