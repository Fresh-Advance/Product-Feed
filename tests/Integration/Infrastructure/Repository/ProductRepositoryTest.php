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
        $articleIds = [
            uniqid('test_article_'),
            uniqid('test_article_'),
            uniqid('test_article_'),
        ];

        foreach ($articleIds as $articleId) {
            $article = oxNew(Article::class);
            $article->setId($articleId);
            $article->assign(['oxactive' => 1]);
            $article->save();
        }

        $productFactorySpy = $this->createMock(ProductFactoryInterface::class);
        $productFactorySpy->expects($this->exactly(3))
            ->method('createFromArticle')
            ->willReturnCallback(fn($article) => $this->createConfiguredStub(
                ProductInterface::class,
                ['getProductId' => $article->getId()]
            ));

        $repository = $this->getSut(
            productFactory: $productFactorySpy
        );
        $products = $repository->getProducts();

        $this->assertGreaterThanOrEqual(3, count($products));

        $productIds = array_map(fn($product) => $product->getProductId(), $products);
        foreach ($articleIds as $articleId) {
            $this->assertContains($articleId, $productIds);
        }
    }

    private function getSut(
        ?ArticleListFactoryInterface $articleListFactory = null,
        ?ProductFactoryInterface $productFactory = null
    ): ProductRepositoryInterface {
        return new ProductRepository(
            context: $this->get(ContextInterface::class),
            language: $this->get(Language::class),
            shopAdapter: $this->get(ShopAdapterInterface::class),
            articleListFactory: $articleListFactory ?? $this->get(ArticleListFactoryInterface::class),
            productFactory: $productFactory ?? $this->get(ProductFactoryInterface::class)
        );
    }
}
