<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Task;

class TaskVoter extends Voter
{
    public const VIEW = 'TASK_VIEW';
    public const EDIT = 'TASK_EDIT';
    public const DELETE = 'TASK_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE]) && $subject instanceof Task;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Task $subject */
        switch ($attribute) {
            case self::VIEW:
                return $this->canView($subject, $user);
            case self::EDIT:
                return $this->canEdit($subject, $user);
            case self::DELETE:
                return $this->canDelete($subject, $user);
        }

        return false;
    }

    private function canView(Task $task, UserInterface $user): bool
    {
        // Any user with ROLE_USER can view their own tasks
        return in_array('ROLE_USER', $user->getRoles()) && $task->getUser() === $user;
    }

    private function canEdit(Task $task, UserInterface $user): bool
    {
        // Users can edit their own tasks
        return in_array('ROLE_USER', $user->getRoles()) && $task->getUser() === $user;
    }

    private function canDelete(Task $task, UserInterface $user): bool
    {
        // Users can delete their own tasks
        return in_array('ROLE_USER', $user->getRoles()) && $task->getUser() === $user;
    }
}
