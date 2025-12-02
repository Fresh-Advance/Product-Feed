<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Tests\Integration\Infrastructure\Repository;

use FreshAdvance\ProductFeed\Infrastructure\Exception\CategoryNotFound;
use FreshAdvance\ProductFeed\Infrastructure\Factory\CategoryModelFactoryInterface;
use FreshAdvance\ProductFeed\Infrastructure\Repository\CategoryRepository;
use FreshAdvance\ProductFeed\Infrastructure\Repository\CategoryRepositoryInterface;
use OxidEsales\Eshop\Application\Model\Category;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use PHPUnit\Framework\Attributes\Test;

final class CategoryRepositoryTest extends IntegrationTestCase
{
    #[Test]
    public function getCategoryTitleById(): void
    {
        $categoryId = uniqid('category_');
        $categoryTitle = uniqid('title_');

        $categorySpy = $this->createMock(Category::class);
        $categorySpy->expects($this->once())
            ->method('load')
            ->with($categoryId)
            ->willReturn(true);
        $categorySpy->method('getFieldData')
            ->with('oxtitle')
            ->willReturn($categoryTitle);

        $factoryStub = $this->createConfiguredStub(
            CategoryModelFactoryInterface::class,
            ['create' => $categorySpy]
        );

        $repository = $this->getSut(
            categoryModelFactory: $factoryStub
        );
        $title = $repository->getCategoryTitleById($categoryId);

        $this->assertSame($categoryTitle, $title);
    }

    #[Test]
    public function throwsExceptionWhenCategoryNotFound(): void
    {
        $nonExistentId = uniqid('nonexistent_');

        $categorySpy = $this->createMock(Category::class);
        $categorySpy->expects($this->never())->method('getFieldData');
        $categorySpy->method('load')
            ->with($nonExistentId)
            ->willReturn(false);

        $factoryStub = $this->createConfiguredStub(
            CategoryModelFactoryInterface::class,
            ['create' => $categorySpy]
        );

        $repository = $this->getSut(
            categoryModelFactory: $factoryStub
        );

        $this->expectException(CategoryNotFound::class);
        $repository->getCategoryTitleById($nonExistentId);
    }

    private function getSut(
        ?CategoryModelFactoryInterface $categoryModelFactory = null
    ): CategoryRepositoryInterface {
        $categoryModelFactory ??= $this->createStub(CategoryModelFactoryInterface::class);

        return new CategoryRepository(
            categoryFactory: $categoryModelFactory,
        );
    }
}
