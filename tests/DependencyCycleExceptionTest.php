<?php

declare(strict_types=1);

namespace Golossus\TestFixtureLoading\Tests;

use Golossus\TestFixtureLoading\CycleDependencyException;
use PHPUnit\Framework\TestCase;

class DependencyCycleExceptionTest extends TestCase
{
    public function testCreate()
    {
        $message = 'Dependency cycle detected starting from a and ending on b';

        $exception = CycleDependencyException::create('a', 'b');

        $this->assertEquals($message, $exception->getMessage());
    }
}
