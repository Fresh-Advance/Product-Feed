<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\ProductFeed\Tests\Unit\Transput;

use FreshAdvance\ProductFeed\Transput\Response;
use FreshAdvance\ProductFeed\Transput\ResponseInterface;
use OxidEsales\Eshop\Core\Utils;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ResponseTest extends TestCase
{
    #[Test]
    #[DataProvider('jsonDataProvider')]
    public function respondsWithJsonData(array $testData): void
    {
        $expectedJson = json_encode($testData);

        $utilsSpy = $this->createMock(Utils::class);
        $utilsSpy->expects($this->once())
            ->method('setHeader')
            ->with('Content-Type: application/json');

        $utilsSpy->expects($this->once())
            ->method('showMessageAndExit')
            ->with($expectedJson);

        $sut = $this->getSut(
            utils: $utilsSpy,
        );

        $sut->respondAsJson($testData);
    }

    public static function jsonDataProvider(): array
    {
        return [
            'empty array' => [
                []
            ],
            'simple nested array' => [
                [
                    'key1' => uniqid('value1_'),
                    'key2' => uniqid('value2_'),
                    'nested' => [
                        'key3' => uniqid('value3_')
                    ]
                ]
            ],
        ];
    }

    private function getSut(
        ?Utils $utils = null
    ): ResponseInterface {
        return new Response(
            utils: $utils ?? $this->createStub(Utils::class)
        );
    }
}
