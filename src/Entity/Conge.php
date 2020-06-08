<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CongeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CongeRepository::class)
 */
class Conge
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
    private $id_conge;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Solde_annuel;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbr_jrs_pris;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbr_jrs_restant;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat_conge;

    /**
     * @ORM\Column(type="boolean")
     */
    private $approbationChef;

    /**
     * @ORM\Column(type="boolean")
     */
    private $approbation_rh;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdConge(): ?string
    {
        return $this->id_conge;
    }

    public function setIdConge(string $id_conge): self
    {
        $this->id_conge = $id_conge;

        return $this;
    }

    public function getSoldeAnnuel(): ?string
    {
        return $this->Solde_annuel;
    }

    public function setSoldeAnnuel(string $Solde_annuel): self
    {
        $this->Solde_annuel = $Solde_annuel;

        return $this;
    }

    public function getNbrJrsPris(): ?int
    {
        return $this->nbr_jrs_pris;
    }

    public function setNbrJrsPris(int $nbr_jrs_pris): self
    {
        $this->nbr_jrs_pris = $nbr_jrs_pris;

        return $this;
    }

    public function getNbrJrsRestant(): ?int
    {
        return $this->nbr_jrs_restant;
    }

    public function setNbrJrsRestant(int $nbr_jrs_restant): self
    {
        $this->nbr_jrs_restant = $nbr_jrs_restant;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getEtatConge(): ?string
    {
        return $this->etat_conge;
    }

    public function setEtatConge(string $etat_conge): self
    {
        $this->etat_conge = $etat_conge;

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

    public function getApprobationRh(): ?bool
    {
        return $this->approbation_rh;
    }

    public function setApprobationRh(bool $approbation_rh): self
    {
        $this->approbation_rh = $approbation_rh;

        return $this;
    }
}
