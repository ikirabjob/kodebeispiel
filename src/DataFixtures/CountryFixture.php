<?php

namespace App\DataFixtures;

use App\Domain\Person\Model\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV4;

class CountryFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $country = new Country(
            new UuidV4(),
            'Test',
            'test',
            'test'
        );

        $manager->persist($country);
        $manager->flush();

        $this->addReference('country', $country);
    }
}
