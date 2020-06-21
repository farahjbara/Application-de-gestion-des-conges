<?php
namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Permission;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class PermissionSubscriber implements EventSubscriberInterface
{

	private $security;

	public function __construct(Security $security)
	{
		$this->security = $security;
	}

	public static function getSubscribedEvents()
	{
		return [
			KernelEvents::VIEW => ['ajouterPermission', EventPriorities::PRE_WRITE],
		];
	}

	// enable or disable User
	public function ajouterPermission (ViewEvent $event)
	{
		$permission = $event->getControllerResult();
		$method = $event->getRequest()->getMethod();

		if (!$permission instanceof Permission || Request::METHOD_POST !== $method) {
			return;
		}

		$user = $this->security->getUser();
		$permission->setUser($user);

	}
}