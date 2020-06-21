<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PermissionRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\PermissionTraiter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ApiResource(
 *   formats={"json"},
 *   collectionOperations={
 *         "get"={
 *             "security"="is_granted('ROLE_CHEF_PROJET', 'ROLE_RH')",
 *             "security_message"="Vous n'avez pas les droits d'accéder"
 *         },
 *         "post"={
 *             "security"="is_granted('IS_AUTHENTICATED_REMEMBERED')",
 *             "security_message"="Vous n'avez pas les droits d'accéder"
 *         },
 *     },
 *   itemOperations={
 *         "get"={
 *             "security"="is_granted('IS_AUTHENTICATED_REMEMBERED') and is_granted('VIEW', object)",
 *             "security_message"="Vous n'avez pas les droits d'accéder"
 *         },
 *         "delete"={
 *             "security"="is_granted('IS_AUTHENTICATED_REMEMBERED') and is_granted('DELETE', object)",
 *             "security_message"="Vous n'avez pas les droits d'accéder"
 *         },
 *         "put"={
 *             "security"="is_granted('IS_AUTHENTICATED_REMEMBERED') and is_granted('EDIT', object)",
 *              "security_message"="Vous n'avez pas les droits d'accéder"
 *         },
 *         "permission_accepter"={
 *         "method"="GET",
 *         "path"="/permissions/{id}/traiter/{valeur}",
 *         "controller"=PermissionTraiter::class,
 *         }

 *     },
 * )
 * @ApiFilter(SearchFilter::class, properties={"id": "exact", "etatPermission":"exact", "user": "exact"})
 * @ApiFilter(DateFilter::class, properties={"datePermission"})
 * @ORM\Entity(repositoryClass="App\Repository\PermissionRepository", repositoryClass=PermissionRepository::class)
 */
class Permission
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="time")
	 * @Assert\GreaterThan("+24 hours")
	 */
	private $heureDebut;

	/**
	 * @ORM\Column(type="time")
	 */
	private $heureFin;

	/**
	 * @ORM\Column(type="date")
	 */
	private $datePermission;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $etatPermission;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $approbationChef;

	/**
	 * @ORM\ManyToOne(targetEntity=User::class)
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $user;

	public function __construct()
	{
		$this->datePermission = new \DateTime();
		$this->etatPermission = 'en attente';
		$this->approbationChef = false;
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getHeureDebut(): ?\DateTimeInterface
	{
		return $this->heureDebut;
	}

	public function setHeureDebut(\DateTimeInterface $heureDebut): self
	{
		$this->heureDebut = $heureDebut;

		return $this;
	}

	public function getHeureFin(): ?\DateTimeInterface
	{
		return $this->heureFin;
	}

	public function setHeureFin(\DateTimeInterface $heureFin): self
	{
		$this->heureFin = $heureFin;

		return $this;
	}

	public function getDatePermission(): ?\DateTimeInterface
	{
		return $this->datePermission;
	}

	public function setDatePermission(\DateTimeInterface $datePermission): self
	{
		$this->datePermission = $datePermission;

		return $this;
	}

	public function getEtatPermission(): ?string
	{
		return $this->etatPermission;
	}

	public function setEtatPermission(string $etatPermission): self
	{
		$this->etatPermission = $etatPermission;

		return $this;
	}

	public function getApprobationChef(): ?bool
	{
		return $this->approbationChef;
	}

	public function setApprobationChef(bool $approbationChef): self
	{
		$this->approbationChef = $approbationChef;

		return $this;
	}

	public function getUser(): ?User
	{
		return $this->user;
	}

	public function setUser(?User $user): self
	{
		$this->user = $user;

		return $this;
	}


}
