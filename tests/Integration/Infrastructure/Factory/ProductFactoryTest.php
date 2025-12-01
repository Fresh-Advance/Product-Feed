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
use FreshAdvance\ProductFeed\Infrastructure\Repository\ManufacturerRepositoryInterface;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Field;
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

        $manufacturerRepositorySpy = $this->createMock(ManufacturerRepositoryInterface::class);
        $manufacturerRepositorySpy->expects($this->once())
            ->method('getManufacturerTitleById')
            ->with($brandId = uniqid('brandId_'))
            ->willReturn($brandTitle = uniqid('brandTitle_'));

        $articleStub = $this->createStub(Article::class);
        $articleStub->method('getId')->willReturn($exampleId = uniqid('id'));
        $articleStub->method('getFieldData')->willReturnMap([
            ['oxtitle', $exampleTitle = uniqid('title')],
            ['oxmanufacturerid', $brandId],
            ['oxshortdesc', $shortDescription = uniqid('shortDesc')],
            ['oxweight', $weight = (string)round(rand(0, 100) / 10, 3)],
            ['oxstock', rand(1, 100)],
            ['oxissearch', $searchable = rand(0, 1)],
            ['oxtimestamp', $time = date("Y-m-d H:i:s", time())],
        ]);

        $articleStub->method('getLongDescription')->willReturn(
            new Field($longDescription = uniqid('longDesc'))
        );

        $articleStub->method('getThumbnailUrl')->willReturn($picture = uniqid('picture'));
        $articleStub->method('getLink')->willReturn($link = uniqid('link'));
        $articleStub->method('getCategoryIds')->willReturn(
            [$categoryId = uniqid('categoryId1'), uniqid('categoryId2')]
        );

        $priceStub = $this->createConfiguredStub(\OxidEsales\Eshop\Core\Price::class, [
            'getBruttoPrice' => $bruttoPrice = rand(1, 1000) / 100
        ]);

        $articleStub->method('getPrice')->willReturn($priceStub);

        $factory = $this->getSut(
            config: $configMock,
            manufacturerRepository: $manufacturerRepositorySpy,
        );
        $product = $factory->createFromArticle($articleStub);

        $this->assertInstanceOf(ProductInterface::class, $product);
        $this->assertSame($exampleId, $product->getProductId());
        $this->assertSame($exampleTitle, $product->getName());
        $this->assertSame($brandTitle, $product->getBrand());
        $this->assertSame($shortDescription, $product->getShortDescription());
        $this->assertSame($longDescription, $product->getLongDescription());
        $this->assertSame($bruttoPrice . ' EUR', $product->getPrice());
        $this->assertSame($weight . ' kg.', $product->getWeight());
        $this->assertSame($categoryId, $product->getCategory());
        $this->assertSame($picture, $product->getImageUrl());
        $this->assertSame($link, $product->getUrl());
        $this->assertSame(ProductInterface::AVAILABILITY_IN_STOCK, $product->getAvailability());
        $this->assertSame((bool)$searchable, $product->isSearchEnabled());
        $this->assertSame($time, $product->getUpdatedTime());
    }

    #[Test]
    public function handlesAllEdgeCases(): void
    {
        $currencyStub = new \stdClass();
        $currencyStub->name = uniqid('CURRENCY');

        $configMock = $this->createStub(Config::class);
        $configMock->method('getActShopCurrencyObject')->willReturn($currencyStub);

        $articleStub = $this->createStub(Article::class);
        $articleStub->method('getThumbnailUrl')->willReturn('');
        $articleStub->method('getLink')->willReturn('');
        $articleStub->method('getCategoryIds')->willReturn([]);

        $articleStub->method('getPrice')->willReturn(null);

        $factory = $this->getSut(config: $configMock);
        $product = $factory->createFromArticle($articleStub);

        $this->assertInstanceOf(ProductInterface::class, $product);
        $this->assertSame('', $product->getProductId());
        $this->assertSame('', $product->getName());
        $this->assertSame('', $product->getBrand());
        $this->assertSame('', $product->getShortDescription());
        $this->assertSame('', $product->getLongDescription());
        $this->assertSame('', $product->getPrice());
        $this->assertSame('', $product->getWeight());
        $this->assertSame('', $product->getCategory());
        $this->assertSame('', $product->getImageUrl());
        $this->assertSame('', $product->getUrl());
        $this->assertSame(ProductInterface::AVAILABILITY_OUT_OF_STOCK, $product->getAvailability());
        $this->assertFalse($product->isSearchEnabled());
        $this->assertSame('', $product->getUpdatedTime());
    }

    #[Test]
    public function doesntTriggerManufacturerLoadingIfNoManufacturerId(): void
    {
        $manufacturerRepositorySpy = $this->createMock(ManufacturerRepositoryInterface::class);
        $manufacturerRepositorySpy->expects($this->never())->method('getManufacturerTitleById');

        $articleStub = $this->createStub(Article::class);

        $articleStub->method('getThumbnailUrl')->willReturn(uniqid('picture_'));
        $articleStub->method('getLink')->willReturn(uniqid('link_'));
        $articleStub->method('getCategoryIds')->willReturn([]);

        $articleStub->method('getFieldData')->willReturnMap([
            ['oxmanufacturerid', ''],
        ]);

        $factory = $this->getSut(
            manufacturerRepository: $manufacturerRepositorySpy,
        );

        $factory->createFromArticle($articleStub);
    }

    private function getSut(
        ?Config $config = null,
        ?ManufacturerRepositoryInterface $manufacturerRepository = null,
    ): ProductFactoryInterface {
        return new ProductFactory(
            config: $config ?? $this->get(Config::class),
            manufacturerRepository: $manufacturerRepository ?? $this->get(ManufacturerRepositoryInterface::class),
        );
    }
}
