<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Tests\Integration;

use OxidEsales\EshopCommunity\Internal\Container\ContainerBuilderFactory;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class ServiceConfigurationTest extends IntegrationTestCase
{
    private static $cachedContainer;
    private static $decorations = [];

    public static function setUpBeforeClass(): void
    {
        $containerBuilder = (new ContainerBuilderFactory())->create();
        $container = $containerBuilder->getContainer();
        foreach ($container->getDefinitions() as $id => $definition) {
            $definition->setPublic(true);
            if ($decorated = $definition->getDecoratedService()) {
                self::$decorations[reset($decorated)][] = $id;
            }
        }
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

    public static function serviceDecorationProvider(): \Generator
    {
        yield [
            'serviceName' => \FreshAdvance\ProductFeed\Transformer\ProductToArrayTransformerInterface::class,
            'expectedDecorations' => [
                \FreshAdvance\ProductFeed\Transformer\ProductToArrayFieldsRenderingDecorator::class,
            ],
        ];
    }

    #[Test]
    #[DataProvider('serviceDecorationProvider')]
    public function serviceIsDecorated(string $serviceName, array $expectedDecorations): void
    {
        $decorations = self::$decorations[$serviceName] ?? [];
        foreach ($expectedDecorations as $oneExpectedDecoration) {
            $this->assertContains($oneExpectedDecoration, $decorations);
        }
    }
}
