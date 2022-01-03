<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2022 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/license
 */

namespace Ergebnis\License\Test\Unit\Exception;

use Ergebnis\License\Exception\InvalidYear;
use Ergebnis\License\Test;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\License\Exception\InvalidYear
 */
final class InvalidYearTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testFromValueReturnsInvalidYear(): void
    {
        $value = self::faker()->sentence();

        $exception = InvalidYear::fromValue($value);

        $expected = \sprintf(
            'Value "%s" is not a valid year.',
            $value
        );

        self::assertSame($expected, $exception->getMessage());
    }
}
