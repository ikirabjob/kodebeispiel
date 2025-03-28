<?php

namespace App\Tests\Application\Person\Query;

use App\Application\Person\Query\FindInviteQuery;
use App\Application\Shared\Query\QueryBusInterface;
use App\DataFixtures\InviteFixture;
use App\DataFixtures\LoginFixture;
use App\DataFixtures\PersonFixture;
use App\Domain\Person\Model\Invite;
use Exception;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FindInviteQueryTest extends KernelTestCase
{
    /**
     * @var object|null
     */
    private ?object $queryBus;

    /**
     * @var object|null
     */
    private ?object $fixtures;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->queryBus = static::getContainer()->get(QueryBusInterface::class);
        $databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();

        $this->fixtures = $databaseTool->loadFixtures([
            InviteFixture::class
        ])->getReferenceRepository();
    }

    public function test_find_invite_query() : void
    {
        $inviteId = $this->fixtures->getReference('invite')->getInviteId();
        $inviteQuery = new FindInviteQuery($inviteId);
        /** @var Invite $invite */
        $invite = $this->queryBus->ask($inviteQuery);
        self::assertInstanceOf(Invite::class, $invite);
        self::assertEquals($inviteId, $invite->getInviteId());
    }

    protected function tearDown(): void
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
        $this->queryBus = null;
        $this->fixtures = null;
    }
}