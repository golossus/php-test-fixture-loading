<?php

declare(strict_types=1);

namespace Golossus\TestFixtureLoading\Tests;

use Golossus\TestFixtureLoading\CycleDependencyException;
use Golossus\TestFixtureLoading\FixtureLoaderTrait;
use Golossus\TestFixtureLoading\Tests\DataFixtures\DataFixture;
use Golossus\TestFixtureLoading\Tests\DataFixtures\DataFixture2;
use Golossus\TestFixtureLoading\Tests\DataFixtures\DataFixture3;
use Golossus\TestFixtureLoading\Tests\DataFixtures\DataFixture4;
use PHPUnit\Framework\TestCase;

class LoadDataFixturesTraitTest extends TestCase
{
    private $trait;

    protected function setUp(): void
    {
        $this->trait = $this->getTraitTestClass();
    }

    public function testLoadDataFixturesLoadsNothingIfEmptyDataFixturesProvided()
    {
        $referenceRepository = $this->trait->loadFixtures(array());

        $this->assertEmpty($referenceRepository->all());
    }

    public function testLoadDataFixturesLoadsAllDataFixturesProvided()
    {
        $referenceRepository = $this->trait->loadFixtures(
            array(
                DataFixture::class,
                DataFixture2::class,
                DataFixture3::class,
            )
        );

        $this->assertEquals(1, $referenceRepository->get(DataFixture::class));
        $this->assertEquals(2, $referenceRepository->get(DataFixture2::class));
        $this->assertEquals(3, $referenceRepository->get(DataFixture3::class));
    }

    public function testLoadDataFixturesLoadsAllDataFixturesProvidedWithDependencies()
    {
        $referenceRepository = $this->trait->loadFixtures(
            array(
                DataFixture::class,
                DataFixture2::class,
                DataFixture3::class,
            )
        );

        $this->assertEquals(1, $referenceRepository->get(DataFixture::class));
        $this->assertEquals(2, $referenceRepository->get(DataFixture2::class));
        $this->assertEquals(3, $referenceRepository->get(DataFixture3::class));
    }

    public function testLoadDataFixturesLoadsAllDataFixturesProvidedWithDependenciesUnordered()
    {
        $referenceRepository = $this->trait->loadFixtures(
            array(
                DataFixture3::class,
                DataFixture::class,
                DataFixture2::class,
            )
        );

        $this->assertEquals(1, $referenceRepository->get(DataFixture::class));
        $this->assertEquals(2, $referenceRepository->get(DataFixture2::class));
        $this->assertEquals(3, $referenceRepository->get(DataFixture3::class));
    }

    public function testLoadDataFixturesLoadsAllDataFixturesOnlyOnce()
    {
        $referenceRepository = $this->trait->loadFixtures(
            array(
                DataFixture::class,
                DataFixture::class,
                DataFixture2::class,
                DataFixture2::class,
                DataFixture3::class,
                DataFixture3::class,
            )
        );

        $this->assertCount(3, $referenceRepository->all());
        $this->assertEquals(1, $referenceRepository->get(DataFixture::class));
        $this->assertEquals(2, $referenceRepository->get(DataFixture2::class));
        $this->assertEquals(3, $referenceRepository->get(DataFixture3::class));
    }

    public function testLoadDataFixturesThrowsExceptionIfDependencyCycleExists()
    {
        $this->expectException(CycleDependencyException::class);

        $this->trait->loadFixtures(
            array(
                DataFixture4::class,
            )
        );
    }

    private function getTraitTestClass(array $dependencies = array())
    {
        return new class($dependencies) {
            use FixtureLoaderTrait {
                FixtureLoaderTrait::loadFixtures as public;
            }

            protected function buildFixture(string $namespace): DataFixture
            {
                return new $namespace();
            }
        };
    }
}
