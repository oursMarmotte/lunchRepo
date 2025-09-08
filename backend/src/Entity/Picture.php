<?php

namespace App\Entity;

use App\Repository\PictureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PictureRepository::class)]
class Picture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $title = null;

    #[ORM\Column(length: 64)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'pictures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Restaurant $restaurant = null;

    #[ORM\ManyToOne(inversedBy: 'pictures')]
    private ?EntreDuChef $entreChef = null;

    #[ORM\ManyToOne(inversedBy: 'pictures')]
    private ?DessertDuChef $dessertChef = null;

    #[ORM\Column(length: 255)]
    private ?string $imgname = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): static
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    public function getEntreChef(): ?EntreDuChef
    {
        return $this->entreChef;
    }

    public function setEntreChef(?EntreDuChef $entreChef): static
    {
        $this->entreChef = $entreChef;

        return $this;
    }

    public function getDessertChef(): ?DessertDuChef
    {
        return $this->dessertChef;
    }

    public function setDessertChef(?DessertDuChef $dessertChef): static
    {
        $this->dessertChef = $dessertChef;

        return $this;
    }

    public function getImgname(): ?string
    {
        return $this->imgname;
    }

    public function setImgname(string $imgname): static
    {
        $this->imgname = $imgname;

        return $this;
    }
}
