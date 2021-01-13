<?php

declare(strict_types=1);

namespace Golossus\TestFixtureLoading\Tests;

use Golossus\TestFixtureLoading\FixtureRepository;
use PHPUnit\Framework\TestCase;

class ReferenceRepositoryTest extends TestCase
{
    public function testRepositoryIsCreatedEmpty()
    {
        $repository = new FixtureRepository();

        $this->assertEmpty($repository->all());
    }

    public function testGetReturnsNullIfReferenceDoesNotExist()
    {
        $repository = new FixtureRepository();

        $this->assertNull($repository->get('dummy'));
    }

    public function testGetReturnsAStoredReference()
    {
        $reference = 'reference';
        $repository = new FixtureRepository();
        $repository->set('my-key', $reference);

        $this->assertEquals($reference, $repository->get('my-key'));
    }

    public function testGetReturnsAReplacedReference()
    {
        $reference = 'reference';
        $replacement = 'replacement';
        $repository = new FixtureRepository();
        $repository->set('my-key', $reference);
        $repository->set('my-key', $replacement);

        $this->assertEquals($replacement, $repository->get('my-key'));
    }
}
