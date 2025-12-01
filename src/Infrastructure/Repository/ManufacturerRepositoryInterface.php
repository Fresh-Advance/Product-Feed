<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Infrastructure\Repository;

use FreshAdvance\ProductFeed\Infrastructure\Exception\ManufacturerNotFound;

interface ManufacturerRepositoryInterface
{
    /**
     * @throws ManufacturerNotFound
     */
    public function getManufacturerTitleById(string $manufacturerId): string;
}
