<?php

namespace App\Infrastructure\Person\ApiPlatform\DataProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Person\Command\CreateUserCommand;
use App\Application\Shared\Command\CommandBusInterface;
use App\Domain\Shared\Exception\AccessDeniedException;
use App\Infrastructure\Person\ApiPlatform\Payload\CreateUserPayload;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Security\Core\Security;

readonly class CreateUserDataProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private Security $security
    ) {
    }

    /**
     * @param mixed|CreateUserPayload $data
     *
     * @throws AccessDeniedException
     */
    #[NoReturn]
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (!$this->security->isGranted('ROLE_ADMINISTRATOR')) {
            throw new AccessDeniedException('Access denied');
        }

        $this
            ->commandBus
            ->dispatch(new CreateUserCommand(
                $data->email,
                '8a22c137-1be9-4bb2-bdc2-1ecb331c4f9b'
            ));
    }
}
