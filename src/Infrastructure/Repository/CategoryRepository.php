<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Infrastructure\Repository;

use FreshAdvance\ProductFeed\Infrastructure\Exception\CategoryNotFound;
use FreshAdvance\ProductFeed\Infrastructure\Factory\CategoryModelFactoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(
        private readonly CategoryModelFactoryInterface $categoryFactory
    ) {
    }

    public function getCategoryTitleById(string $categoryId): string
    {
        $category = $this->categoryFactory->create();
        if (!$category->load($categoryId)) {
            throw new CategoryNotFound($categoryId);
        }

        return (string)$category->getFieldData('oxtitle');
    }
}
