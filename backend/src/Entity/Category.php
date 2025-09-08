<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $title = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comments = null;

    /**
     * @var Collection<int, EntreDuChef>
     */
    #[ORM\OneToMany(targetEntity: EntreDuChef::class, mappedBy: 'categorie',cascade:['persist','remove'])]
    private Collection $entreDuChef;

    /**
     * @var Collection<int, PlatDuChef>
     */

  
    #[ORM\OneToMany(targetEntity: PlatDuChef::class, mappedBy: 'category')]
    
    private Collection $platDuChef;

    /**
     * @var Collection<int, DessertDuChef>
     */
    #[ORM\OneToMany(targetEntity: DessertDuChef::class, mappedBy: 'category',cascade:['persist','remove'])]
    private Collection $dessertDuChefs;

    public function __construct()
    {
        $this->entreDuChef = new ArrayCollection();
        $this->platDuChef = new ArrayCollection();
        $this->dessertDuChefs = new ArrayCollection();
    }

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

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

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): static
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @return Collection<int, EntreDuChef>
     */
    public function getEntreDuChef(): Collection
    {
        return $this->entreDuChef;
    }

    public function addEntreDuChef(EntreDuChef $entreDuChef): static
    {
        if (!$this->entreDuChef->contains($entreDuChef)) {
            $this->entreDuChef->add($entreDuChef);
            $entreDuChef->setCategorie($this);
        }

        return $this;
    }

    public function removeEntreDuChef(EntreDuChef $entreDuChef): static
    {
        if ($this->entreDuChef->removeElement($entreDuChef)) {
            // set the owning side to null (unless already changed)
            if ($entreDuChef->getCategorie() === $this) {
                $entreDuChef->setCategorie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PlatDuChef>
     */
    public function getPlatDuChef(): Collection
    {
        return $this->platDuChef;
    }

    public function addPlatDuChef(PlatDuChef $platDuChef): static
    {
        if (!$this->platDuChef->contains($platDuChef)) {
            $this->platDuChef->add($platDuChef);
            $platDuChef->setCategory($this);
        }

        return $this;
    }

    public function removePlatDuChef(PlatDuChef $platDuChef): static
    {
        if ($this->platDuChef->removeElement($platDuChef)) {
            // set the owning side to null (unless already changed)
            if ($platDuChef->getCategory() === $this) {
                $platDuChef->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DessertDuChef>
     */
    public function getDessertDuChefs(): Collection
    {
        return $this->dessertDuChefs;
    }

    public function addDessertDuChef(DessertDuChef $dessertDuChef): static
    {
        if (!$this->dessertDuChefs->contains($dessertDuChef)) {
            $this->dessertDuChefs->add($dessertDuChef);
            $dessertDuChef->setCategory($this);
        }

        return $this;
    }

    public function removeDessertDuChef(DessertDuChef $dessertDuChef): static
    {
        if ($this->dessertDuChefs->removeElement($dessertDuChef)) {
            // set the owning side to null (unless already changed)
            if ($dessertDuChef->getCategory() === $this) {
                $dessertDuChef->setCategory(null);
            }
        }

        return $this;
    }
}
