<?php

declare(strict_types=1);

namespace Golossus\TestFixtureLoading;

use Exception;

class CycleDependencyException extends Exception
{
    public static function create($start, $end)
    {
        $message = sprintf('Dependency cycle detected starting from %s and ending on %s', $start, $end);

        return new static($message);
    }
}
