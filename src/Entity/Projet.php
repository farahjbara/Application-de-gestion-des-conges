<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProjetRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ProjetRepository::class)
 */
class Projet
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $id_projet;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_projet;

    /**
     * @ORM\Column(type="date")
     */
    private $Date_debut_projet;

    /**
     * @ORM\Column(type="date")
     */
    private $date_limite;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat_projet;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdProjet(): ?string
    {
        return $this->id_projet;
    }

    public function setIdProjet(string $id_projet): self
    {
        $this->id_projet = $id_projet;

        return $this;
    }

    public function getNomProjet(): ?string
    {
        return $this->nom_projet;
    }

    public function setNomProjet(string $nom_projet): self
    {
        $this->nom_projet = $nom_projet;

        return $this;
    }

    public function getDateDebutProjet(): ?\DateTimeInterface
    {
        return $this->Date_debut_projet;
    }

    public function setDateDebutProjet(\DateTimeInterface $Date_debut_projet): self
    {
        $this->Date_debut_projet = $Date_debut_projet;

        return $this;
    }

    public function getDateLimite(): ?\DateTimeInterface
    {
        return $this->date_limite;
    }

    public function setDateLimite(\DateTimeInterface $date_limite): self
    {
        $this->date_limite = $date_limite;

        return $this;
    }

    public function getEtatProjet(): ?string
    {
        return $this->etat_projet;
    }

    public function setEtatProjet(string $etat_projet): self
    {
        $this->etat_projet = $etat_projet;

        return $this;
    }
}
