<?php

namespace App\Entity;

use App\Repository\ImportCtllFicExcelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImportCtllFicExcelRepository::class)]
class ImportCtllFicExcel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateFichier = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $utilisateurImport = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fileName = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateImport = null;

    /**
     * @var Collection<int, ImportCtll>
     */
    #[ORM\OneToMany(targetEntity: ImportCtll::class, mappedBy: 'ImportCtllFicExcel')]
    private Collection $importCtlls;

    #[ORM\Column]
    private ?int $nbLignesDataFicExcel = null;

    public function __construct()
    {
        $this->importCtlls = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateFichier(): ?\DateTimeInterface
    {
        return $this->dateFichier;
    }

    public function setDateFichier(\DateTimeInterface $dateFichier): static
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
     * @return Collection<int, ImportCtll>
     */
    public function getImportCtlls(): Collection
    {
        return $this->importCtlls;
    }

    public function addImportCtll(ImportCtll $importCtll): static
    {
        if (!$this->importCtlls->contains($importCtll)) {
            $this->importCtlls->add($importCtll);
            $importCtll->setImportCtllFicExcel($this);
        }

        return $this;
    }

    public function removeImportCtll(ImportCtll $importCtll): static
    {
        if ($this->importCtlls->removeElement($importCtll)) {
            // set the owning side to null (unless already changed)
            if ($importCtll->getImportCtllFicExcel() === $this) {
                $importCtll->setImportCtllFicExcel(null);
            }
        }

        return $this;
    }

    public function getNbLignesDataFicExcel(): ?int
    {
        return $this->nbLignesDataFicExcel;
    }

    public function setNbLignesDataFicExcel(int $nbLignesDataFicExcel): static
    {
        $this->nbLignesDataFicExcel = $nbLignesDataFicExcel;

        return $this;
    }
}
