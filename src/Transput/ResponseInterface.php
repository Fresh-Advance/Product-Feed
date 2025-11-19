<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

namespace FreshAdvance\ProductFeed\Transput;

interface ResponseInterface
{
    /**
     * @param array<mixed> $data
     */
    public function respondAsJson(array $data): void;
}
