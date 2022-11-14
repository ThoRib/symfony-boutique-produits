<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $nom;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    /**
     * @ORM\Column(type="boolean")
     */
    private $rupture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lienImage;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Reference", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $reference;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Distributeur", inversedBy="produit", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $distributeur;

    /**
     * @ORM\ManyToMany(targetEntity=Fabricant::class, inversedBy="produits", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $fabricants;

// ~~~~~~~~~~~~~ CONSTRUCTEUR ~~~~~~~~~~~~ //

    public function __construct()
    {
        $this->distributeur = new ArrayCollection();
        $this->fabricants = new ArrayCollection();
    }

// ~~~~~~~~~~~ GETTER / SETTER ~~~~~~~~~~~ //

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function isRupture(): ?bool
    {
        return $this->rupture;
    }

    public function setRupture(bool $rupture): self
    {
        $this->rupture = $rupture;

        return $this;
    }

    public function getLienImage(): ?string
    {
        return $this->lienImage;
    }

    public function setLienImage(?string $lienImage): self
    {
        $this->lienImage = $lienImage;

        return $this;
    }

    public function getReference(): ?Reference
    {
        return $this->reference;
    }

    public function setReference(?Reference $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return Collection<int, Distributeur>
     */
    public function getDistributeur(): Collection
    {
        return $this->distributeur;
    }

    public function addDistributeur(Distributeur $distributeur): self
    {
        if (!$this->distributeur->contains($distributeur)) {
            $this->distributeur[] = $distributeur;
        }

        return $this;
    }

    public function removeDistributeur(Distributeur $distributeur): self
    {
        $this->distributeur->removeElement($distributeur);

        return $this;
    }

    /**
     * @return Collection<int, Fabricant>
     */
    public function getFabricants(): Collection
    {
        return $this->fabricants;
    }

    public function addFabricant(Fabricant $fabricant): self
    {
        if (!$this->fabricants->contains($fabricant)) {
            $this->fabricants[] = $fabricant;
        }

        return $this;
    }

    public function removeFabricant(Fabricant $fabricant): self
    {
        $this->fabricants->removeElement($fabricant);

        return $this;
    }
}
