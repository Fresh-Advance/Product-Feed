<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Service;

use FreshAdvance\ProductFeed\Infrastructure\Repository\ProductRepositoryInterface;
use FreshAdvance\ProductFeed\Transformer\ProductToArrayTransformerInterface;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private readonly ProductRepositoryInterface $repository,
        private readonly ProductToArrayTransformerInterface $transformer
    ) {
    }

    public function getProductsArrayForJson(): array
    {
        $products = $this->repository->getProducts();

        $result = [];
        foreach ($products as $product) {
            $result[] = $this->transformer->toArray($product);
        }

        return $result;
    }
}
