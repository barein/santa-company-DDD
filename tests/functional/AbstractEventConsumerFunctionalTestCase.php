<?php

declare(strict_types=1);

namespace Tests\functional;

use App\Shared\Application\Bus\EventBusInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class AbstractEventConsumerFunctionalTestCase extends AbstractFunctionalTestCase
{
    protected EventBusInterface $eventBus;
    protected CommandTester $commandTester;

    protected function setUp(): void
    {
        parent::setUp();
        $this->eventBus = static::getContainer()->get(EventBusInterface::class);
        $application = new Application(static::$kernel);
        $command = $application->find('messenger:consume');
        $this->commandTester = new CommandTester($command);
    }
}
