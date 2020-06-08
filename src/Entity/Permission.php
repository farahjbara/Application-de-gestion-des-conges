<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PermissionRepository;
use Doctrine\ORM\Mapping as ORM;


  /**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=PermissionRepository::class)
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
     */
    private $heure_debut;

    /**
     * @ORM\Column(type="time")
     */
    private $heure_fin;

    /**
     * @ORM\Column(type="date")
     */
    private $date_permission;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat_permission;

    /**
     * @ORM\Column(type="boolean")
     */
    private $approbation_chef;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeureDebut(): ?\DateTimeInterface
    {
        return $this->heure_debut;
    }

    public function setHeureDebut(\DateTimeInterface $heure_debut): self
    {
        $this->heure_debut = $heure_debut;

        return $this;
    }

    public function getHeureFin(): ?\DateTimeInterface
    {
        return $this->heure_fin;
    }

    public function setHeureFin(\DateTimeInterface $heure_fin): self
    {
        $this->heure_fin = $heure_fin;

        return $this;
    }

    public function getDatePermission(): ?\DateTimeInterface
    {
        return $this->date_permission;
    }

    public function setDatePermission(\DateTimeInterface $date_permission): self
    {
        $this->date_permission = $date_permission;

        return $this;
    }

    public function getEtatPermission(): ?string
    {
        return $this->etat_permission;
    }

    public function setEtatPermission(string $etat_permission): self
    {
        $this->etat_permission = $etat_permission;

        return $this;
    }

    public function getApprobationChef(): ?bool
    {
        return $this->approbation_chef;
    }

    public function setApprobationChef(bool $approbation_chef): self
    {
        $this->approbation_chef = $approbation_chef;

        return $this;
    }
}
