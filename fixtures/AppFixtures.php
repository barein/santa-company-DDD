<?php

declare(strict_types=1);

namespace Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Fixtures\Factory\LetterProcessing\ChildFactory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
//        ChildFactory::createOne();

        $manager->flush();
    }
}
