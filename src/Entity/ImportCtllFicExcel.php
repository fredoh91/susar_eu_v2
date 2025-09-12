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

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
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

    #[ORM\Column(nullable: true)]
    private ?int $nbLignesDataFicExcel = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbInsertedSusar = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbInsertedMedic = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbInsertedEffInd = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbInsertedMedHist = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbInsertedIndic = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbSusarAttribue = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbMedicAttribue = null;

    #[ORM\Column(nullable: true)]
    private ?array $gatewayDate = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbSusarNonAttribue = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $idNonAttribue = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 4, nullable: true)]
    private ?string $ExecutionTime = null;


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

    public function setNbLignesDataFicExcel(?int $nbLignesDataFicExcel): static
    {
        $this->nbLignesDataFicExcel = $nbLignesDataFicExcel;

        return $this;
    }

    public function getNbInsertedSusar(): ?int
    {
        return $this->nbInsertedSusar;
    }

    public function setNbInsertedSusar(?int $nbInsertedSusar): static
    {
        $this->nbInsertedSusar = $nbInsertedSusar;

        return $this;
    }

    public function getNbInsertedMedic(): ?int
    {
        return $this->nbInsertedMedic;
    }

    public function setNbInsertedMedic(?int $nbInsertedMedic): static
    {
        $this->nbInsertedMedic = $nbInsertedMedic;

        return $this;
    }

    public function getNbInsertedEffInd(): ?int
    {
        return $this->nbInsertedEffInd;
    }

    public function setNbInsertedEffInd(?int $nbInsertedEffInd): static
    {
        $this->nbInsertedEffInd = $nbInsertedEffInd;

        return $this;
    }

    public function getNbInsertedMedHist(): ?int
    {
        return $this->nbInsertedMedHist;
    }

    public function setNbInsertedMedHist(?int $nbInsertedMedHist): static
    {
        $this->nbInsertedMedHist = $nbInsertedMedHist;

        return $this;
    }

    public function getNbInsertedIndic(): ?int
    {
        return $this->nbInsertedIndic;
    }

    public function setNbInsertedIndic(?int $nbInsertedIndic): static
    {
        $this->nbInsertedIndic = $nbInsertedIndic;

        return $this;
    }

    public function getNbSusarAttribue(): ?int
    {
        return $this->nbSusarAttribue;
    }

    public function setNbSusarAttribue(?int $nbSusarAttribue): static
    {
        $this->nbSusarAttribue = $nbSusarAttribue;

        return $this;
    }

    public function getNbMedicAttribue(): ?int
    {
        return $this->nbMedicAttribue;
    }

    public function setNbMedicAttribue(?int $nbMedicAttribue): static
    {
        $this->nbMedicAttribue = $nbMedicAttribue;

        return $this;
    }

    public function getGatewayDate(): ?array
    {
        return $this->gatewayDate;
    }

    public function setGatewayDate(?array $gatewayDate): static
    {
        $this->gatewayDate = $gatewayDate;

        return $this;
    }

    public function getNbSusarNonAttribue(): ?int
    {
        return $this->nbSusarNonAttribue;
    }

    public function setNbSusarNonAttribue(?int $nbSusarNonAttribue): static
    {
        $this->nbSusarNonAttribue = $nbSusarNonAttribue;

        return $this;
    }

    public function getIdNonAttribue(): ?array
    {
        return $this->idNonAttribue;
    }

    public function setIdNonAttribue(?array $idNonAttribue): static
    {
        $this->idNonAttribue = $idNonAttribue;

        return $this;
    }

    public function getExecutionTime(): ?string
    {
        return $this->ExecutionTime;
    }

    public function setExecutionTime(?string $ExecutionTime): static
    {
        $this->ExecutionTime = $ExecutionTime;

        return $this;
    }

}
