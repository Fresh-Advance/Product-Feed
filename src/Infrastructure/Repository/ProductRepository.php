<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Infrastructure\Repository;

use FreshAdvance\ProductFeed\Infrastructure\Factory\ArticleListFactoryInterface;
use FreshAdvance\ProductFeed\Infrastructure\Factory\ProductFactoryInterface;
use OxidEsales\Eshop\Core\Language;
use OxidEsales\Eshop\Core\TableViewNameGenerator;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\ContextInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private readonly ContextInterface $context,
        private readonly Language $language,
        private readonly TableViewNameGenerator $tableViewNameGenerator,
        private readonly ArticleListFactoryInterface $articleListFactory,
        private readonly ProductFactoryInterface $productFactory
    ) {
    }

    public function getProducts(): array
    {
        $tableName = $this->getTableName();

        $query = "SELECT * FROM {$tableName}";

        $articleList = $this->articleListFactory->create();
        $articleList->selectString($query);

        $products = [];
        foreach ($articleList->getArray() as $article) {
            $products[] = $this->productFactory->createFromArticle($article);
        }

        return $products;
    }

    private function getTableName(): string
    {
        $shopId = $this->context->getCurrentShopId();
        $languageId = (int)$this->language->getBaseLanguage();

        return $this->tableViewNameGenerator->getViewName('oxarticles', $languageId, $shopId);
    }
}
