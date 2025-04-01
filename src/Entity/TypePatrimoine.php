<?php

namespace App\Entity;

use App\Repository\TypePatrimoineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypePatrimoineRepository::class)]
class TypePatrimoine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Patrimoine>
     */
    #[ORM\OneToMany(targetEntity: Patrimoine::class, mappedBy: 'typePatrimoine')]
    private Collection $nom_type_patrimoine;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function __construct()
    {
        $this->nom_type_patrimoine = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, patrimoine>
     */
    public function getNomTypePatrimoine(): Collection
    {
        return $this->nom_type_patrimoine;
    }

    public function addNomTypePatrimoine(Patrimoine $nomTypePatrimoine): static
    {
        if (!$this->nom_type_patrimoine->contains($nomTypePatrimoine)) {
            $this->nom_type_patrimoine->add($nomTypePatrimoine);
            $nomTypePatrimoine->setTypePatrimoine($this);
        }

        return $this;
    }

    public function removeNomTypePatrimoine(Patrimoine $nomTypePatrimoine): static
    {
        if ($this->nom_type_patrimoine->removeElement($nomTypePatrimoine)) {
            // set the owning side to null (unless already changed)
            if ($nomTypePatrimoine->getTypePatrimoine() === $this) {
                $nomTypePatrimoine->setTypePatrimoine(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
