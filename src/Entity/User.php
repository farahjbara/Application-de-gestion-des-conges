<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\UserReinitialiserMotDePasse;
use App\Controller\UserResettingPasswordRequest;
/**
 * @UniqueEntity("email" ,message="L'email que vous avez indiqué est déja utilisé!")
 * @UniqueEntity("cin" ,message="cin que vous avez indiqué est déja utilisé!")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository", repositoryClass=UserRepository::class)
 *
 * @ApiResource(
 *   formats={"json"},
 *    collectionOperations={
 *         "user_list"={
 *             "method"="GET",
 *             "path"="/users",
 *             "security"="is_granted('ROLE_CHEF_PROJET') or is_granted('ROLE_RH')",
 *             "security_message"="Vous n'avez pas les droits d'accéder"
 *         },
 *         "user_all"={
 *             "method"="GET",
 *             "path"="/init",
 *             "security_message"="Vous n'avez pas les droits d'accéder"
 *         },
 *         "user_new"={
 *             "method"="POST",
 *             "path"="/users",
 *             "denormalization_context"={"groups"={"write"}},
 *             "validation_groups"={"write"},
 *             "security_message"="Vous n'avez pas les droits d'accéder"
 *         }
 *     },
 *     itemOperations={
 *         "get"={
 *             "security"="is_granted('IS_AUTHENTICATED_REMEMBERED') and is_granted('VIEW', object)",
 *             "security_message"="Vous n'avez pas les droits d'accéder"
 *         },
 *         "put"={
 *             "denormalization_context"={"groups"={"edit"}},
 *             "validation_groups"={"edit"},
 *             "security"="is_granted('IS_AUTHENTICATED_REMEMBERED') and is_granted('EDIT', object)",
 *             "security_message"="Vous n'avez pas les droits d'accéder"
 *         },
 *         "user_mot_de_passe_oublie"={
 *             "method"="POST",
 *             "path"="/mot-de-passe-oublie",
 *             "controller"=UserResettingPasswordRequest::class,
 *             "formats"={"json"},
 *             "read"=false,
 *             "denormalization_context"={"groups"={"user:resetting-password-request"}},
 *             "validation_groups"={"resetting_password_request"},
 *             "security"="is_granted('IS_AUTHENTICATED_REMEMBERED') == false",
 *             "security_message"="Vous êtes déjà connecté !",
 *         },
 *         "user_reinitialiser_mot_de_passe"={
 *             "method"="POST",
 *             "path"="/reinitialiser-mot-de-passe",
 *             "controller"=UserReinitialiserMotDePasse::class,
 *             "read"=false,
 *             "denormalization_context"={"groups"={"user:resetting-password-reset"}},
 *             "validation_groups"={"resetting_password_reset"},
 *             "formats"={"json"},
 *             "security"="is_granted('IS_AUTHENTICATED_REMEMBERED') == false",
 *             "security_message"="Vous êtes déjà connecté",
 *         },
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
	 * @Assert\NotBlank(groups={"write", "resetting_password_request"})
	 * @Assert\Email(groups={"write", "resetting_password_request"})
	 * @Groups({"write", "edit", "user:resetting-password-request"})
	 * @ORM\Column(type="string", length=180, unique=true)
	 */
	private $email;

	/**
	 * @Groups({"write"})
	 * @ORM\Column(type="json")
	 */
	private $roles = [];

	/**
	 * @var string The hashed password,
	 * @ORM\Column(type="string")
	 *
	 */
	private $password;

	/**
	 * @Assert\NotBlank(groups={"write", "resetting_password_reset"})
	 * @Assert\Length(
	 *     min=8,
	 *     minMessage="Votre mot de passe doit faire minimum {{limit}} caractères",
	 *     groups={"write"}
	 *)
	 * @Groups({"write", "edit", "user:resetting-password-reset"})
	 */
	private $plainPassword;

	/**
	 *
	 * @Assert\NotBlank(groups={"write", "resetting_password_reset"})
	 * @Assert\EqualTo(
	 *     propertyPath="plainPassword", message="Vous n'avez pas tapé le même mot de passe",
	 *     groups={"write", "resetting_password_reset"}
	 * )
	 * @Groups({"write", "edit", "user:resetting-password-reset"})
	 */
	private $confirmPassword;

	/**
	 * @Assert\NotBlank(groups={"write", "edit"})
	 * @ORM\Column(type="string", length=8)
	 * @Groups({"write", "edit"})
	 */
	private $cin;

	/**
	 * @Assert\NotBlank(groups={"write"})
	 * @ORM\Column(type="string", length=255)
	 * @Groups({"write", "edit"})
	 */
	private $nom;

	/**
	 * @Assert\NotBlank(groups={"write"})
	 * @ORM\Column(type="string", length=255)
	 * @Groups({"write", "edit"})
	 */
	private $prenom;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @Groups({"write", "edit"})
	 */
	private $numTel;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank(groups={"write", "edit"})
	 * @Groups({"write", "edit"})
	 */
	private $fonction;

	/**
	 * @ORM\Column(type="boolean")
	 * @Groups({"write", "edit"})
	 */
	private $enabled;

	/**
	 * @Assert\NotBlank(groups={"write", "edit"})
	 * @Groups({"write", "edit"})
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $soldeAnnuel;

	/**
	 * @Assert\NotBlank(groups={"write", "edit"})
	 * @Groups({"write", "edit"})
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $nbrJrsPris;

	/**
	 * @Assert\NotBlank(groups={"write", "edit"})
	 * @Groups({"write", "edit"})
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $nbrJrsRestant;

	/**
	 * @ORM\Column(name="confirmation_token", type="string", length=180, unique=true, nullable=true)
	 */
	private $confirmationToken;


	public function __construct()
	{
		$this->enabled = true;
	}

	public function getId(): ?int
	{
		return $this->id;
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

	public function getEmail(): ?string
	{
		return $this->email;
	}

	public function setEmail(string $email): self
	{
		$this->email = $email;

		return $this;
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

	public function getSoldeAnnuel(): ?int
	{
		return $this->soldeAnnuel;
	}

	public function setSoldeAnnuel(int $soldeAnnuel): self
	{
		$this->soldeAnnuel = $soldeAnnuel;

		return $this;
	}

	public function getNbrJrsPris(): ?int
	{
		return $this->nbrJrsPris;
	}

	public function setNbrJrsPris(int $nbrJrsPris): self
	{
		$this->nbrJrsPris = $nbrJrsPris;

		return $this;
	}

	public function getNbrJrsRestant(): ?int
	{
		return $this->nbrJrsRestant;
	}

	public function setNbrJrsRestant(int $nbrJrsRestant): self
	{
		$this->nbrJrsRestant = $nbrJrsRestant;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getConfirmationToken()
	{
		return $this->confirmationToken;
	}

	/**
	 * @param mixed $confirmationToken
	 */
	public function setConfirmationToken($confirmationToken): void
	{
		$this->confirmationToken = $confirmationToken;
	}


}
