<?php

namespace App\Infrastructure\Person\ApiPlatform\DataProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Person\Command\PasswordChangeFromAdminCommand;
use App\Application\Shared\Command\CommandBusInterface;
use App\Infrastructure\Person\ApiPlatform\Payload\PasswordChangeFromAdminPayload;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Uid\Uuid;

class PasswordChangeFromAdminDataProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly RequestStack $requestStack,
    ) {
    }

    /**
     * @param mixed|PasswordChangeFromAdminPayload $data
     *
     * @return void
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($operation instanceof Post) {
            $platform = $this->requestStack->getCurrentRequest()->server->get('HTTP_SEC_CH_UA_PLATFORM');
            $browser = $this->requestStack->getCurrentRequest()->server->get('HTTP_SEC_CH_UA');
            $ip = $this->requestStack->getCurrentRequest()->getClientIp();

            $this->commandBus->dispatch(new PasswordChangeFromAdminCommand(
                Uuid::fromString($data->personId),
                $data->newPassword,
                $platform,
                $browser,
                $ip
            ));
        }
    }
}
