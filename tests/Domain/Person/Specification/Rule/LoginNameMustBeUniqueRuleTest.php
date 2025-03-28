<?php

namespace App\Tests\Domain\Person\Specification\Rule;

use App\Domain\Person\Enums\LoginTypeEnum;
use App\Domain\Person\Specification\Checker\LoginNameUniquenessCheckerInterface;
use App\Domain\Person\Specification\Rule\LoginNameMustBeUniqueRule;
use App\Domain\Person\ValueObject\Auth\UserIdentifier;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LoginNameMustBeUniqueRuleTest extends KernelTestCase
{
    private ?LoginNameUniquenessCheckerInterface $checker;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->checker = self::getContainer()->get(LoginNameUniquenessCheckerInterface::class);
    }

    public function test_is_satisfied_by() : void
    {
        $userIdentifier = UserIdentifier::create(
            'test@7lags.com',
            LoginTypeEnum::LOGIN_TYPE_EMAIL->value
        );
        $rule = new LoginNameMustBeUniqueRule($this->checker, $userIdentifier);
        self::assertTrue($rule->isSatisfiedBy());
    }


    protected function tearDown(): void
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
        $this->checker = null;
    }
}