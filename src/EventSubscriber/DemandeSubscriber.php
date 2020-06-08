<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Demande;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class DemandeSubscriber implements EventSubscriberInterface
{

	private $security;

	public function __construct(Security $security)
	{
		$this->security = $security;
	}

	public static function getSubscribedEvents()
	{
		return [
			KernelEvents::VIEW => ['ajouterDemande', EventPriorities::PRE_WRITE],
		];
	}

	// enable or disable User
	public function ajouterDemande(ViewEvent $event)
	{
		$demande = $event->getControllerResult();
		$method = $event->getRequest()->getMethod();

		if (!$demande instanceof Demande || Request::METHOD_POST !== $method) {
			return;
		}

		$user = $this->security->getUser();
		$demande->setUser($user);

	}
}