<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class DemandeTraiter
{

	private $entityManager;
	private $requestStack;

	public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
	{
		$this->entityManager = $entityManager;
		$this->requestStack = $requestStack;
	}


	public function __invoke(Demande $data, MailerInterface $mailer): Demande
	{
		$valeur = $this->requestStack->getCurrentRequest()->get('valeur');
		if ($valeur === 'oui') {
			$data->setEtat('acceptée');
			$emailSubject = "Demande acceptée";
			$emailHtml = "<p>Bonjour,</p><p> Votre demande de congé est validée ..</p>";

			//recalculer solde congé
			$dateDebut = $data->getDateDebut();
			$dateFin = $data->getDateFin();
			$nbJoursConge = $dateFin->diff($dateDebut)->format("%a");

			$typeConge = $data->getTypeConge();

			/** @var User $user */
			$user = $data->getUser(); // récuperer user via demande
			$user->setSoldeAnnuel($user->getSoldeAnnuel() - $nbJoursConge);
			$user->setNbrJrsPris($user->getNbrJrsPris() + $nbJoursConge);
			$user->setNbrJrsRestant($user->getNbrJrsRestant() - $nbJoursConge);


		} elseif ($valeur === 'non') {
			$data->setEtat('refusée');
			$emailSubject = "Demande refusée";
			$emailHtml = "<p>Bonjour,</p><p>Nous somme désolé votre demande de congé est refusée ..</p>";
		}
		$this->entityManager->flush();

		$email = (new Email())
			->from('farahjbara99@gmail.com')
			->to($data->getUser()->getEmail())
			->subject($emailSubject)
			->html($emailHtml);
		$mailer->send($email);

		return $data;
	}

}