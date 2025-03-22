<?php

namespace App\Tests\Domain\Person\Event;

use App\Application\Shared\Event\EventBusInterface;
use App\DataFixtures\DocumentCategoryFixture;
use App\Domain\Person\Event\PersonValidated;
use App\Domain\Person\Exception\PersonNotFoundException;
use Exception;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Uid\UuidV4;

class PersonValidatedTest extends KernelTestCase
{
    /**
     * @var object|null
     */
    private ?object $eventBus;

    private ?object $fixtures;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->eventBus  = self::getContainer()->get(EventBusInterface::class);
        $this->fixtures = $databaseTool->loadFixtures([
            DocumentCategoryFixture::class
        ])->getReferenceRepository();
    }

    public function test_person_validated_event() : void
    {
        $this->expectException(PersonNotFoundException::class);
        $personId = new UuidV4();
        $event = new PersonValidated($personId);
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