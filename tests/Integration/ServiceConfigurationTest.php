<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Tests\Integration;

use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\EshopCommunity\Tests\TestContainerFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class ServiceConfigurationTest extends IntegrationTestCase
{
    public static $cachedContainer;

    public static function setUpBeforeClass(): void
    {
        $container = (new TestContainerFactory())->create();
        $container->compile(true);
        self::$cachedContainer = $container;
    }

    public static function servicesProvider(): array
    {
        return [
            // Infrastructure
            [\FreshAdvance\ProductFeed\Infrastructure\Factory\ArticleListFactoryInterface::class],
            [\FreshAdvance\ProductFeed\Infrastructure\Factory\ProductFactoryInterface::class],
            [\FreshAdvance\ProductFeed\Infrastructure\Factory\ManufacturerModelFactoryInterface::class],
            [\FreshAdvance\ProductFeed\Infrastructure\Factory\CategoryModelFactoryInterface::class],
            [\FreshAdvance\ProductFeed\Infrastructure\Repository\ProductRepositoryInterface::class],
            [\FreshAdvance\ProductFeed\Infrastructure\Repository\ManufacturerRepositoryInterface::class],
            [\FreshAdvance\ProductFeed\Infrastructure\Repository\CategoryRepositoryInterface::class],

            // Service
            [\FreshAdvance\ProductFeed\Service\ProductServiceInterface::class],

            // Transformer
            [\FreshAdvance\ProductFeed\Transformer\ProductToArrayTransformerInterface::class],

            // Transput
            [\FreshAdvance\ProductFeed\Transput\ResponseInterface::class],
        ];
    }

    #[Test]
    #[DataProvider('servicesProvider')]
    public function serviceIsAvailable(string $serviceName): void
    {
        $service = self::$cachedContainer->get($serviceName);
        $this->assertInstanceOf($serviceName, $service);
    }
}
