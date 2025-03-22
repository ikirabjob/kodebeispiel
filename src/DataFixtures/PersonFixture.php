<?php

namespace App\DataFixtures;

use App\Domain\Person\Enums\PersonStatusEnum;
use App\Domain\Person\Model\Person;
use App\Domain\Person\ValueObject\UserName;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PersonFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $person = Person::create(
            null,
            PersonStatusEnum::STATUS_CANDIDATE_NO_AGREEMENT->value,
            new UserName('test', 'test', 'test')
        );
        $manager->persist($person);

        $manager->flush();

        $this->addReference('person', $person);
    }
}
