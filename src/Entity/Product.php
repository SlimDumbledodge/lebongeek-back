<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"users", "products", "ads", "categories"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 5,
     *      max = 250,
     *      minMessage = "Nombre de caractère minimum {{ limit }}",
     *      maxMessage = "Nombre de caractère maximum {{ limit }}")
     * @Groups({"users", "products", "ads", "categories"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url
     * @Groups({"users", "products", "ads", "categories"})
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     * @Assert\Length(
     *      min = 3,
     *      max = 15,
     *      minMessage = "Nombre de caractère minimum {{ limit }}",
     *      maxMessage = "Nombre de caractère maximum {{ limit }}")
     * @Groups({"users", "products", "ads", "categories"})
     */
    private $year;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     *      minMessage = "Nombre de caractère minimum {{ limit }}",
     *      maxMessage = "Nombre de caractère maximum {{ limit }}")
     * @Groups({"users", "products", "ads", "categories"})
     */
    private $serie_number;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="product")
     * @Groups({"products", "ads"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="product", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"products", "ads"})
     */
    private $category;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=false)
     * @Groups({"users", "products", "ads", "categories"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups({"users", "products", "ads"})
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=Ad::class, inversedBy="products", cascade={"remove", "persist"})
     * @Groups({"products"})
     */
    private $ad;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(?string $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getSerieNumber(): ?int
    {
        return $this->serie_number;
    }

    public function setSerieNumber(?int $serie_number): self
    {
        $this->serie_number = $serie_number;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }
}
