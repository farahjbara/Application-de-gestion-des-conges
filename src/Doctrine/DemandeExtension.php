<?php
// api/src/Doctrine/CurrentUserExtension.php

namespace App\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Demande;
use App\Entity\Permission;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

final class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
	private $security;

	public function __construct(Security $security)
	{
		$this->security = $security;
	}

	public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null): void
	{
		$this->addWhere($queryBuilder, $resourceClass);
	}

	public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = []): void
	{
		$this->addWhere($queryBuilder, $resourceClass);
	}

	private function addWhere(QueryBuilder $queryBuilder, string $resourceClass): void
	{
		if (Demande::class !== $resourceClass || $this->security->isGranted('ROLE_RH') || $this->security->isGranted('ROLE_CHEF_PROJET') || null === $user = $this->security->getUser()) {
			return;
		}

		if (Permission::class !== $resourceClass || $this->security->isGranted('ROLE_RH') || $this->security->isGranted('ROLE_CHEF_PROJET') || null === $user = $this->security->getUser()) {
			return;
		}

		$rootAlias = $queryBuilder->getRootAliases()[0];
		$queryBuilder->andWhere(sprintf('%s.user = :current_user', $rootAlias));
		$queryBuilder->setParameter('current_user', $user->getId());
	}
}
