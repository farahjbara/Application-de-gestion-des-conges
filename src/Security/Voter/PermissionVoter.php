<?php

namespace App\Security\Voter;

use App\Entity\Permission;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;

class PermissionVoter extends Voter
{
	private $security;

	public function __construct(Security $security)
	{
		$this->security = $security;
	}

	protected function supports($attribute, $subject)
	{

		return in_array($attribute, [ 'EDIT', 'VIEW', 'DELETE'])
			&& $subject instanceof \App\Entity\Permission;
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

	public function canEditDeleteView(Permission $permission, User $user)
	{
		return $permission->getUser() === $user || $this->security->isGranted('ROLE_CHEF_PROJET') || $this->security->isGranted('ROLE_RH');
	}
}
