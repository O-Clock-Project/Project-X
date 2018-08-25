<?php

namespace App\Security;

use App\Entity\User;
use App\Entity\Announcement;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class AnnouncementVoter extends Voter
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

        // only vote on Announcement objects inside this voter
        if (!$subject instanceof Announcement) {
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

        if ($this->decisionManager->decide($token, array('ROLE_TEACHER'))) {
            return true;
        }

        // you know $subject is a Announcement object, thanks to supports
        /** @var Announcement $Announcement */
        $announcement = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($announcement, $user);
            case self::EDIT:
                return $this->canEdit($announcement, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Announcement $announcement, User $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($announcement, $user)) {
            return true;
        }


        return !$announcement->getBanned();
    }

    private function canEdit(Announcement $announcement, User $user)
    {
        // this assumes that the data object has a getUser() method
        // to get the entity of the user who owns this data object

        return $user === $announcement->getAuthor();
    }
}