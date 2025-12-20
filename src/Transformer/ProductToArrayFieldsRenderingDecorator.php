<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Transformer;

use FreshAdvance\ProductFeed\DTO\ProductInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Templating\TemplateRendererInterface;

class ProductToArrayFieldsRenderingDecorator implements ProductToArrayTransformerInterface
{
    public function __construct(
        private readonly ProductToArrayTransformerInterface $originalTransformer,
        private readonly TemplateRendererInterface $templateRenderer
    ) {
    }

    public function toArray(ProductInterface $product): array
    {
        $result = $this->originalTransformer->toArray($product);

        $result['long_description'] = $this->templateRenderer->renderFragment(
            $result['long_description'],
            $result['id'] . '_long_description',
            []
        );

        return $result;
    }
}
