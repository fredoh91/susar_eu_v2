<?php

namespace App\Entity;

use App\Repository\IntervenantSubstanceDMMRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IntervenantSubstanceDMMRepository::class)]
class IntervenantSubstanceDMM
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $DMM = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pole_long = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pole_court = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $evaluateur = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $active_substance_high_level = null;

    #[ORM\Column(nullable: true)]
    private ?bool $inactif = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type_saMS_Mono = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: SusarEU::class, inversedBy: 'intervenantSubstanceDMMs')]
    private Collection $susarEUs;

    #[ORM\OneToMany(targetEntity: IntervenantSubstanceDMMSubstance::class, mappedBy: 'IntervenantSubstanceDMM')]
    private Collection $intervenantSubstanceDMMSubstances;

    #[ORM\Column(nullable: true)]
    private ?bool $AssociationDeSubstances = null;

    #[ORM\ManyToOne(inversedBy: 'intervenantSubstanceDMMs')]
    private ?ActiveSubstanceGrouping $ActSubGrouping = null;

    #[ORM\ManyToOne(inversedBy: 'intervenantSubstanceDMMs')]
    private ?IntervenantsANSM $IntervenantANSM = null;

    // #[ORM\ManyToMany(targetEntity: SusarEU::class, mappedBy: 'IntervenantSubstanceDMM')]
    // private Collection $susarEUs;

    public function __construct()
    {
        // $this->susarEUs = new ArrayCollection();
        $this->intervenantSubstanceDMMSubstances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDMM(): ?string
    {
        return $this->DMM;
    }

    public function setDMM(?string $DMM): static
    {
        $this->DMM = $DMM;

        return $this;
    }

    public function getPoleLong(): ?string
    {
        return $this->pole_long;
    }

    public function setPoleLong(?string $pole_long): static
    {
        $this->pole_long = $pole_long;

        return $this;
    }

    public function getPoleCourt(): ?string
    {
        return $this->pole_court;
    }

    public function setPoleCourt(?string $pole_court): static
    {
        $this->pole_court = $pole_court;

        return $this;
    }

    public function getEvaluateur(): ?string
    {
        return $this->evaluateur;
    }

    public function setEvaluateur(?string $evaluateur): static
    {
        $this->evaluateur = $evaluateur;

        return $this;
    }

    public function getActiveSubstanceHighLevel(): ?string
    {
        return $this->active_substance_high_level;
    }

    public function setActiveSubstanceHighLevel(?string $active_substance_high_level): static
    {
        $this->active_substance_high_level = $active_substance_high_level;

        return $this;
    }

    public function isInactif(): ?bool
    {
        return $this->inactif;
    }

    public function setInactif(?bool $inactif): static
    {
        $this->inactif = $inactif;

        return $this;
    }

    public function getTypeSaMSMono(): ?string
    {
        return $this->type_saMS_Mono;
    }

    public function setTypeSaMSMono(?string $type_saMS_Mono): static
    {
        $this->type_saMS_Mono = $type_saMS_Mono;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    // /**
    //  * @return Collection<int, SusarEU>
    //  */
    // public function getSusarEUs(): Collection
    // {
    //     return $this->susarEUs;
    // }

    // public function addSusarEUs(SusarEU $susarEUs): static
    // {
    //     if (!$this->susarEUs->contains($susarEUs)) {
    //         $this->susarEUs->add($susarEUs);
    //         $susarEUs->addIntervenantSubstanceDMM($this);
    //     }

    //     return $this;
    // }

    // public function removeSusarEUs(SusarEU $susarEUs): static
    // {
    //     if ($this->susarEUs->removeElement($susarEUs)) {
    //         $susarEUs->removeIntervenantSubstanceDMM($this);
    //     }

    //     return $this;
    // }

    /**
     * @return Collection<int, SusarEU>
     */
    public function getSusarEUs(): Collection
    {
        return $this->susarEUs;
    }

    public function addSusarEUs(SusarEU $susarEUs): static
    {
        if (!$this->susarEUs->contains($susarEUs)) {
            $this->susarEUs->add($susarEUs);
        }

        return $this;
    }

    public function removeSusarEUs(SusarEU $susarEUs): static
    {
        $this->susarEUs->removeElement($susarEUs);

        return $this;
    }

    /**
     * @return Collection<int, IntervenantSubstanceDMMSubstance>
     */
    public function getIntervenantSubstanceDMMSubstances(): Collection
    {
        return $this->intervenantSubstanceDMMSubstances;
    }

    public function addIntervenantSubstanceDMMSubstance(IntervenantSubstanceDMMSubstance $intervenantSubstanceDMMSubstance): static
    {
        if (!$this->intervenantSubstanceDMMSubstances->contains($intervenantSubstanceDMMSubstance)) {
            $this->intervenantSubstanceDMMSubstances->add($intervenantSubstanceDMMSubstance);
            $intervenantSubstanceDMMSubstance->setIntervenantSubstanceDMM($this);
        }

        return $this;
    }

    public function removeIntervenantSubstanceDMMSubstance(IntervenantSubstanceDMMSubstance $intervenantSubstanceDMMSubstance): static
    {
        if ($this->intervenantSubstanceDMMSubstances->removeElement($intervenantSubstanceDMMSubstance)) {
            // set the owning side to null (unless already changed)
            if ($intervenantSubstanceDMMSubstance->getIntervenantSubstanceDMM() === $this) {
                $intervenantSubstanceDMMSubstance->setIntervenantSubstanceDMM(null);
            }
        }

        return $this;
    }

    public function isAssociationDeSubstances(): ?bool
    {
        return $this->AssociationDeSubstances;
    }

    public function setAssociationDeSubstances(?bool $AssociationDeSubstances): static
    {
        $this->AssociationDeSubstances = $AssociationDeSubstances;

        return $this;
    }

    public function getActSubGrouping(): ?ActiveSubstanceGrouping
    {
        return $this->ActSubGrouping;
    }

    public function setActSubGrouping(?ActiveSubstanceGrouping $ActSubGrouping): static
    {
        $this->ActSubGrouping = $ActSubGrouping;

        return $this;
    }

    public function getIntervenantANSM(): ?IntervenantsANSM
    {
        return $this->IntervenantANSM;
    }

    public function setIntervenantANSM(?IntervenantsANSM $IntervenantANSM): static
    {
        $this->IntervenantANSM = $IntervenantANSM;

        return $this;
    }
}
