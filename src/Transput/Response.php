<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Transput;

use OxidEsales\Eshop\Core\Utils;

class Response implements ResponseInterface
{
    public function __construct(
        private readonly Utils $utils
    ) {
    }

    /**
     * @param array<mixed> $data
     */
    public function respondAsJson(array $data): void
    {
        $this->utils->setHeader('Content-Type: application/json');
        $this->utils->showMessageAndExit((string)json_encode($data));
    }
}
