<?php

namespace App\Infrastructure\Person\ApiPlatform\DataProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Person\Command\PasswordResetCommand;
use App\Application\Shared\Command\CommandBusInterface;
use App\Infrastructure\Person\ApiPlatform\Payload\PasswordResetPayload;
use Symfony\Component\HttpFoundation\RequestStack;

class PasswordResetDataProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly RequestStack $requestStack
    ) {
    }

    /**
     * @param mixed|PasswordResetPayload $data
     *
     * @return void
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $platform = $this->requestStack->getCurrentRequest()->server->get('HTTP_SEC_CH_UA_PLATFORM');
        $browser = $this->requestStack->getCurrentRequest()->server->get('HTTP_SEC_CH_UA');
        $ip = $this->requestStack->getCurrentRequest()->getClientIp();

        $this->commandBus->dispatch(new PasswordResetCommand(
            $data->loginName,
            $platform,
            $browser,
            $ip
        ));
    }
}
