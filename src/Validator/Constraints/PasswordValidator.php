<?php

namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PasswordValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if (!preg_match(Password::PASSWORD_REG_EXP, $value, $matches))
        {
            $this->context->buildViolation($constraint->message)
                ->setCode($constraint->message)
                ->addViolation();
        }
    }
}