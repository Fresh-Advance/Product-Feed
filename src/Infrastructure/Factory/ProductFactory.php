<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Infrastructure\Factory;

use FreshAdvance\ProductFeed\DTO\Product;
use FreshAdvance\ProductFeed\DTO\ProductInterface;
use FreshAdvance\ProductFeed\Infrastructure\Repository\CategoryRepositoryInterface;
use FreshAdvance\ProductFeed\Infrastructure\Repository\ManufacturerRepositoryInterface;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\Config;

class ProductFactory implements ProductFactoryInterface
{
    public function __construct(
        private readonly Config $config,
        private readonly ManufacturerRepositoryInterface $manufacturerRepository,
        private readonly CategoryRepositoryInterface $categoryRepository,
    ) {
    }

    public function createFromArticle(Article $article): ProductInterface
    {
        /** @var \OxidEsales\Eshop\Core\Field|null $longDescField */
        $longDescField = $article->getLongDescription();

        return new Product(
            productId: (string)$article->getId(),
            name: (string)$article->getFieldData('oxtitle'),
            brand: $this->getManufacturerTitle($article),
            shortDescription: (string)$article->getFieldData('oxshortdesc'),
            longDescription: (string)$longDescField,
            price: $this->getFormattedPrice($article),
            weight: $this->getFormattedWeight($article),
            category: $this->getCategoryTitle($article),
            imageUrl: $article->getThumbnailUrl(),
            url: $article->getLink(),
            availability: $article->getFieldData('oxstock') > 0 ? 'in_stock' : 'out_of_stock',
            searchEnabled: (bool)$article->getFieldData('oxissearch'),
            updatedTime: (string)$article->getFieldData('oxtimestamp')
        );
    }

    private function getFormattedPrice(Article $article): string
    {
        /** @var \OxidEsales\Eshop\Core\Price|null $price */
        $price = $article->getPrice();
        $currency = $this->config->getActShopCurrencyObject();
        $formattedPrice = $price ? $price->getBruttoPrice() . ' ' . $currency->name : '';

        return $formattedPrice;
    }

    private function getFormattedWeight(Article $article): string
    {
        return $article->getFieldData('oxweight') ? $article->getFieldData('oxweight') . ' kg.' : '';
    }

    private function getManufacturerTitle(Article $article): string
    {
        $result = '';

        if ($manufacturerId = $article->getFieldData('oxmanufacturerid')) {
            $result = $this->manufacturerRepository->getManufacturerTitleById($manufacturerId);
        }

        return $result;
    }

    private function getCategoryTitle(Article $article): string
    {
        $result = '';
        $categories = $article->getCategoryIds();

        if ($categoryId = reset($categories)) {
            $result = $this->categoryRepository->getCategoryTitleById($categoryId);
        }

        return $result;
    }
}
