<?php

namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
	public function checkPreAuth(UserInterface $user)
	{
		if (!$user instanceof AppUser) {
			return;
		}

		// user is enabled, show a generic Account Not Found message.
		if ($user->getEnabled() === false) {
			throw new DisabledException('Votre compte est desactivé');
		}
	}

	public function checkPostAuth(UserInterface $user)
	{
		if (!$user instanceof AppUser) {
			return;
		}


		// user is enabled, show a generic Account Not Found message.
		if ($user->getEnabled() === false) {
			throw new DisabledException('Votre compte est desactivé');
		}
	}
}