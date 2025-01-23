<?php

namespace App\Entity;

use App\Repository\ActiveSubstanceGroupingFicExcelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActiveSubstanceGroupingFicExcelRepository::class)]
class ActiveSubstanceGroupingFicExcel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateFichier = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $utilisateurImport = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fileName = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateImport = null;

    /**
     * @var Collection<int, ActiveSubstanceGrouping>
     */
    #[ORM\OneToMany(targetEntity: ActiveSubstanceGrouping::class, mappedBy: 'ficExcel')]
    private Collection $activeSubstanceGroupings;

    public function __construct()
    {
        $this->activeSubstanceGroupings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateFichier(): ?\DateTimeInterface
    {
        return $this->dateFichier;
    }

    public function setDateFichier(?\DateTimeInterface $dateFichier): static
    {
        $this->dateFichier = $dateFichier;

        return $this;
    }

    public function getUtilisateurImport(): ?string
    {
        return $this->utilisateurImport;
    }

    public function setUtilisateurImport(?string $utilisateurImport): static
    {
        $this->utilisateurImport = $utilisateurImport;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getDateImport(): ?\DateTimeInterface
    {
        return $this->dateImport;
    }

    public function setDateImport(?\DateTimeInterface $dateImport): static
    {
        $this->dateImport = $dateImport;

        return $this;
    }

    /**
     * @return Collection<int, ActiveSubstanceGrouping>
     */
    public function getActiveSubstanceGroupings(): Collection
    {
        return $this->activeSubstanceGroupings;
    }

    public function addActiveSubstanceGrouping(ActiveSubstanceGrouping $activeSubstanceGrouping): static
    {
        if (!$this->activeSubstanceGroupings->contains($activeSubstanceGrouping)) {
            $this->activeSubstanceGroupings->add($activeSubstanceGrouping);
            $activeSubstanceGrouping->setFicExcel($this);
        }

        return $this;
    }

    public function removeActiveSubstanceGrouping(ActiveSubstanceGrouping $activeSubstanceGrouping): static
    {
        if ($this->activeSubstanceGroupings->removeElement($activeSubstanceGrouping)) {
            // set the owning side to null (unless already changed)
            if ($activeSubstanceGrouping->getFicExcel() === $this) {
                $activeSubstanceGrouping->setFicExcel(null);
            }
        }

        return $this;
    }
}
