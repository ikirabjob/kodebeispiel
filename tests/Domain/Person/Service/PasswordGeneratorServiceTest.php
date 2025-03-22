<?php

namespace App\Tests\Domain\Person\Service;

use App\Domain\Person\Service\PasswordGeneratorServiceInterface;
use App\Infrastructure\Person\Service\PasswordGenerator;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PasswordGeneratorServiceTest extends KernelTestCase
{
    /**
     * @throws Exception
     */
    public function test_generate_password() : void
    {
        /** @var PasswordGenerator $passwordGeneratorService */
        $passwordGeneratorService = self::getContainer()->get(PasswordGeneratorServiceInterface::class);
        $password = $passwordGeneratorService->generatePassword(6);
        self::assertNotNull($password);
        self::assertIsString($password);
    }
}