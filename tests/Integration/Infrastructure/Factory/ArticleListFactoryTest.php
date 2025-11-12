<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Tests\Integration\Infrastructure\Factory;

use FreshAdvance\ProductFeed\Infrastructure\Factory\ArticleListFactory;
use FreshAdvance\ProductFeed\Infrastructure\Factory\ArticleListFactoryInterface;
use OxidEsales\Eshop\Application\Model\ArticleList;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use PHPUnit\Framework\Attributes\Test;

final class ArticleListFactoryTest extends IntegrationTestCase
{
    #[Test]
    public function createsArticleList(): void
    {
        $factory = $this->getSut();

        $articleList = $factory->create();

        $this->assertInstanceOf(ArticleList::class, $articleList);
    }

    #[Test]
    public function createsNewInstanceEveryTime(): void
    {
        $factory = $this->getSut();

        $articleList1 = $factory->create();
        $articleList2 = $factory->create();

        $this->assertNotSame($articleList1, $articleList2);
    }

    private function getSut(): ArticleListFactoryInterface
    {
        return new ArticleListFactory();
    }
}
