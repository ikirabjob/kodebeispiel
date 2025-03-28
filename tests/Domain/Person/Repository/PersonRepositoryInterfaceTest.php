<?php

namespace App\Tests\Domain\Person\Repository;

use App\DataFixtures\PersonFixture;
use App\Domain\Person\Enums\PersonStatusEnum;
use App\Domain\Person\Model\Person;
use App\Domain\Person\Repository\PersonRepositoryInterface;
use App\Domain\Person\ValueObject\Email;
use App\Domain\Person\ValueObject\UserName;
use App\Domain\Shared\Criteria\Criteria;
use App\Infrastructure\Person\Repository\Doctrine\PersonRepository;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\UuidV4;

class PersonRepositoryInterfaceTest extends KernelTestCase
{
    /**
     * @var PersonRepository|null
     */
    private ?PersonRepository $repository;

    /**
     * @var object|null
     */
    private ?object $fixtures;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->repository = self::getContainer()->get(PersonRepositoryInterface::class);
        $this->fixtures = $databaseTool->loadFixtures([
            PersonFixture::class
        ])->getReferenceRepository();
    }

    public function test_search_person() : void
    {
        $person = $this->repository->search($this->fixtures->getReference('person')->getPersonId());
        self::assertInstanceOf(Person::class, $person);
        self::assertNotNull($person);
    }

    public function test_save_person() : void
    {
        $person = Person::create(
            null,
            PersonStatusEnum::STATUS_CANDIDATE_NO_AGREEMENT->value,
            new UserName('test', 'test', 'test')
        );
        $person = $this->repository->save($person);
        self::assertNull($person);
    }

    public function test_search_by_criteria() : void
    {
        $persons = $this->repository->searchByCriteria(new Criteria([]));

        $result = [];
        foreach ($persons as $key => $person) {
            $result[$key] = $person;
        }

        self::assertNotNull($result);
        self::assertIsArray($result);
    }

    public function test_delete_person() : void
    {
        $person = $this->repository->delete($this->fixtures->getReference('person')->getPersonId());
        self::assertNull($person);
    }

    protected function tearDown(): void
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
        $this->repository = null;
        $this->fixtures = null;
    }
}