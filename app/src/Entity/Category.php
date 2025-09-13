<?php

/**
 * Category entity.
 */

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Category.
 */
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: 'category')]
#[ORM\UniqueConstraint(name: 'uq_category_title', columns: ['title'])]
class Category
{
    /**
     *  Primary Key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Title.
     */
    #[ORM\Column(length: 64)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 64)]
    private ?string $title = null;

    /**
     *  Getter for Id.
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
     * @return Category Title
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }
}
