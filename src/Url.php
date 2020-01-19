<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/license
 */

namespace Ergebnis\License;

final class Url
{
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @param string $value
     *
     * @throws Exception\InvalidUrl
     *
     * @return self
     */
    public static function fromString(string $value): self
    {
        $trimmed = \trim($value);

        if (false === \filter_var($trimmed, \FILTER_VALIDATE_URL)) {
            throw Exception\InvalidUrl::fromValue($value);
        }

        return new self($trimmed);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
