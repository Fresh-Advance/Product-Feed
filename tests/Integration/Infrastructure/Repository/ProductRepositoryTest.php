<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Tests\Integration\Infrastructure\Repository;

use FreshAdvance\ProductFeed\DTO\ProductInterface;
use FreshAdvance\ProductFeed\Infrastructure\Factory\ArticleListFactoryInterface;
use FreshAdvance\ProductFeed\Infrastructure\Factory\ProductFactoryInterface;
use FreshAdvance\ProductFeed\Infrastructure\Repository\ProductRepository;
use FreshAdvance\ProductFeed\Infrastructure\Repository\ProductRepositoryInterface;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\ArticleList;
use OxidEsales\Eshop\Core\Language;
use OxidEsales\EshopCommunity\Internal\Transition\Adapter\ShopAdapterInterface;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\ContextInterface;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use PHPUnit\Framework\Attributes\Test;

final class ProductRepositoryTest extends IntegrationTestCase
{
    #[Test]
    public function usesFactoryToCreateProducts(): void
    {
        $article1 = $this->createConfiguredStub(Article::class, ['getId' => 'article_1']);
        $article2 = $this->createConfiguredStub(Article::class, ['getId' => 'article_2']);

        $articleListSpy = $this->createMock(ArticleList::class);
        $articleListSpy->expects($this->once())
            ->method('selectString')
            ->with($this->stringContains('SELECT * FROM oxv_oxarticles'));
        $articleListSpy->method('getArray')->willReturn([$article1, $article2]);

        $articleListFactoryMock = $this->createConfiguredStub(
            ArticleListFactoryInterface::class,
            ['create' => $articleListSpy]
        );

        $productFactorySpy = $this->createMock(ProductFactoryInterface::class);
        $productFactorySpy->expects($this->exactly(2))
            ->method('createFromArticle')
            ->willReturnCallback(fn($article) => $this->createConfiguredStub(
                ProductInterface::class,
                ['getProductId' => $article->getId()]
            ));

        $repository = $this->getSut($articleListFactoryMock, $productFactorySpy);
        $products = $repository->getProducts();

        $this->assertCount(2, $products);
        $this->assertSame('article_1', $products[0]->getProductId());
        $this->assertSame('article_2', $products[1]->getProductId());
    }

    private function getSut(
        ?ArticleListFactoryInterface $articleListFactory = null,
        ?ProductFactoryInterface $productFactory = null
    ): ProductRepositoryInterface {
        return new ProductRepository(
            $this->get(ContextInterface::class),
            $this->get(Language::class),
            $this->get(ShopAdapterInterface::class),
            $articleListFactory ?? $this->get(ArticleListFactoryInterface::class),
            $productFactory ?? $this->get(ProductFactoryInterface::class)
        );
    }
}
