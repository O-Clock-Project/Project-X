<?php

namespace App\Security;

use App\Entity\User;
use App\Entity\Bookmark;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class BookmarkVoter extends Voter
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
        if (!$subject instanceof Bookmark) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // if ($this->decisionManager->decide($token, array('ROLE_TEACHER'))) {
        //     return true;
        // }
  
        // you know $subject is a Bookmark object, thanks to supports
        /** @var Bookmark $bookmark */
        $bookmark = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($bookmark, $user);
            case self::EDIT:
                return $this->canEdit($bookmark, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Bookmark $bookmark, User $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($bookmark, $user)) {
            return true;
        }


        return !$bookmark->getBanned();
    }

    private function canEdit(Bookmark $bookmark, User $user)
    {
        // this assumes that the data object has a getUser() method
        // to get the entity of the user who owns this data object

        return $user === $bookmark->getUser();
    }
}