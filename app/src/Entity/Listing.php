<?php

/**
 * Listing Entity.
 */

namespace App\Entity;

use App\Repository\ListingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Listing.
 */
#[ORM\Entity(repositoryClass: ListingRepository::class)]
#[ORM\Table(name: 'listing')]
class Listing
{
    /**
     * Primary Key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Title.
     */
    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 50)]
    private ?string $title = null;

    /**
     * Description.
     */
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Type('string')]
    #[Assert\Length(max: 500)]
    private ?string $description = null;

    /**
     * Created at datetime.
     */
    #[Assert\Type(\DateTimeImmutable::class)]
    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * Activated at datetime.
     */
    #[Assert\Type(\DateTimeImmutable::class)]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $activatedAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\Type(Category::class)]
    #[Assert\NotBlank]
    private ?Category $category = null;

    /**
     * Getter for id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for title.
     *
     * @return string|null Title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for title.
     *
     * @param string $title Title
     *
     * @return $this
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Getter for description.
     *
     * @return string|null Description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Setter for description.
     *
     * @param string $description Description
     *
     * @return $this
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Getter for created at datetime.
     *
     * @return \DateTimeImmutable|null Created at
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Setter for created at datetime.
     *
     * @param \DateTimeImmutable $createdAt Created at
     *
     * @return $this
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Getter for activated at datetime.
     *
     * @return \DateTimeImmutable|null Activated at
     */
    public function getActivatedAt(): ?\DateTimeImmutable
    {
        return $this->activatedAt;
    }

    /**
     * Setter for activated at datetime.
     *
     * @param \DateTimeImmutable|null $activatedAt Activated at
     *
     * @return $this
     */
    public function setActivatedAt(?\DateTimeImmutable $activatedAt): static
    {
        $this->activatedAt = $activatedAt;

        return $this;
    }

    /**
     * Getter for assigned Category.
     *
     * @return Category|null Category Entity or Null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Setter for Category.
     *
     * @param Category|null $category Category Entity
     *
     * @return $this
     */
    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }
}
