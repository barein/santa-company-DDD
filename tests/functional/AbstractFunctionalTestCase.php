<?php

declare(strict_types=1);

namespace Tests\functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

abstract class AbstractFunctionalTestCase extends WebTestCase
{
    use ResetDatabase;
    use Factories;
}
