<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Tests\Integration\Infrastructure\Factory;

use FreshAdvance\ProductFeed\DTO\ProductInterface;
use FreshAdvance\ProductFeed\Infrastructure\Factory\ProductFactory;
use FreshAdvance\ProductFeed\Infrastructure\Factory\ProductFactoryInterface;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use PHPUnit\Framework\Attributes\Test;

final class ProductFactoryTest extends IntegrationTestCase
{
    #[Test]
    public function createsProductFromArticle(): void
    {
        $currencyStub = new \stdClass();
        $currencyStub->name = 'EUR';

        $configMock = $this->createStub(Config::class);
        $configMock->method('getActShopCurrencyObject')->willReturn($currencyStub);

        $articleStub = $this->createStub(Article::class);
        $articleStub->method('getId')->willReturn('test_article_id');
        $articleStub->method('getFieldData')->willReturnMap([
            ['oxtitle', 'Test Product'],
            ['oxmanufacturerid', 'test_brand'],
            ['oxshortdesc', 'Short desc'],
            ['oxlongdesc', 'Long desc'],
            ['oxweight', '1.5'],
            ['oxstock', 10],
            ['oxsearchable', 1],
            ['oxtimestamp', '2025-01-01 12:00:00'],
        ]);
        $articleStub->method('getThumbnailUrl')->willReturn('http://example.com/image.jpg');
        $articleStub->method('getLink')->willReturn('http://example.com/product');
        $articleStub->method('getCategoryIds')->willReturn(['cat1', 'cat2']);

        $priceStub = $this->createConfiguredStub(\OxidEsales\Eshop\Core\Price::class, [
            'getBruttoPrice' => 99.99
        ]);

        $articleStub->method('getPrice')->willReturn($priceStub);

        $factory = $this->getSut($configMock);
        $product = $factory->createFromArticle($articleStub);

        $this->assertInstanceOf(ProductInterface::class, $product);
        $this->assertSame('test_article_id', $product->getProductId());
        $this->assertSame('Test Product', $product->getName());
        $this->assertSame('test_brand', $product->getBrand());
        $this->assertSame('Short desc', $product->getShortDescription());
        $this->assertSame('Long desc', $product->getLongDescription());
        $this->assertSame('99.99 EUR', $product->getPrice());
        $this->assertSame('1.5', $product->getWeight());
        $this->assertSame('cat1', $product->getCategory());
        $this->assertSame('http://example.com/image.jpg', $product->getImageUrl());
        $this->assertSame('http://example.com/product', $product->getUrl());
        $this->assertSame('in_stock', $product->getAvailability());
        $this->assertTrue($product->isSearchEnabled());
        $this->assertSame('2025-01-01 12:00:00', $product->getUpdatedTime());
    }

    private function getSut(?Config $config = null): ProductFactoryInterface
    {
        return new ProductFactory(
            $config ?? $this->get(Config::class)
        );
    }
}
