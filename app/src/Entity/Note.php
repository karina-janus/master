<?php

/**
 * Note entity.
 */

namespace App\Entity;

use App\Repository\NoteRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
#[ORM\Table(name: 'notes')]
/**
 * Class Note.
 */
class Note
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null $title Title of the note
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    private ?string $title = null;

    /**
     * @var string|null $content Content of the note
     */
    #[ORM\Column(length: 2048)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 2048)]
    private ?string $content = null;

    /**
     * @var DateTimeImmutable|null $createdAt Date and time when the note was created
     */
    #[ORM\Column]
    #[Assert\Type(DateTimeImmutable::class)]
    private ?DateTimeImmutable $createdAt = null;

    /**
     * @var DateTimeImmutable|null $updatedAt Date and time when the note was last updated
     */
    #[ORM\Column]
    #[Assert\Type(DateTimeImmutable::class)]
    private ?DateTimeImmutable $updatedAt = null;

    /**
     * @var Category|null $category Category associated with the note
     */
    #[ORM\ManyToOne]
    #[Assert\Type(Category::class)]
    private ?Category $category = null;

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for Title.
     *
     * @return string|null Title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for Title.
     *
     * @param string|null $title Title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * Getter for Content.
     *
     * @return string|null Content
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Setter for Content.
     *
     * @param string|null $content Content
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    /**
     * Getter for CreatedAt.
     *
     * @return DateTimeImmutable|null CreatedAt
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Setter for CreatedAt.
     *
     * @param DateTimeImmutable|null $createdAt CreatedAt
     */
    public function setCreatedAt(?DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Getter for UpdatedAt.
     *
     * @return DateTimeImmutable|null UpdatedAt
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Setter for UpdatedAt.
     *
     * @param DateTimeImmutable|null $updatedAt UpdatedAt
     */
    public function setUpdatedAt(?DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Getter for Category.
     *
     * @return Category|null Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Setter for Category.
     *
     * @param Category|null $category Category
     */
    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }
}
