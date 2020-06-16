<?php


namespace App\Controller;


use App\Entity\Permission;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class PermissionTraiter

{

	private $entityManager;
	private $requestStack;

	public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
	{
		$this->entityManager = $entityManager;
		$this->requestStack = $requestStack;
	}


	public function __invoke(Permission $data, MailerInterface $mailer): Permission
	{
		$valeur = $this->requestStack->getCurrentRequest()->get('valeur');
		if ($valeur === 'oui') {
			$data->setEtatPermission('acceptée');
			$emailSubject = "Permission acceptée";
			$emailHtml = "<p>Bonjour,</p><p>Votre demande de permission est validée ..</p>";

			//recalculer solde congé

		} elseif ($valeur === 'non') {
			$data->setEtatPermission('refusée');
			$emailSubject = "Permission refusée";
			$emailHtml = "<p>Bonjour,</p><p>Nous somme désolé, votre demande de permission est refusée </p>";
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