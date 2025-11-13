<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Infrastructure\Repository;

use FreshAdvance\ProductFeed\Infrastructure\Factory\ManufacturerModelFactoryInterface;

class ManufacturerRepository implements ManufacturerRepositoryInterface
{
    public function __construct(
        private readonly ManufacturerModelFactoryInterface $manufacturerFactory
    ) {
    }

    public function getManufacturerTitleById(string $manufacturerId): string
    {
        $manufacturer = $this->manufacturerFactory->create();
        $manufacturer->load($manufacturerId);

        return (string)$manufacturer->getFieldData('oxtitle');
    }
}
