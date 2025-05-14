<?php

namespace App\Entity;

use App\Repository\IntervenantsANSMRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IntervenantsANSMRepository::class)]
class IntervenantsANSM
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $DMM = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pole_court = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pole_long = null;

    #[ORM\Column]
    private ?bool $inactif = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $OrdreTri = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $DMM_pole_court = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $DMM_pole_long = null;

    /**
     * @var Collection<int, IntervenantSubstanceDMM>
     */
    #[ORM\OneToMany(targetEntity: IntervenantSubstanceDMM::class, mappedBy: 'IntervenantANSM')]
    private Collection $intervenantSubstanceDMMs;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $evaluateur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateDesactivation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $PoleTresCourt = null;

    public function __construct()
    {
        $this->intervenantSubstanceDMMs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDMM(): ?string
    {
        return $this->DMM;
    }

    public function setDMM(string $DMM): self
    {
        $this->DMM = $DMM;

        return $this;
    }

    public function getPoleCourt(): ?string
    {
        return $this->pole_court;
    }

    public function setPoleCourt(string $pole_court): self
    {
        $this->pole_court = $pole_court;

        return $this;
    }

    public function getPoleLong(): ?string
    {
        return $this->pole_long;
    }

    public function setPoleLong(string $pole_long): self
    {
        $this->pole_long = $pole_long;

        return $this;
    }

    public function isInactif(): ?bool
    {
        return $this->inactif;
    }

    public function setInactif(bool $inactif): self
    {
        $this->inactif = $inactif;

        return $this;
    }

    public function getOrdreTri(): ?int
    {
        return $this->OrdreTri;
    }

    public function setOrdreTri(int $OrdreTri): self
    {
        $this->OrdreTri = $OrdreTri;

        return $this;
    }

    public function getDMMPoleCourt(): ?string
    {
        return $this->DMM_pole_court;
    }

    public function setDMMPoleCourt(?string $DMM_pole_court): static
    {
        $this->DMM_pole_court = $DMM_pole_court;

        return $this;
    }

    public function getDMMPoleLong(): ?string
    {
        return $this->DMM_pole_long;
    }

    public function setDMMPoleLong(?string $DMM_pole_long): static
    {
        $this->DMM_pole_long = $DMM_pole_long;

        return $this;
    }

    /**
     * @return Collection<int, IntervenantSubstanceDMM>
     */
    public function getIntervenantSubstanceDMMs(): Collection
    {
        return $this->intervenantSubstanceDMMs;
    }

    public function addIntervenantSubstanceDMM(IntervenantSubstanceDMM $intervenantSubstanceDMM): static
    {
        if (!$this->intervenantSubstanceDMMs->contains($intervenantSubstanceDMM)) {
            $this->intervenantSubstanceDMMs->add($intervenantSubstanceDMM);
            $intervenantSubstanceDMM->setIntervenantANSM($this);
        }

        return $this;
    }

    public function removeIntervenantSubstanceDMM(IntervenantSubstanceDMM $intervenantSubstanceDMM): static
    {
        if ($this->intervenantSubstanceDMMs->removeElement($intervenantSubstanceDMM)) {
            // set the owning side to null (unless already changed)
            if ($intervenantSubstanceDMM->getIntervenantANSM() === $this) {
                $intervenantSubstanceDMM->setIntervenantANSM(null);
            }
        }

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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getDateDesactivation(): ?\DateTimeInterface
    {
        return $this->dateDesactivation;
    }

    public function setDateDesactivation(?\DateTimeInterface $dateDesactivation): static
    {
        $this->dateDesactivation = $dateDesactivation;

        return $this;
    }

    public function getPoleTresCourt(): ?string
    {
        return $this->PoleTresCourt;
    }

    public function setPoleTresCourt(?string $PoleTresCourt): static
    {
        $this->PoleTresCourt = $PoleTresCourt;

        return $this;
    }
}
