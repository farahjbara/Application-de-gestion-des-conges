<?php

namespace App\Controller;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Security\TokenGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserResettingPasswordRequest
{
	private $entityManager;
	private $router;
	private $tokenGenerator;
	private $validator;

	public function __construct(EntityManagerInterface $entityManager, RouterInterface $router, TokenGenerator $tokenGenerator, ValidatorInterface $validator)
	{
		$this->entityManager = $entityManager;
		$this->router = $router;
		$this->tokenGenerator = $tokenGenerator;
		$this->validator = $validator;
	}

	public function __invoke(User $data, MailerInterface $mailer)
	{
		$errors = $this->validator->validate($data, null, ['resetting_password_request']);
		if (count($errors) > 0) {
			throw new ValidationException($errors);
		}

		// récuperer email envoyé dans la requete
		$email = $data->getEmail();

		/** @var User $user */
		$user = $this->entityManager->getRepository(User::class)->findOneBy(array('email' => $email));

		if (null === $user) {
			return array(
				'success' => false,
				'message' => 'Un utilisateur avec cet email n\'existe pas.'
			);
		}

		if (false === $user->getEnabled()) {
			return array(
				'success' => false,
				'message' => 'Votre compte est désactivé !'
			);
		}


		$user->setConfirmationToken($this->tokenGenerator->createRandomSecureToken());

		$this->entityManager->flush();

		$resettingPasswordUrl = $this->router->generate('api_users_user_reinitialiser_mot_de_passe_item', array(
			'confirmationToken' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);

		// send email
		$email = (new TemplatedEmail())
			->from('farahjbara99@gmail.com')
			->to($email)
			->subject('Réinitialisation de votre mot de passe')
			->htmlTemplate('emails/mot_de_passe_oublie.html.twig')
			->context([
				'url'=> $resettingPasswordUrl,
			])
		;
		$mailer->send($email);

		return array(
			'success' => true,
			'message' => 'Email envoyé'
		);
	}
}