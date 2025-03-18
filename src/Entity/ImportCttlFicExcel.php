<?php

namespace App\Entity;

use App\Repository\ImportCttlFicExcelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImportCttlFicExcelRepository::class)]
class ImportCttlFicExcel
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
    #[ORM\OneToMany(targetEntity: ImportCtll::class, mappedBy: 'ImportCttlFicExcel')]
    private Collection $importCtlls;

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
            $importCtll->setImportCttlFicExcel($this);
        }

        return $this;
    }

    public function removeImportCtll(ImportCtll $importCtll): static
    {
        if ($this->importCtlls->removeElement($importCtll)) {
            // set the owning side to null (unless already changed)
            if ($importCtll->getImportCttlFicExcel() === $this) {
                $importCtll->setImportCttlFicExcel(null);
            }
        }

        return $this;
    }
}
