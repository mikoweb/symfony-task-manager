<?php

namespace App\Core\Symfony\Validator;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;
use Attribute;

#[Attribute]
final class EntityExist extends Constraint
{
    #[HasNamedArguments]
    public function __construct(
        /**
         * @var class-string<object>
         */
        public string $entity,
        public string $message = 'The entity `{{ entity }}` with id `{{ id }}` does not exist.',
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct([], $groups, $payload);
    }

    public function validatedBy(): string
    {
        return EntityExistValidator::class;
    }
}
