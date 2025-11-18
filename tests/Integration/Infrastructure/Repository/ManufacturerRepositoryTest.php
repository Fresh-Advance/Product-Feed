<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Tests\Integration\Infrastructure\Repository;

use FreshAdvance\ProductFeed\Infrastructure\Factory\ManufacturerModelFactoryInterface;
use FreshAdvance\ProductFeed\Infrastructure\Repository\ManufacturerRepository;
use FreshAdvance\ProductFeed\Infrastructure\Repository\ManufacturerRepositoryInterface;
use OxidEsales\Eshop\Application\Model\Manufacturer;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use PHPUnit\Framework\Attributes\Test;

final class ManufacturerRepositoryTest extends IntegrationTestCase
{
    #[Test]
    public function getsManufacturerTitleById(): void
    {
        $manufacturerId = uniqid('manufacturer_');
        $manufacturerTitle = uniqid('title_');

        $manufacturerSpy = $this->createMock(Manufacturer::class);
        $manufacturerSpy->expects($this->once())
            ->method('load')
            ->with($manufacturerId);
        $manufacturerSpy->method('getFieldData')
            ->with('oxtitle')
            ->willReturn($manufacturerTitle);

        $factoryStub = $this->createConfiguredStub(
            ManufacturerModelFactoryInterface::class,
            ['create' => $manufacturerSpy]
        );

        $repository = $this->getSut(
            manufacturerModelFactory: $factoryStub
        );
        $title = $repository->getManufacturerTitleById($manufacturerId);

        $this->assertSame($manufacturerTitle, $title);
    }

    private function getSut(
        ?ManufacturerModelFactoryInterface $manufacturerModelFactory = null
    ): ManufacturerRepositoryInterface {
        return new ManufacturerRepository(
            manufacturerFactory: $manufacturerModelFactory ?? $this->get(ManufacturerModelFactoryInterface::class)
        );
    }
}
