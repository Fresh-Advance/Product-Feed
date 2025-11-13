<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Tests\Integration\Infrastructure\Factory;

use FreshAdvance\ProductFeed\Infrastructure\Factory\ManufacturerModelFactory;
use FreshAdvance\ProductFeed\Infrastructure\Factory\ManufacturerModelFactoryInterface;
use OxidEsales\Eshop\Application\Model\Manufacturer;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use PHPUnit\Framework\Attributes\Test;

final class ManufacturerModelFactoryTest extends IntegrationTestCase
{
    #[Test]
    public function createsManufacturerObject(): void
    {
        $factory = $this->getSut();

        $manufacturer = $factory->create();

        $this->assertInstanceOf(Manufacturer::class, $manufacturer);
    }

    #[Test]
    public function createsNewInstanceEveryTime(): void
    {
        $factory = $this->getSut();

        $manufacturer1 = $factory->create();
        $manufacturer2 = $factory->create();

        $this->assertNotSame($manufacturer1, $manufacturer2);
    }

    private function getSut(): ManufacturerModelFactoryInterface
    {
        return new ManufacturerModelFactory();
    }
}
