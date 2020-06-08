<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity("email" ,message="L'email que vous avez indiqué est déja utilisé!")
 * @UniqueEntity("cin" ,message="cin que vous avez indiqué est déja utilisé!")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository", repositoryClass=UserRepository::class)
 *
 * @ApiResource(
 *   formats={"json"},
 *   collectionOperations={
 *         "user_list"={
 *             "method"="GET",
 *             "path"="/users",
 *             "security"="is_granted('ROLE_ADMIN', 'ROLE_RH')",
 *             "security_message"="Vous n'avez pas les droits d'accéder"
 *         },
 *         "user_new"={
 *             "method"="POST",
 *             "path"="/users",
 *             "denormalization_context"={"groups"={"write"}},
 *             "security"="is_granted('ROLE_ADMIN', 'ROLE_RH')",
 *             "security_message"="Vous n'avez pas les droits d'accéder"
 *         }
 *     }
 * )
 */
class User implements UserInterface
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=180, unique=true)
	 */
	private $email;

	/**
	 * @ORM\Column(type="json")
	 */
	private $roles = [];

	/**
	 * @var string The hashed password
	 * @ORM\Column(type="string")
	 *
	 */
	private $password;

	/**
	 * @Assert\NotBlank(groups={"write"})
	 * @Assert\Length(
	 *     min=8,
	 *     minMessage="Votre mot de passe doit faire minimum {{limit}} caractères",
	 *     groups={"write"}
	 *    )
	 */
	private $plainPassword;

	/**
	 *
	 * @Assert\NotBlank(groups={"write"})
	 * @Assert\EqualTo(
	 *     propertyPath="plainPassword", message="Vous n'avez pas tapé le même mot de passe",
	 *     groups={"write"}
	 * )
	 */
	private $confirmPassword;

	/**
	 * @ORM\Column(type="string", length=8)
	 */
	private $cin;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $nom;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $prenom;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $numTel;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $fonction;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $enabled;

	/**
	 * @ORM\OneToMany(targetEntity=Demande::class, mappedBy="user", orphanRemoval=true)
	 */
	private $demandes;

	public function __construct()
	{
		$this->enabled = true;
		$this->demandes = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getEmail(): ?string
	{
		return $this->email;
	}

	public function setEmail(string $email): self
	{
		$this->email = $email;

		return $this;
	}

	/**
	 * A visual identifier that represents this user.
	 *
	 * @see UserInterface
	 */
	public function getUsername(): string
	{
		return (string)$this->email;
	}

	/**
	 * @see UserInterface
	 */
	public function getRoles(): array
	{
		$roles = $this->roles;
		// guarantee every user at least has ROLE_USER
		$roles[] = 'ROLE_USER';

		return array_unique($roles);
	}

	public function setRoles(array $roles): self
	{
		$this->roles = $roles;

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function getPassword(): string
	{
		return (string)$this->password;
	}

	public function setPassword(string $password): self
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function getSalt()
	{
		// not needed when using the "bcrypt" algorithm in security.yaml
	}

	/**
	 * @see UserInterface
	 */
	public function eraseCredentials()
	{
		// If you store any temporary, sensitive data on the user, clear it here
		// $this->plainPassword = null;
	}

	/**
	 * @return mixed
	 */
	public function getPlainPassword()
	{
		return $this->plainPassword;
	}

	/**
	 * @param mixed $plainPassword
	 */
	public function setPlainPassword($plainPassword): void
	{
		$this->plainPassword = $plainPassword;
	}

	public function getConfirmPassword()
	{
		return $this->confirmPassword;
	}


	public function setConfirmPassword($confirmPassword): void
	{
		$this->confirmPassword = $confirmPassword;
	}

	public function getCin(): ?string
	{
		return $this->cin;
	}

	public function setCin(string $cin): self
	{
		$this->cin = $cin;

		return $this;
	}

	public function getNom(): ?string
	{
		return $this->nom;
	}

	public function setNom(string $nom): self
	{
		$this->nom = $nom;

		return $this;
	}

	public function getPrenom(): ?string
	{
		return $this->prenom;
	}

	public function setPrenom(string $prenom): self
	{
		$this->prenom = $prenom;

		return $this;
	}

	public function getNumTel(): ?string
	{
		return $this->numTel;
	}

	public function setNumTel(string $numTel): self
	{
		$this->numTel = $numTel;

		return $this;
	}

	public function getFonction(): ?string
	{
		return $this->fonction;
	}

	public function setFonction(string $fonction): self
	{
		$this->fonction = $fonction;

		return $this;
	}

	public function getEnabled(): ?bool
	{
		return $this->enabled;
	}

	public function setEnabled(bool $enabled): self
	{
		$this->enabled = $enabled;

		return $this;
	}

	/**
	 * @return Collection|Demande[]
	 */
	public function getDemandes(): Collection
	{
		return $this->demandes;
	}

	public function addDemande(Demande $demande): self
	{
		if (!$this->demandes->contains($demande)) {
			$this->demandes[] = $demande;
			$demande->setUser($this);
		}

		return $this;
	}

	public function removeDemande(Demande $demande): self
	{
		if ($this->demandes->contains($demande)) {
			$this->demandes->removeElement($demande);
			// set the owning side to null (unless already changed)
			if ($demande->getUser() === $this) {
				$demande->setUser(null);
			}
		}

		return $this;
	}


}
