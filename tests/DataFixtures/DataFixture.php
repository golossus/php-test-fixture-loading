<?php

declare(strict_types=1);

namespace Golossus\TestFixtureLoading\Tests\DataFixtures;

use Golossus\TestFixtureLoading\AbstractFixture;
use Golossus\TestFixtureLoading\FixtureRepository;

class DataFixture extends AbstractFixture
{
    public function load(FixtureRepository $fixtureRepository): void
    {
        $length = \count($fixtureRepository->all());
        $fixtureRepository->set(static::class, $length + 1);
    }
}

class DataFixture2 extends DataFixture
{
    public function depends(): array
    {
        return array(
            DataFixture::class,
        );
    }
}

class DataFixture3 extends DataFixture
{
    public function depends(): array
    {
        return array(
            DataFixture2::class,
        );
    }
}

class DataFixture4 extends DataFixture
{
    public function depends(): array
    {
        return array(
            DataFixture5::class,
        );
    }
}

class DataFixture5 extends DataFixture
{
    public function depends(): array
    {
        return array(
            DataFixture4::class,
        );
    }
}
