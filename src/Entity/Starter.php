<?php

namespace App\Entity;

use App\Repository\StarterRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use DateTime;
use DateTimeInterface;

#[ORM\Entity(repositoryClass: StarterRepository::class)]
#[Vich\Uploadable]
#[Assert\EnableAutoMapping]
class Starter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'starters')]
    #[ORM\JoinColumn(
        name:"menu_id",
        referencedColumnName: "id",
        onDelete: "SET NULL"
    )]
    private ?Menu $menu = null;

    #[ORM\Column(length: 255)]
    private ?string $picture = 'default_poster_value.svg';

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DatetimeInterface $updatedAt = null;

    #[Vich\UploadableField(mapping: 'starter', fileNameProperty: 'picture')]
    #[Assert\File(
        maxSize: '2M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
    )]
    private ?File $pictureFile = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private array $allergens = [];

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): static
    {
        $this->menu = $menu;

        return $this;
    }

    public function removeMenu(): void
    {
        if ($this->menu !== null) {
            $this->menu->removeStarter($this);
            $this->menu = null;
        }
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPictureFile(): ?File
    {
        return $this->pictureFile;
    }

    public function setAllergens(array $allergens): static
    {
        $this->allergens = $allergens;

        return $this;
    }

    public function setPictureFile(File $pictureFile = null): Starter
    {
        $this->pictureFile = $pictureFile;
        if ($pictureFile) {
            $this->updatedAt = new DateTime('now');
        }
        return $this;
    }

    public function getAllergens(): array
    {
        return $this->allergens;
    }
}
