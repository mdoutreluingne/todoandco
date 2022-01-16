<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

class TaskVoter extends Voter
{
    const DELETE = 'TASK_DELETE';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::DELETE])
            && $subject instanceof \App\Entity\Task;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::DELETE:
                return $this->canDelete($subject, $user);
                break;
        }

        return false;
    }

    private function canDelete($subject, User $user): bool
    {
        //Tasks attached to the "anonymous" user can be deleted only by users with the administrator role (ROLE_ADMIN)
        if ($subject->getUser()->getUsername() === "anonyme" && $this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return $subject->getUser() === $user;
    }
}
