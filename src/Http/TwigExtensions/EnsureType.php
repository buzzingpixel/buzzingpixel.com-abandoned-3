<?php

declare(strict_types=1);

namespace App\Http\TwigExtensions;

use InvalidArgumentException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use function gettype;

class EnsureType extends AbstractExtension
{
    /**
     * @return TwigFunction[]
     */
    public function getFunctions() : array
    {
        return [
            new TwigFunction('ensureType', [$this, '__invoke']),
        ];
    }

    /**
     * @param mixed $var
     *
     * @throws InvalidArgumentException
     */
    public function __invoke($var, string $type) : void
    {
        $types = [
            'int' => 'integer',
            'integer' => 'integer',
            'bool' => 'boolean',
            'boolean' => 'boolean',
            'float' => 'double',
            'double' => 'double',
            'string' => 'string',
            'array' => 'array',
            'object' => 'object',
        ];

        if (isset($types[$type])) {
            if (gettype($var) === $types[$type]) {
                return;
            }

            throw new InvalidArgumentException();
        }

        if ($var instanceof $type) {
            return;
        }

        throw new InvalidArgumentException();
    }
}
