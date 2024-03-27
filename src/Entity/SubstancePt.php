<?php

namespace App\Entity;

use App\Repository\SubstancePtRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubstancePtRepository::class)]
class SubstancePt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 1000)]
    private ?string $active_substance_high_level = null;

    #[ORM\Column]
    private ?int $codereactionmeddrapt = null;

    #[ORM\Column(length: 255)]
    private ?string $reactionmeddrapt = null;

    #[ORM\ManyToMany(targetEntity: SusarEU::class, inversedBy: 'substancePts')]
    private Collection $susarEUs;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: SubstancePtEval::class, mappedBy: 'substancePts')]
    private Collection $substancePtEvals;

    public function __construct()
    {
        $this->susarEUs = new ArrayCollection();
        $this->substancePtEvals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActiveSubstanceHighLevel(): ?string
    {
        return $this->active_substance_high_level;
    }

    public function setActiveSubstanceHighLevel(string $active_substance_high_level): static
    {
        $this->active_substance_high_level = $active_substance_high_level;

        return $this;
    }

    public function getCodereactionmeddrapt(): ?int
    {
        return $this->codereactionmeddrapt;
    }

    public function setCodereactionmeddrapt(int $codereactionmeddrapt): static
    {
        $this->codereactionmeddrapt = $codereactionmeddrapt;

        return $this;
    }

    public function getReactionmeddrapt(): ?string
    {
        return $this->reactionmeddrapt;
    }

    public function setReactionmeddrapt(string $reactionmeddrapt): static
    {
        $this->reactionmeddrapt = $reactionmeddrapt;

        return $this;
    }

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, SubstancePtEval>
     */
    public function getSubstancePtEvals(): Collection
    {
        return $this->substancePtEvals;
    }

    public function addSubstancePtEval(SubstancePtEval $substancePtEval): static
    {
        if (!$this->substancePtEvals->contains($substancePtEval)) {
            $this->substancePtEvals->add($substancePtEval);
            $substancePtEval->addSubstancePt($this);
        }

        return $this;
    }

    public function removeSubstancePtEval(SubstancePtEval $substancePtEval): static
    {
        if ($this->substancePtEvals->removeElement($substancePtEval)) {
            $substancePtEval->removeSubstancePt($this);
        }

        return $this;
    }
}
