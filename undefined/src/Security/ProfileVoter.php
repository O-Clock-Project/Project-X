<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class ProfileVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';

    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }


    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
            return false;
        }

        // only vote on Bookmark objects inside this voter
        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $askingUser = $token->getUser();

        if (!$askingUser instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        if ($this->decisionManager->decide($token, array('ROLE_ADMINISTRATOR'))) {
            return true;
        }

        // you know $subject is a Bookmark object, thanks to supports
        /** @var User $user */
        $user = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($user, $askingUser);
            case self::EDIT:
                return $this->canEdit($user, $askingUser);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(User $user, User $askingUser)
    {

        return true;
    }

    private function canEdit(User $user, User $askingUser)
    {
        // this assumes that the data object has a getUser() method
        // to get the entity of the user who owns this data object

        return $askingUser === $user;
    }
}