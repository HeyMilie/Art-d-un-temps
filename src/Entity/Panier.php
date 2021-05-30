<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PanierRepository::class)
 */
class Panier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $membre_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix;

    /**
     * @ORM\Column(type="text")
     */
    private $quantite;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $image;

    /**
     * @ORM\OneToOne(targetEntity=Oeuvre::class, inversedBy="oeuvre", cascade={"persist", "remove"})
     */
    private $panier;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMembreId(): ?int
    {
        return $this->membre_id;
    }

    public function setMembreId(int $membre_id): self
    {
        $this->membre_id = $membre_id;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQuantite(): ?string
    {
        return $this->quantite;
    }

    public function setQuantite(string $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPanier(): ?Oeuvre
    {
        return $this->panier;
    }

    public function setPanier(?Oeuvre $panier): self
    {
        $this->panier = $panier;

        return $this;
    }
}
