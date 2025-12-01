<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Infrastructure\Factory;

use FreshAdvance\ProductFeed\DTO\Product;
use FreshAdvance\ProductFeed\DTO\ProductInterface;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\Config;

class ProductFactory implements ProductFactoryInterface
{
    public function __construct(
        private readonly Config $config
    ) {
    }

    public function createFromArticle(Article $article): ProductInterface
    {
        /** @var \OxidEsales\Eshop\Core\Field|null $longDescField */
        $longDescField = $article->getLongDescription();

        return new Product(
            productId: (string)$article->getId(),
            name: (string)$article->getFieldData('oxtitle'),
            brand: (string)$article->getFieldData('oxmanufacturerid'),
            shortDescription: (string)$article->getFieldData('oxshortdesc'),
            longDescription: (string)$longDescField,
            price: $this->getFormattedPrice($article),
            weight: $this->getFormattedWeight($article),
            category: $article->getCategoryIds()[0] ?? '',
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
}
