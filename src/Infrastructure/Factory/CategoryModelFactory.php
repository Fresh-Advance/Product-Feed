<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Infrastructure\Factory;

use OxidEsales\Eshop\Application\Model\Category;

class CategoryModelFactory implements CategoryModelFactoryInterface
{
    public function create(): Category
    {
        return oxNew(Category::class);
    }
}
