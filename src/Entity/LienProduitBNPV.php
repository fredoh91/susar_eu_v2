<?php

namespace App\Entity;

use App\Repository\LienProduitBNPVRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LienProduitBNPVRepository::class)]
class LienProduitBNPV
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $idCTLL = null;

    #[ORM\Column(nullable: true)]
    private ?int $idProduit = null;

    #[ORM\Column(nullable: true)]
    private ?int $IdInter_Sub_DMM = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Inter_Sub_DMM_substance = null;

    #[ORM\Column(nullable: true)]
    private ?int $id_susar_eu_int_sub_dmm = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $susar_eu_active_substance_high_level = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $substance_susar_eu_v1 = null;

    #[ORM\Column(nullable: true)]
    private ?bool $association_de_substances = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCTLL(): ?int
    {
        return $this->idCTLL;
    }

    public function setIdCTLL(?int $idCTLL): static
    {
        $this->idCTLL = $idCTLL;

        return $this;
    }

    public function getIdProduit(): ?int
    {
        return $this->idProduit;
    }

    public function setIdProduit(?int $idProduit): static
    {
        $this->idProduit = $idProduit;

        return $this;
    }

    public function getIdInterSubDMM(): ?int
    {
        return $this->IdInter_Sub_DMM;
    }

    public function setIdInterSubDMM(?int $IdInter_Sub_DMM): static
    {
        $this->IdInter_Sub_DMM = $IdInter_Sub_DMM;

        return $this;
    }

    public function getInterSubDMMSubstance(): ?string
    {
        return $this->Inter_Sub_DMM_substance;
    }

    public function setInterSubDMMSubstance(?string $Inter_Sub_DMM_substance): static
    {
        $this->Inter_Sub_DMM_substance = $Inter_Sub_DMM_substance;

        return $this;
    }

    public function getIdSusarEuIntSubDmm(): ?int
    {
        return $this->id_susar_eu_int_sub_dmm;
    }

    public function setIdSusarEuIntSubDmm(?int $id_susar_eu_int_sub_dmm): static
    {
        $this->id_susar_eu_int_sub_dmm = $id_susar_eu_int_sub_dmm;

        return $this;
    }

    public function getSusarEuActiveSubstanceHighLevel(): ?string
    {
        return $this->susar_eu_active_substance_high_level;
    }

    public function setSusarEuActiveSubstanceHighLevel(?string $susar_eu_active_substance_high_level): static
    {
        $this->susar_eu_active_substance_high_level = $susar_eu_active_substance_high_level;

        return $this;
    }

    public function getSubstanceSusarEuV1(): ?string
    {
        return $this->substance_susar_eu_v1;
    }

    public function setSubstanceSusarEuV1(?string $substance_susar_eu_v1): static
    {
        $this->substance_susar_eu_v1 = $substance_susar_eu_v1;

        return $this;
    }

    public function isAssociationDeSubstances(): ?bool
    {
        return $this->association_de_substances;
    }

    public function setAssociationDeSubstances(?bool $association_de_substances): static
    {
        $this->association_de_substances = $association_de_substances;

        return $this;
    }
}
