<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;

class CompetenceVoter extends Voter
{
    private $security = null;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['POST_EDIT', 'POST_VIEW','POST_VIEW_ALL','POST_DELETE','POST_CREATE'])
            && $subject instanceof \App\Entity\Competence;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
           case 'POST_CREATE':
                if ( $this->security->isGranted(Role::ADMIN) ) { return true; }
               break;
           case 'POST_DELETE':
                 if ( $this->security->isGranted(Role::ADMIN) ) { return true; }
                break;
           case 'POST_EDIT':
               if ( $this->security->isGranted(Role::ADMIN) ) { return true; }

                break;
           case 'POST_VIEW_ALL':
                if ( $this->security->isGranted(Role::ADMIN) ) { return true; }
                if ( $this->security->isGranted(Role::FORMATEUR) ) { return true; }
                if ( $this->security->isGranted(Role::CM) ) { return true; }   
                break;
            case 'POST_VIEW':
                 if ( $this->security->isGranted(Role::ADMIN) ) { return true; }
                 if ( $this->security->isGranted(Role::FORMATEUR) ) { return true; }
                 if ( $this->security->isGranted(Role::CM) ) { return true; }
                 if ( $this->security->isGranted(Role::APPRENANT) ) { return true; }

        }

        return false;
    }
}
