<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MeddraMdHierarchyRepository;

#[ORM\Entity(repositoryClass: MeddraMdHierarchyRepository::class)]
#[Index(name: "idx_pt_name_en", columns: ["pt_name_en"])]

class MeddraMdHierarchy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $PtCode = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $HltCode = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $HlgtCode = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $SocCode = null;

    #[ORM\Column(length: 255)]
    private ?string $PtNameEn = null;

    #[ORM\Column(length: 255)]
    private ?string $PtNameFr = null;

    #[ORM\Column(length: 255)]
    private ?string $HltNameEn = null;

    #[ORM\Column(length: 255)]
    private ?string $HltNameFr = null;

    #[ORM\Column(length: 255)]
    private ?string $HlgtNameEn = null;

    #[ORM\Column(length: 255)]
    private ?string $HlgtNameFr = null;

    #[ORM\Column(length: 255)]
    private ?string $SocNameEn = null;

    #[ORM\Column(length: 255)]
    private ?string $SocNameFr = null;

    #[ORM\Column(length: 255)]
    private ?string $SocAbbrev = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $PtSocCode = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $PrimarySocFg = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPtCode(): ?string
    {
        return $this->PtCode;
    }

    public function setPtCode(string $PtCode): static
    {
        $this->PtCode = $PtCode;

        return $this;
    }

    public function getHltCode(): ?string
    {
        return $this->HltCode;
    }

    public function setHltCode(string $HltCode): static
    {
        $this->HltCode = $HltCode;

        return $this;
    }

    public function getHlgtCode(): ?string
    {
        return $this->HlgtCode;
    }

    public function setHlgtCode(string $HlgtCode): static
    {
        $this->HlgtCode = $HlgtCode;

        return $this;
    }

    public function getSocCode(): ?string
    {
        return $this->SocCode;
    }

    public function setSocCode(string $SocCode): static
    {
        $this->SocCode = $SocCode;

        return $this;
    }

    public function getPtNameEn(): ?string
    {
        return $this->PtNameEn;
    }

    public function setPtNameEn(string $PtNameEn): static
    {
        $this->PtNameEn = $PtNameEn;

        return $this;
    }

    public function getPtNameFr(): ?string
    {
        return $this->PtNameFr;
    }

    public function setPtNameFr(string $PtNameFr): static
    {
        $this->PtNameFr = $PtNameFr;

        return $this;
    }

    public function getHltNameEn(): ?string
    {
        return $this->HltNameEn;
    }

    public function setHltNameEn(string $HltNameEn): static
    {
        $this->HltNameEn = $HltNameEn;

        return $this;
    }

    public function getHltNameFr(): ?string
    {
        return $this->HltNameFr;
    }

    public function setHltNameFr(string $HltNameFr): static
    {
        $this->HltNameFr = $HltNameFr;

        return $this;
    }

    public function getHlgtNameEn(): ?string
    {
        return $this->HlgtNameEn;
    }

    public function setHlgtNameEn(string $HlgtNameEn): static
    {
        $this->HlgtNameEn = $HlgtNameEn;

        return $this;
    }

    public function getHlgtNameFr(): ?string
    {
        return $this->HlgtNameFr;
    }

    public function setHlgtNameFr(string $HlgtNameFr): static
    {
        $this->HlgtNameFr = $HlgtNameFr;

        return $this;
    }

    public function getSocNameEn(): ?string
    {
        return $this->SocNameEn;
    }

    public function setSocNameEn(string $SocNameEn): static
    {
        $this->SocNameEn = $SocNameEn;

        return $this;
    }

    public function getSocNameFr(): ?string
    {
        return $this->SocNameFr;
    }

    public function setSocNameFr(string $SocNameFr): static
    {
        $this->SocNameFr = $SocNameFr;

        return $this;
    }

    public function getSocAbbrev(): ?string
    {
        return $this->SocAbbrev;
    }

    public function setSocAbbrev(string $SocAbbrev): static
    {
        $this->SocAbbrev = $SocAbbrev;

        return $this;
    }

    public function getPtSocCode(): ?string
    {
        return $this->PtSocCode;
    }

    public function setPtSocCode(string $PtSocCode): static
    {
        $this->PtSocCode = $PtSocCode;

        return $this;
    }

    public function getPrimarySocFg(): ?string
    {
        return $this->PrimarySocFg;
    }

    public function setPrimarySocFg(?string $PrimarySocFg): static
    {
        $this->PrimarySocFg = $PrimarySocFg;

        return $this;
    }
}
