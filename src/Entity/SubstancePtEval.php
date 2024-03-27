<?php

namespace App\Entity;

use App\Repository\SubstancePtEvalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubstancePtEvalRepository::class)]
class SubstancePtEval
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Changes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $AssessmentOutcome = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Comments = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $DateEval = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userCreate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userModif = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: SubstancePt::class, inversedBy: 'substancePtEvals')]
    private Collection $substancePts;

    #[ORM\ManyToMany(targetEntity: SusarEU::class, inversedBy: 'substancePtEvals')]
    private Collection $susarEUs;

    public function __construct()
    {
        $this->substancePts = new ArrayCollection();
        $this->susarEUs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChanges(): ?string
    {
        return $this->Changes;
    }

    public function setChanges(?string $Changes): static
    {
        $this->Changes = $Changes;

        return $this;
    }

    public function getAssessmentOutcome(): ?string
    {
        return $this->AssessmentOutcome;
    }

    public function setAssessmentOutcome(?string $AssessmentOutcome): static
    {
        $this->AssessmentOutcome = $AssessmentOutcome;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->Comments;
    }

    public function setComments(?string $Comments): static
    {
        $this->Comments = $Comments;

        return $this;
    }

    public function getDateEval(): ?\DateTimeInterface
    {
        return $this->DateEval;
    }

    public function setDateEval(?\DateTimeInterface $DateEval): static
    {
        $this->DateEval = $DateEval;

        return $this;
    }

    public function getUserCreate(): ?string
    {
        return $this->userCreate;
    }

    public function setUserCreate(?string $userCreate): static
    {
        $this->userCreate = $userCreate;

        return $this;
    }

    public function getUserModif(): ?string
    {
        return $this->userModif;
    }

    public function setUserModif(?string $userModif): static
    {
        $this->userModif = $userModif;

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
     * @return Collection<int, SubstancePt>
     */
    public function getSubstancePts(): Collection
    {
        return $this->substancePts;
    }

    public function addSubstancePt(SubstancePt $substancePt): static
    {
        if (!$this->substancePts->contains($substancePt)) {
            $this->substancePts->add($substancePt);
        }

        return $this;
    }

    public function removeSubstancePt(SubstancePt $substancePt): static
    {
        $this->substancePts->removeElement($substancePt);

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
}
