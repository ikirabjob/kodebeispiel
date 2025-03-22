<?php

namespace App\Infrastructure\Shared\Validator\Constraints;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class PhoneValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof Phone) {
            throw new UnexpectedTypeException($constraint, Phone::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if ('strict' === $constraint->mode) {
            // ...
        }

        if (0 === preg_match('/^[0-9]{4,12}$/', $value, $matches)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->setCode(Phone::INVALID_FORMAT_ERROR)
                ->addViolation();

            return;
        }

        try {
            $phoneNumber = PhoneNumberUtil::getInstance()->parse('+'.$value, null);
        } catch (NumberParseException $e) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->setCode(Phone::INVALID_FORMAT_ERROR)
                ->addViolation();

            return;
        }

        if (!PhoneNumberUtil::getInstance()->isValidNumber($phoneNumber)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->setCode(Phone::INVALID_FORMAT_ERROR)
                ->addViolation();

            return;
        }
    }
}
