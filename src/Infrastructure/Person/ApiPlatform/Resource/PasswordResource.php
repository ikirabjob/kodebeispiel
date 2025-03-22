<?php

namespace App\Infrastructure\Person\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Infrastructure\Person\ApiPlatform\DataProcessor\PasswordChangeDataProcessor;
use App\Infrastructure\Person\ApiPlatform\DataProcessor\PasswordChangeFromAdminDataProcessor;
use App\Infrastructure\Person\ApiPlatform\DataProcessor\PasswordResetDataProcessor;
use App\Infrastructure\Person\ApiPlatform\Payload\PasswordChangeFromAdminPayload;
use App\Infrastructure\Person\ApiPlatform\Payload\PasswordChangePayload;
use App\Infrastructure\Person\ApiPlatform\Payload\PasswordResetPayload;

#[ApiResource(
    shortName: 'Password',
    operations: [
        new Post(
            uriTemplate: '/password/reset',
            openapiContext: ['summary' => 'Reset password'],
            normalizationContext: ['groups' => ['password_reset']],
            input: PasswordResetPayload::class,
            output: false,
            processor: PasswordResetDataProcessor::class
        ),
        new Post(
            uriTemplate: '/password/change',
            openapiContext: ['summary' => 'Change password'],
            normalizationContext: ['groups' => ['password_change']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY")',
            input: PasswordChangePayload::class,
            output: false,
            processor: PasswordChangeDataProcessor::class
        ),
        new Post(
            uriTemplate: '/password/admin_change',
            openapiContext: ['summary' => 'Change password from admin panel'],
            normalizationContext: ['groups' => ['password_change']],
            security: 'is_granted("ROLE_ADMINISTRATOR")',
            input: PasswordChangeFromAdminPayload::class,
            output: false,
            processor: PasswordChangeFromAdminDataProcessor::class
        ),
    ]
)]
class PasswordResource
{
}
