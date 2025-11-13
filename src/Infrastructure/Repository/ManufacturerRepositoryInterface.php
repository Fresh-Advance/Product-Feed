<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Infrastructure\Repository;

interface ManufacturerRepositoryInterface
{
    public function getManufacturerTitleById(string $manufacturerId): string;
}
