<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class BaseVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, $this->getSupportedAttributes()) && $this->supportsClass($subject);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case $this->getViewAttribute():
                return $this->canView($subject, $user);
            case $this->getEditAttribute():
                return $this->canEdit($subject, $user);
            case $this->getDeleteAttribute():
                return $this->canDelete($subject, $user);
        }

        return false;
    }

    abstract protected function supportsClass($subject): bool;
    abstract protected function getSupportedAttributes(): array;
    abstract protected function getViewAttribute(): string;
    abstract protected function getEditAttribute(): string;
    abstract protected function getDeleteAttribute(): string;
    abstract protected function canView($subject, UserInterface $user): bool;
    abstract protected function canEdit($subject, UserInterface $user): bool;
    abstract protected function canDelete($subject, UserInterface $user): bool;
}
