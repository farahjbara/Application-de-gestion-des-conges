<?php

namespace App\Security\Voter;

use App\Entity\Demande;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;

class DemandeVoter extends Voter
{
	private $security;

	public function __construct(Security $security)
	{
		$this->security = $security;
	}

	protected function supports($attribute, $subject)
	{
		// replace with your own logic
		// https://symfony.com/doc/current/security/voters.html
		return in_array($attribute, ['POST', 'EDIT', 'VIEW', 'DELETE'])
			&& $subject instanceof \App\Entity\Demande;
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
			case 'POST':
				return $this->security->isGranted('IS_AUTHENTICATED_REMEMBERED');
				break;
			case 'VIEW':
			case 'EDIT':
			case 'DELETE':
				return $this->canEditDeleteView($subject, $user);
				break;
		}

		return false;

	}

	public function canEditDeleteView(Demande $demande, User $user)
	{
		return $demande->getUser() === $user || $this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_RH');
	}
}
