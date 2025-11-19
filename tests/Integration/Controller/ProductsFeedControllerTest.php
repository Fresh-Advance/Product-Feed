<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Tests\Integration\Controller;

use FreshAdvance\ProductFeed\Controller\ProductsFeedController;
use FreshAdvance\ProductFeed\Service\ProductServiceInterface;
use FreshAdvance\ProductFeed\Transput\ResponseInterface;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use PHPUnit\Framework\Attributes\Test;

final class ProductsFeedControllerTest extends IntegrationTestCase
{
    #[Test]
    public function rendersProductsAsJson(): void
    {
        $productsArray = [
            ['id' => uniqid('product_')],
            ['id' => uniqid('product_')],
        ];

        $productServiceStub = $this->createConfiguredStub(
            ProductServiceInterface::class,
            ['getProductsArrayForJson' => $productsArray]
        );

        $responseSpy = $this->createMock(ResponseInterface::class);
        $responseSpy->expects($this->once())
            ->method('respondAsJson')
            ->with(['products' => $productsArray]);

        $controller = $this->getSut(
            productService: $productServiceStub,
            response: $responseSpy,
        );

        // should be not needed as we dont come here in the normal case, but to satisfy the method signature
        $this->assertSame('', $controller->render());
    }

    private function getSut(
        ?ProductServiceInterface $productService = null,
        ?ResponseInterface $response = null,
    ): ProductsFeedController {
        return new ProductsFeedController(
            productService: $productService ?? $this->get(ProductServiceInterface::class),
            response: $response ?? $this->get(ResponseInterface::class),
        );
    }
}
