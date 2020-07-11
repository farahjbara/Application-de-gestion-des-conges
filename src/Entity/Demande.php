<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DemandeRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\DemandeTraiter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass=DemandeRepository::class)
 *
 * @ApiResource(
 *   formats={"json"},
 *   collectionOperations={
 *         "get"={
 *            "security"="is_granted('IS_AUTHENTICATED_REMEMBERED')",
 *            "security_message"="Vous n'avez pas les droits d'accéder"
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
 *        "demande_accepter"={
 *             "method"="GET",
 *             "path"="/demandes/{id}/traiter/{valeur}",
 *             "controller"=DemandeTraiter::class,
 *         }
 *     },
 * )
 * @ApiFilter(SearchFilter::class, properties={"id": "exact", "typeConge": "exact", "etat":"exact", "user": "exact"})
 * @ApiFilter(DateFilter::class, properties={"dateDemande"})
 */
class Demande
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="date")
	 */
	private $dateDemande;

	/**
	 * @ORM\Column(type="date")
	 * @Assert\GreaterThan("+48 hours")
	 */
	private $dateDebut;

	/**
	 * @ORM\Column(type="date")
	 */
	private $dateFin;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $typeConge;

	/**
	 * @ORM\Column(type="object", nullable=true)
	 */
	private $fichierJoint;

	/**
	 * @ORM\ManyToOne(targetEntity=User::class)
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $user;

	/**
	 * @ORM\Column(type="string", length=30)
	 */
	private $etat;

	/**
	 * @Assert\Callback
	 */
	public function validate(ExecutionContextInterface $context, $payload)
	{

		if ($this->getDateFin() <= $this->getDateDebut())
			$context->buildViolation('Date fin doit être supérieure à date début.')
				->atPath('dateFin')
				->addViolation();

	}

	public function __construct()
	{
		$this->dateDemande = new \DateTime();
		$this->etat = 'en cours';
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getDateDemande(): ?\DateTimeInterface
	{
		return $this->dateDemande;
	}

	public function setDateDemande(\DateTimeInterface $dateDemande): self
	{
		$this->dateDemande = $dateDemande;

		return $this;
	}

	public function getDateDebut(): ?\DateTimeInterface
	{
		return $this->dateDebut;
	}

	public function setDateDebut(\DateTimeInterface $dateDebut): self
	{
		$this->dateDebut = $dateDebut;

		return $this;
	}

	public function getDateFin(): ?\DateTimeInterface
	{
		return $this->dateFin;
	}

	public function setDateFin(\DateTimeInterface $dateFin): self
	{
		$this->dateFin = $dateFin;

		return $this;
	}

	public function getTypeConge(): ?string
	{
		return $this->typeConge;
	}

	public function setTypeConge(string $typeConge): self
	{
		$this->typeConge = $typeConge;

		return $this;
	}

	public function getFichierJoint()
	{
		return $this->fichierJoint;
	}

	public function setFichierJoint($fichierJoint): self
	{
		$this->fichierJoint = $fichierJoint;

		return $this;
	}

	public function getEtat(): ?string
	{
		return $this->etat;
	}

	public function setEtat(string $etat): self
	{
		$this->etat = $etat;

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
