<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\OneToMany(
        mappedBy: 'menu',
        targetEntity: Starter::class,
        cascade:['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $starters;

    #[ORM\OneToMany(
        mappedBy: 'menu',
        targetEntity: Meal::class,
        cascade:['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $meals;

    #[ORM\OneToMany(
        mappedBy: 'menu',
        targetEntity: Dessert::class,
        cascade:['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $desserts;

    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: Comment::class)]
    private Collection $comments;

    public function __construct()
    {
        $this->starters = new ArrayCollection();
        $this->meals = new ArrayCollection();
        $this->desserts = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Starter>
     */
    public function getStarters(): Collection
    {
        return $this->starters;
    }

    public function addStarter(Starter $starter): static
    {
        if (!$this->starters->contains($starter)) {
            $this->starters->add($starter);
            $starter->setMenu($this);
        }

        return $this;
    }

    public function removeStarter(Starter $starter): static
    {
        if ($this->starters->removeElement($starter)) {
            // set the owning side to null (unless already changed)
            if ($starter->getMenu() === $this) {
                $starter->setMenu(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Meal>
     */
    public function getMeals(): Collection
    {
        return $this->meals;
    }

    public function addMeal(Meal $meal): static
    {
        if (!$this->meals->contains($meal)) {
            $this->meals->add($meal);
            $meal->setMenu($this);
        }

        return $this;
    }

    public function removeMeal(Meal $meal): static
    {
        if ($this->meals->removeElement($meal)) {
            // set the owning side to null (unless already changed)
            if ($meal->getMenu() === $this) {
                $meal->setMenu(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Dessert>
     */
    public function getDesserts(): Collection
    {
        return $this->desserts;
    }

    public function addDessert(Dessert $dessert): static
    {
        if (!$this->desserts->contains($dessert)) {
            $this->desserts->add($dessert);
            $dessert->setMenu($this);
        }

        return $this;
    }

    public function removeDessert(Dessert $dessert): static
    {
        if ($this->desserts->removeElement($dessert)) {
            // set the owning side to null (unless already changed)
            if ($dessert->getMenu() === $this) {
                $dessert->setMenu(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setMenu($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getMenu() === $this) {
                $comment->setMenu(null);
            }
        }

        return $this;
    }
}
