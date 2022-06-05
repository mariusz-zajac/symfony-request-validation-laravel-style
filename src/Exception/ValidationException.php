<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * This exception is thrown when an invalid data is given
 */
class ValidationException extends \Exception
{
    private ConstraintViolationListInterface $violations;

    public function __construct(string $message, ConstraintViolationListInterface $violations)
    {
        parent::__construct($message);

        $this->violations = $violations;
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}
