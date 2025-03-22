<?php

namespace App\Tests\Domain\Person\Event;

use App\Application\Shared\Event\EventBusInterface;
use App\Domain\Person\Event\ProfileDataApplied;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Uid\UuidV4;

class ProfileDataAppliedTest extends KernelTestCase
{
    /**
     * @var object|null
     */
    private ?object $eventBus;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->eventBus  = self::getContainer()->get(EventBusInterface::class);
    }

    public function test_profile_data_applied_event() : void
    {
        $personId = new UuidV4();
        $event = new ProfileDataApplied($personId);
        /** @var Envelope $result */
        $result = $this->eventBus->dispatch($event);
        self::assertEquals($personId, $result->getMessage()->personId);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->eventBus = null;
    }
}