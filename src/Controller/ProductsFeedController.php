<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Controller;

use FreshAdvance\ProductFeed\Service\ProductServiceInterface;
use FreshAdvance\ProductFeed\Transput\ResponseInterface;
use OxidEsales\Eshop\Application\Controller\FrontendController;

class ProductsFeedController extends FrontendController
{
    public function __construct(
        private readonly ProductServiceInterface $productService,
        private readonly ResponseInterface $response,
    ) {
        parent::__construct();
    }

    public function render(): string
    {
        $products = $this->productService->getProductsArrayForJson();

        $this->response->respondAsJson(['products' => $products]);

        return '';
    }
}
