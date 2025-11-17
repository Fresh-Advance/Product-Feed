<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Infrastructure\Factory;

use FreshAdvance\ProductFeed\DTO\ProductInterface;
use OxidEsales\Eshop\Application\Model\Article;

interface ProductFactoryInterface
{
    public function createFromArticle(Article $article): ProductInterface;
}
