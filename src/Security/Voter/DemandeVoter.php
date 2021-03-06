<?php

namespace App\Security\Voter;

use App\Entity\Demande;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DemandeVoter extends Voter
{
	private $security;
	/**
	 * @var AuthorizationCheckerInterface
	 */
	private $authorizationChecker;

	public function __construct(Security $security, AuthorizationCheckerInterface $authorizationChecker)
	{
		$this->security = $security;
		$this->authorizationChecker = $authorizationChecker;
	}

	protected function supports($attribute, $subject)
	{
		// replace with your own logic
		// https://symfony.com/doc/current/security/voters.html
		return in_array($attribute, ['EDIT', 'VIEW', 'DELETE'])
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
		return $demande->getUser() === $user || $this->security->isGranted('ROLE_CHEF_PROJET') || $this->security->isGranted('ROLE_RH');
	}

}
