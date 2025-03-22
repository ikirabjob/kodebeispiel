<?php

namespace App\DataFixtures;

use App\Domain\Person\Enums\LoginTypeEnum;
use App\Domain\Person\Model\Login;
use App\Domain\Person\ValueObject\Auth\Credentials;
use App\Domain\Person\ValueObject\Auth\HashedPassword;
use App\Domain\Person\ValueObject\Auth\UserIdentifier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV4;

class LoginFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $userIdentifier = UserIdentifier::create(
            'ikirabjob@gmail.com',
            LoginTypeEnum::LOGIN_TYPE_EMAIL->value
        );

        $login = Login::create(
            new UuidV4(),
            new Credentials(
                $userIdentifier,
                HashedPassword::encode('aaaaaa')
            ),
            $this->getReference('person'),
        );

        $manager->persist($login);
        $manager->flush();

        $this->addReference('login', $login);
    }

    public function getDependencies(): array
    {
        return [
            PersonFixture::class,
        ];
    }
}
