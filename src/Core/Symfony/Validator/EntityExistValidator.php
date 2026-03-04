<?php

namespace App\Core\Symfony\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class EntityExistValidator extends ConstraintValidator
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof EntityExist) {
            throw new UnexpectedTypeException($constraint, EntityExist::class);
        }

        if (is_null($value)) {
            return;
        }

        $id = match (gettype($value)) {
            'string', 'integer' => $value,
            'object' => $value instanceof Uuid ? $value->toString() : '',
            default => '',
        };

        if (empty($id)) {
            $this->context->buildViolation('Entity Id cannot by empty.')->addViolation();

            return;
        }

        $repository = $this->entityManager->getRepository($constraint->entity);
        $entity = $repository->find($id);

        if (is_null($entity)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ entity }}', $constraint->entity)
                ->setParameter('{{ id }}', (string) $id)
                ->addViolation()
            ;
        }
    }
}
