[![Build Status](https://api.travis-ci.org/golossus/php-test-fixture-loading.svg?branch=main)](https://api.travis-ci.org/golossus/php-test-fixture-loading)

<p align="center">
    <a href="https://www.golossus.com" target="_blank">
        <img height="100" src="https://avatars2.githubusercontent.com/u/58183018">
    </a>
</p>

[php-test-fixture-loading][1] is a package which tries to ease in the process of loading testing fixtures to have a
better maintainability and code reuse. With this package, (data) fixtures can be created as simple classes that can be
joined to compose more complex testing scenarios.

Installation
------------

```shell
composer require golossus/php-test-fixture-loading
```

Usage
-----

## Configuration

Use the trait [FixtureLoaderTrait][2] on a base test class or `TestCase`. Those will typically inherit from a `PHPUnit`
test class, like the _Symfony_ `WebTestCase` or similar.

Additionally, this trait has an abstract method `buildFixture` which should be implemented as well. This method is
responsible to adapt the way data fixtures are instantiated, because different projects might follow different
approaches. This is specially true when a Dependency Injection container is needed to build a fixture.

As an example take the following `TestCase` class which uses a _Symfony_ 4 `WebTestCase`. Take a look at the trait and
the method:

```php
<?php declare(strict_types = 1);

namespace App\Tests\Rest;

use Golossus\TestFixtureLoading\Fixture;
use Golossus\TestFixtureLoading\FixtureLoaderTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FunctionalTestCase extends WebTestCase
{
    use FixtureLoaderTrait;

    protected function buildFixture(string $namespace): Fixture
    {
        return self::$container->get($namespace);
    }

}
```

Your case might be more complex (or maybe simpler), so implement this method to cover your needs.

> For _Symfony_ applications like in the example above don't forget to declare the fixtures folder as services, or
> you won't be able to build the fixtures.

> Although the example uses the testing container to build the data fixtures, so in practice you're allowed to build
> private services (which is the default behaviour), actually it's possible that you need to make the fixtures public.
> Look at the following example:

```yaml
# in cofig/services_test.yaml 
services:
  _defaults:
    autowire: true
    autoconfigure: true

  App\Tests\DataFixtures\:
    resource: '../tests/DataFixtures/*'
    public: true
```

### Creating a fixture class

To create a fixture is so easy as creating a new class which implement the [Fixture][3] interface. This interface only
provides two methods: `load` and `depends`. The first one defines the logic to create the data and provides
a [FixtureRepository][4] parameter to store a reference on memory of any object created (and maybe stored in the
database) which can be retrieved afterwards on the test function. The second method is useful to define which other data
fixtures should be loaded before the current one.

> All dependencies of a loaded fixture will be also loaded even if they are not specified directly in the list of
> fixtures to load.

> If any fixture declares a dependency graph which produces a cycle, will throw an exception during the loading phase.

> Even if some dependency is used more than once it will actually load just one time.

As an example of fixture:

```php
<?php declare(strict_types = 1);

namespace Tests\Fixtures;

use Golossus\TestFixtureLoading\AbstractFixture;
use Golossus\TestFixtureLoading\FixtureRepository;
...

class AdminUserFixture extends AbstractFixture
{
    ...
    const ADMIN_USER = 'admin-user';

    public function load(FixtureRepository $fixtureRepository): void
    {
        $company = $fixtureRepository->get(CompanyFixture::COMPANY);

        $user = $this->createAdminUserForCompany($company);

        $fixtureRepository->set(self::ADMIN_USER, $user);
    }

    public function depends(): array
    {
        return [
            CompanyFixture::class,
        ];
    }
}
```

### Loading fixtures

Once the configuration above has been completed, the usage is quite straightforward, just use the method `loadFixtures`
provided in the previous trait to load any desired fixture. This method returns a special fixture repository which is
used during loading to store object references on demand.

```php
public function testWhateverYouLike()
{
    // First load the required data fixtures
    $fixtures = $this->loadFixtures([
        SomeDataFixture::class,
        AnotherDataFixture::class,
    ]);
    
    // You can get a data fixture object by key
    $dummy = $fixtures->get('some-key'); 
    
    // The rest should be a normal test
}
```

Community
---------

* Join our [Slack][5] to meet the community and get support.
* Follow us on [GitHub][6].
* Read our [Code of Conduct][7].

Contributing
------------

This is an Open Source project. The Golossus team wants to enable it to be community-driven and open
to [contributors][8]. Take a look at [contributing documentation][9].

Security Issues
---------------

If you discover a security vulnerability, please follow our [disclosure procedure][10].

About Us
--------

This package development is led by the Golossus Team [Leaders][11] and supported by [contributors][8].

[1]: https://github.com/golossus/php-lazy-proxy-loading

[2]: ./lib/FixtureLoaderTrait.php

[3]: ./lib/Fixture.php

[4]: ./lib/FixtureRepository.php

[5]: https://join.slack.com/t/golossus/shared_invite/zt-db4brnes-M8q1Lw2ouFT5X~gQg69NQQ

[6]: https://github.com/golossus

[7]: ./CODE_OF_CONDUCT.md

[8]: ./CONTRIBUTORS.md

[9]: ./CONTRIBUTING.md

[10]: ./CONTRIBUTING.md#reporting-a-security-issue

[11]: ./CONTRIBUTING.md#leaders
