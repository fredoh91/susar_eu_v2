<?php

namespace App\Entity;

use App\Repository\DMERepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DMERepository::class)]
class DME
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $llt_code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $llt_name_en = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $llt_name_fr = null;

    #[ORM\Column(nullable: true)]
    private ?int $pt_code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $llt_currency = null;

    #[ORM\Column(nullable: true)]
    private ?bool $inactif = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLltCode(): ?int
    {
        return $this->llt_code;
    }

    public function setLltCode(?int $llt_code): static
    {
        $this->llt_code = $llt_code;

        return $this;
    }

    public function getLltNameEn(): ?string
    {
        return $this->llt_name_en;
    }

    public function setLltNameEn(?string $llt_name_en): static
    {
        $this->llt_name_en = $llt_name_en;

        return $this;
    }

    public function getLltNameFr(): ?string
    {
        return $this->llt_name_fr;
    }

    public function setLltNameFr(?string $llt_name_fr): static
    {
        $this->llt_name_fr = $llt_name_fr;

        return $this;
    }

    public function getPtCode(): ?int
    {
        return $this->pt_code;
    }

    public function setPtCode(?int $pt_code): static
    {
        $this->pt_code = $pt_code;

        return $this;
    }

    public function getLltCurrency(): ?string
    {
        return $this->llt_currency;
    }

    public function setLltCurrency(?string $llt_currency): static
    {
        $this->llt_currency = $llt_currency;

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
}
