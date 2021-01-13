<?php

declare(strict_types=1);

namespace Golossus\TestFixtureLoading;

/**
 * Interface Fixture.
 */
interface Fixture
{
    /**
     * This method loads some data and uses the fixture repository to also store data
     * in memory for later use in the tests.
     */
    public function load(FixtureRepository $fixtureRepository): void;

    /**
     * Returns an array of dependant fixtures namespaces, so the dependencies will
     * be loaded before the current one.
     */
    public function depends(): array;
}
