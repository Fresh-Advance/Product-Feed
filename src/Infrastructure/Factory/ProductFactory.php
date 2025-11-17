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
        $price = $article->getPrice();
        $currency = $this->config->getActShopCurrencyObject();
        $formattedPrice = $price->getBruttoPrice() . ' ' . $currency->name;

        return new Product(
            productId: $article->getId(),
            name: $article->getFieldData('oxtitle'),
            brand: $article->getFieldData('oxmanufacturerid'),
            shortDescription: $article->getFieldData('oxshortdesc'),
            longDescription: $article->getFieldData('oxlongdesc'),
            price: $formattedPrice,
            weight: $article->getFieldData('oxweight'),
            category: $article->getCategoryIds()[0] ?? '',
            imageUrl: $article->getThumbnailUrl(),
            url: $article->getLink(),
            availability: $article->getFieldData('oxstock') > 0 ? 'in_stock' : 'out_of_stock',
            searchEnabled: (bool)$article->getFieldData('oxsearchable'),
            updatedTime: $article->getFieldData('oxtimestamp')
        );
    }
}
