<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Tests\Integration\Infrastructure\Factory;

use FreshAdvance\ProductFeed\Infrastructure\Factory\CategoryModelFactory;
use FreshAdvance\ProductFeed\Infrastructure\Factory\CategoryModelFactoryInterface;
use OxidEsales\Eshop\Application\Model\Category;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use PHPUnit\Framework\Attributes\Test;

final class CategoryModelFactoryTest extends IntegrationTestCase
{
    #[Test]
    public function createsCategoryObject(): void
    {
        $factory = $this->getSut();

        $category = $factory->create();

        $this->assertInstanceOf(Category::class, $category);
    }

    #[Test]
    public function createsNewInstanceEveryTime(): void
    {
        $factory = $this->getSut();

        $category1 = $factory->create();
        $category2 = $factory->create();

        $this->assertNotSame($category1, $category2);
    }

    private function getSut(): CategoryModelFactoryInterface
    {
        return new CategoryModelFactory();
    }
}
