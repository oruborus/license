<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2023 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/license
 */

namespace Ergebnis\License\Test\Unit;

use Ergebnis\DataProvider;
use Ergebnis\License\Exception;
use Ergebnis\License\File;
use Ergebnis\License\Holder;
use Ergebnis\License\Range;
use Ergebnis\License\Template;
use Ergebnis\License\Test;
use Ergebnis\License\Year;
use PHPUnit\Framework;
use Symfony\Component\Filesystem;

#[Framework\Attributes\CoversClass(File::class)]
#[Framework\Attributes\UsesClass(Exception\InvalidFile::class)]
#[Framework\Attributes\UsesClass(Holder::class)]
#[Framework\Attributes\UsesClass(Range::class)]
#[Framework\Attributes\UsesClass(Template::class)]
#[Framework\Attributes\UsesClass(Year::class)]
final class FileTest extends Framework\TestCase
{
    use Test\Util\Helper;

    protected function setUp(): void
    {
        $filesystem = new Filesystem\Filesystem();

        $filesystem->mkdir(self::temporaryDirectory());
    }

    protected function tearDown(): void
    {
        $filesystem = new Filesystem\Filesystem();

        $filesystem->remove(self::temporaryDirectory());
    }

    #[Framework\Attributes\DataProviderExternal(DataProvider\StringProvider::class, 'blank')]
    #[Framework\Attributes\DataProviderExternal(DataProvider\StringProvider::class, 'empty')]
    public function testCreateRejectsBlankOrEmptyName(string $name): void
    {
        $faker = self::faker();

        $template = Template::fromFile(__DIR__ . '/../../resource/license/MIT.txt');
        $range = Range::since(
            Year::fromString($faker->year()),
            new \DateTimeZone($faker->timezone()),
        );
        $holder = Holder::fromString($faker->name());

        $this->expectException(Exception\InvalidFile::class);

        File::create(
            $name,
            $template,
            $range,
            $holder,
        );
    }

    public function testNameReturnsName(): void
    {
        $faker = self::faker();

        $name = \sprintf(
            '%s/%s.txt',
            __DIR__,
            $faker->slug(),
        );
        $template = Template::fromFile(__DIR__ . '/../../resource/license/MIT.txt');
        $range = Range::since(
            Year::fromString($faker->year()),
            new \DateTimeZone($faker->timezone()),
        );
        $holder = Holder::fromString($faker->name());

        $file = File::create(
            $name,
            $template,
            $range,
            $holder,
        );

        self::assertSame($name, $file->name());
    }

    public function testSaveSavesFile(): void
    {
        $faker = self::faker();

        $name = \sprintf(
            '%s/%s.txt',
            self::temporaryDirectory(),
            $faker->slug(),
        );
        $template = Template::fromFile(__DIR__ . '/../../resource/license/MIT.txt');
        $range = Range::since(
            Year::fromString($faker->year()),
            new \DateTimeZone($faker->timezone()),
        );
        $holder = Holder::fromString($faker->name());

        $file = File::create(
            $name,
            $template,
            $range,
            $holder,
        );

        $file->save();

        self::assertFileExists($file->name());

        $expected = $template->toString([
            '<holder>' => $holder->toString(),
            '<range>' => $range->toString(),
        ]);

        self::assertSame($expected, \file_get_contents($file->name()));
    }
}
