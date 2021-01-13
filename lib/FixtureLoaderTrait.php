<?php

declare(strict_types=1);

namespace Golossus\TestFixtureLoading;

/**
 * Trait FixtureLoaderTrait.
 */
trait FixtureLoaderTrait
{
    /**
     * Builds the data fixture and injects any possible dependency it may have. This is done commonly by
     * a service container instance.
     */
    abstract protected function buildFixture(string $namespace): Fixture;

    /**
     * Loads a bunch of data fixtures from a given array of namespaces.
     *
     * @param string[] $fixtures
     *
     * @throws CycleDependencyException
     */
    protected function loadFixtures(array $fixtures): FixtureRepository
    {
        $fixtureRepository = new FixtureRepository();

        $resolvedFixtures = array();
        $pending = array();
        foreach ($fixtures as $fixtureClass) {
            $this->resolveFixture($fixtureClass, $resolvedFixtures, $pending);
        }

        foreach ($resolvedFixtures as $fixture) {
            $fixture->load($fixtureRepository);
        }

        return $fixtureRepository;
    }

    /**
     * Resolved data fixtures taking into account their dependencies.
     *
     * @return void
     *
     * @throws CycleDependencyException
     */
    final private function resolveFixture(
        string $fixtureClass,
        array &$loaded,
        array &$pending
    ) {
        if (isset($loaded[$fixtureClass])) {
            return;
        }

        $fixture = $this->buildFixture($fixtureClass);

        $dependencies = $fixture->depends();
        if (empty($dependencies)) {
            $loaded[$fixtureClass] = $fixture;

            return;
        }

        $pending[$fixtureClass] = true;
        foreach ($dependencies as $dependency) {
            if (isset($pending[$dependency])) {
                throw CycleDependencyException::create($dependency, $fixtureClass);
            }

            $this->resolveFixture($dependency, $loaded, $pending);
        }

        $loaded[$fixtureClass] = $fixture;
        unset($pending[$fixtureClass]);
    }
}
