<?php

namespace App\Entity;

use App\Repository\EffetsIndesirablesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EffetsIndesirablesRepository::class)]
class EffetsIndesirables
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $master_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $caseid = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $specificcaseid = null;

    #[ORM\Column(nullable: true)]
    private ?int $DLPVersion = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $reactionstartdate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reactionmeddrallt = null;

    #[ORM\Column(nullable: true)]
    private ?int $codereactionmeddrallt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reactionmeddrapt = null;

    #[ORM\Column(nullable: true)]
    private ?int $codereactionmeddrapt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reactionmeddrahlt = null;

    #[ORM\Column(nullable: true)]
    private ?int $codereactionmeddrahlt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reactionmeddrahlgt = null;

    #[ORM\Column(nullable: true)]
    private ?int $codereactionmeddrahlgt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $soc = null;

    #[ORM\Column(nullable: true)]
    private ?int $reactionmeddrasoc = null;

    #[ORM\ManyToOne(inversedBy: 'EffetsIndesirables')]
    private ?SusarEU $susar = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $ReactionListPT_CTLL = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ReactionListPT = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Outcome = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Date_FormatDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Duration = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMasterId(): ?int
    {
        return $this->master_id;
    }

    public function setMasterId(int $master_id): self
    {
        $this->master_id = $master_id;

        return $this;
    }

    public function getCaseid(): ?int
    {
        return $this->caseid;
    }

    public function setCaseid(int $caseid): self
    {
        $this->caseid = $caseid;

        return $this;
    }

    public function getSpecificcaseid(): ?string
    {
        return $this->specificcaseid;
    }

    public function setSpecificcaseid(string $specificcaseid): self
    {
        $this->specificcaseid = $specificcaseid;

        return $this;
    }

    public function getDLPVersion(): ?int
    {
        return $this->DLPVersion;
    }

    public function setDLPVersion(int $DLPVersion): self
    {
        $this->DLPVersion = $DLPVersion;

        return $this;
    }

    public function getReactionstartdate(): ?\DateTimeInterface
    {
        return $this->reactionstartdate;
    }

    public function setReactionstartdate(?\DateTimeInterface $reactionstartdate): self
    {
        $this->reactionstartdate = $reactionstartdate;

        return $this;
    }

    public function getReactionmeddrallt(): ?string
    {
        return $this->reactionmeddrallt;
    }

    public function setReactionmeddrallt(?string $reactionmeddrallt): self
    {
        $this->reactionmeddrallt = $reactionmeddrallt;

        return $this;
    }

    public function getCodereactionmeddrallt(): ?int
    {
        return $this->codereactionmeddrallt;
    }

    public function setCodereactionmeddrallt(?int $codereactionmeddrallt): self
    {
        $this->codereactionmeddrallt = $codereactionmeddrallt;

        return $this;
    }

    public function getReactionmeddrapt(): ?string
    {
        return $this->reactionmeddrapt;
    }

    public function setReactionmeddrapt(?string $reactionmeddrapt): self
    {
        $this->reactionmeddrapt = $reactionmeddrapt;

        return $this;
    }

    public function getCodereactionmeddrapt(): ?int
    {
        return $this->codereactionmeddrapt;
    }

    public function setCodereactionmeddrapt(?int $codereactionmeddrapt): self
    {
        $this->codereactionmeddrapt = $codereactionmeddrapt;

        return $this;
    }

    public function getReactionmeddrahlt(): ?string
    {
        return $this->reactionmeddrahlt;
    }

    public function setReactionmeddrahlt(?string $reactionmeddrahlt): self
    {
        $this->reactionmeddrahlt = $reactionmeddrahlt;

        return $this;
    }

    public function getCodereactionmeddrahlt(): ?int
    {
        return $this->codereactionmeddrahlt;
    }

    public function setCodereactionmeddrahlt(?int $codereactionmeddrahlt): self
    {
        $this->codereactionmeddrahlt = $codereactionmeddrahlt;

        return $this;
    }

    public function getReactionmeddrahlgt(): ?string
    {
        return $this->reactionmeddrahlgt;
    }

    public function setReactionmeddrahlgt(?string $reactionmeddrahlgt): self
    {
        $this->reactionmeddrahlgt = $reactionmeddrahlgt;

        return $this;
    }

    public function getCodereactionmeddrahlgt(): ?int
    {
        return $this->codereactionmeddrahlgt;
    }

    public function setCodereactionmeddrahlgt(?int $codereactionmeddrahlgt): self
    {
        $this->codereactionmeddrahlgt = $codereactionmeddrahlgt;

        return $this;
    }

    public function getSoc(): ?string
    {
        return $this->soc;
    }

    public function setSoc(?string $soc): self
    {
        $this->soc = $soc;

        return $this;
    }

    public function getReactionmeddrasoc(): ?int
    {
        return $this->reactionmeddrasoc;
    }

    public function setReactionmeddrasoc(?int $reactionmeddrasoc): self
    {
        $this->reactionmeddrasoc = $reactionmeddrasoc;

        return $this;
    }

    public function getSusar(): ?SusarEU
    {
        return $this->susar;
    }

    public function setSusar(?SusarEU $susar): self
    {
        $this->susar = $susar;

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

    public function getReactionListPTCTLL(): ?string
    {
        return $this->ReactionListPT_CTLL;
    }

    public function setReactionListPTCTLL(?string $ReactionListPT_CTLL): static
    {
        $this->ReactionListPT_CTLL = $ReactionListPT_CTLL;

        return $this;
    }

    public function getReactionListPT(): ?string
    {
        return $this->ReactionListPT;
    }

    public function setReactionListPT(?string $ReactionListPT): static
    {
        $this->ReactionListPT = $ReactionListPT;

        return $this;
    }

    public function getOutcome(): ?string
    {
        return $this->Outcome;
    }

    public function setOutcome(?string $Outcome): static
    {
        $this->Outcome = $Outcome;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->Date;
    }

    public function setDate(?string $Date): static
    {
        $this->Date = $Date;

        return $this;
    }

    public function getDateFormatDate(): ?\DateTimeInterface
    {
        return $this->Date_FormatDate;
    }

    public function setDateFormatDate(?\DateTimeInterface $Date_FormatDate): static
    {
        $this->Date_FormatDate = $Date_FormatDate;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->Duration;
    }

    public function setDuration(?string $Duration): static
    {
        $this->Duration = $Duration;

        return $this;
    }
}
