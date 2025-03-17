<?php

namespace App\Entity;

use App\Repository\AmortissementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AmortissementRepository::class)]
class Amortissement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Credit $num_credit = null;

    #[ORM\Column]
    private ?float $num_echeance = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_echeance = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $montant_amortissement = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $montant_interet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumCredit(): ?Credit
    {
        return $this->num_credit;
    }

    public function setNumCredit(?Credit $num_credit): static
    {
        $this->num_credit = $num_credit;

        return $this;
    }

    public function getNumEcheance(): ?float
    {
        return $this->num_echeance;
    }

    public function setNumEcheance(float $num_echeance): static
    {
        $this->num_echeance = $num_echeance;

        return $this;
    }

    public function getDateEcheance(): ?\DateTimeInterface
    {
        return $this->date_echeance;
    }

    public function setDateEcheance(\DateTimeInterface $date_echeance): static
    {
        $this->date_echeance = $date_echeance;

        return $this;
    }

    public function getMontantAmortissement(): ?string
    {
        return $this->montant_amortissement;
    }

    public function setMontantAmortissement(string $montant_amortissement): static
    {
        $this->montant_amortissement = $montant_amortissement;

        return $this;
    }

    public function getMontantInteret(): ?string
    {
        return $this->montant_interet;
    }

    public function setMontantInteret(string $montant_interet): static
    {
        $this->montant_interet = $montant_interet;

        return $this;
    }
}
