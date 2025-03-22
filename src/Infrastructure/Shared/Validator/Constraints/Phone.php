<?php

namespace App\Infrastructure\Shared\Validator\Constraints;

use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Exception\LogicException;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Phone extends Constraint
{
    public const VALIDATION_MODE_HTML5 = 'html5';
    public const VALIDATION_MODE_STRICT = 'strict';
    public const VALIDATION_MODE_LOOSE = 'loose';

    public const INVALID_FORMAT_ERROR = 'bd79c0ab-ddba-46cc-a703-a7a4b08de310';

    public const VALIDATION_MODES = [
        self::VALIDATION_MODE_HTML5,
        self::VALIDATION_MODE_STRICT,
        self::VALIDATION_MODE_LOOSE,
    ];

    protected const ERROR_NAMES = [
        self::INVALID_FORMAT_ERROR => 'STRICT_CHECK_FAILED_ERROR',
    ];

    /**
     * @deprecated since Symfony 6.1, use const ERROR_NAMES instead
     */
    protected static $errorNames = self::ERROR_NAMES;

    public $message = 'This value is not a valid phone number.';
    public $mode = true;
    public $normalizer;

    public function __construct(
        array $options = null,
        string $message = null,
        string $mode = null,
        callable $normalizer = null,
        array $groups = null,
        mixed $payload = null
    ) {
        if (\is_array($options) && \array_key_exists('mode', $options) && !\in_array($options['mode'], self::VALIDATION_MODES, true)) {
            throw new InvalidArgumentException('The "mode" parameter value is not valid.');
        }

        parent::__construct($options, $groups, $payload);

        $this->message = $message ?? $this->message;
        $this->mode = $mode ?? $this->mode;
        $this->normalizer = $normalizer ?? $this->normalizer;

        if (self::VALIDATION_MODE_STRICT === $this->mode && !class_exists(PhoneNumberUtil::class)) {
            throw new LogicException(sprintf('The "giggsey/libphonenumber-for-php" component is required to use the "%s" constraint in strict mode.', __CLASS__));
        }

        if (null !== $this->normalizer && !\is_callable($this->normalizer)) {
            throw new InvalidArgumentException(sprintf('The "normalizer" option must be a valid callable ("%s" given).', get_debug_type($this->normalizer)));
        }
    }
}
