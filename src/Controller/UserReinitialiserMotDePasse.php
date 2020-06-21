<?php

namespace App\Controller;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserReinitialiserMotDePasse
{
	private $entityManager;
	private $passwordEncoder;
	private $validator;
	private $tokenManager;
	private $requestStack;

	public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator, JWTTokenManagerInterface $tokenManager, RequestStack $requestStack)
	{
		$this->entityManager = $entityManager;
		$this->passwordEncoder = $passwordEncoder;
		$this->validator = $validator;
		$this->tokenManager = $tokenManager;
		$this->requestStack = $requestStack;
	}

	public function __invoke(User $data)
	{
		$errors = $this->validator->validate($data, null, ['resetting_password_reset']);

		if (count($errors) > 0) {
			throw new ValidationException($errors);
		}

		// récuperer paramétre de l'url confirmationToken
		$confirmationToken = $this->requestStack->getCurrentRequest()->get('confirmationToken');

		if (!isset($confirmationToken) || empty($confirmationToken)) {
			return array(
				'success' => false,
				'message' => 'invalid confirmation token'
			);
		}

		/** @var User $user */
		$user = $this->entityManager->getRepository(User::class)->findOneBy(array('confirmationToken' => $confirmationToken));

		if (null === $user) {
			return array(
				'success' => false,
				'message' => 'user does not exist !'
			);
		}

		if (false == $user->getEnabled()) {
			return array(
				'success' => false,
				'message' => 'Votre compte est désactivé!'
			);
		}

		$user->setPassword(
			$this->passwordEncoder->encodePassword(
				$user,
				$data->getPlainPassword()
			)
		);

		$user->setConfirmationToken(null);

		$this->entityManager->flush();

		return array(
			'user' => $user,
			'success' => true,
			'message' => 'Votre mot de passe a été modifié avec succés.'
		);

	}
}