<?php

declare(strict_types=1);

namespace Golossus\TestFixtureLoading;

/**
 * Class FixtureRepository.
 */
class FixtureRepository
{
    private $fixtures = array();

    /**
     * Gets a fixture by a key.
     *
     * @return mixed $data
     */
    public function get(string $key)
    {
        if (!\array_key_exists($key, $this->fixtures)) {
            return null;
        }

        return $this->fixtures[$key];
    }

    /**
     * Sets a fixture using a key.
     *
     * @param mixed $data
     */
    public function set(string $key, $data): void
    {
        $this->fixtures[$key] = $data;
    }

    /**
     * Returns all fixtures as an array.
     */
    public function all(): array
    {
        return $this->fixtures;
    }
}
