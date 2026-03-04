<?php

namespace App\Application\Task\Security\Voter;

use App\Domain\Task\Entity\Task;
use App\Domain\User\Entity\User;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends Voter<string, Task>
 */
final class TaskOwnerVoter extends Voter
{
    public const array VOTERS = [
        self::IS_TASK_OWNER,
    ];

    public const string IS_TASK_OWNER = 'is_task_owner';
    public const string MESSAGE_ILLEGAL_TASK = 'Illegal task';

    protected function supports($attribute, $subject): bool
    {
        return in_array($attribute, self::VOTERS) && $subject instanceof Task;
    }

    /**
     * @param Task $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::IS_TASK_OWNER => $this->isOwner($subject, $user),
            default => throw new LogicException(sprintf('Unsupported voter: %s', $attribute)),
        };
    }

    private function isOwner(Task $task, User $user): bool
    {
        return $task->getAssignedUser()?->getId()->toString() === $user->getId()->toString();
    }
}
