<?php

declare(strict_types=1);

namespace Golossus\TestFixtureLoading;

/**
 * Class AbstractFixture.
 */
abstract class AbstractFixture implements Fixture
{
    /**
     * {@inheritDoc}
     */
    abstract public function load(FixtureRepository $fixtureRepository): void;

    /**
     * {@inheritDoc}
     */
    public function depends(): array
    {
        return array();
    }
}
