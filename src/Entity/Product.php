<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @Vich\Uploadable
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"users", "products", "ads", "categories", "searchData", "transaction"})
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
     * @Groups({"users", "products", "ads", "categories", "searchData"})
     */
    private $title;

    /**
     * @Vich\UploadableField(mapping="image_product", fileNameProperty="picture")
     */
    private ?File $imageFile = null;

    /**
     * @ORM\Column(nullable="true")
     * @Groups({"users", "products", "ads", "categories", "searchData"})
     */
    private ?string $picture = null;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     * @Assert\Length(
     *      min = 3,
     *      max = 15,
     *      minMessage = "Nombre de caractère minimum {{ limit }}",
     *      maxMessage = "Nombre de caractère maximum {{ limit }}")
     * @Groups({"users", "products", "ads", "categories", "searchData"})
     */
    private $year;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Nombre de caractère maximum {{ limit }}")
     * @Groups({"users", "products", "ads", "categories", "searchData"})
     */
    private $serial_number;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="product")
     * @Groups({"products", "ads", "searchData", "transaction"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="product", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"users", "products", "ads", "searchData"})
     */
    private $category;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=false)
     * @Groups({"users", "products", "ads", "categories", "searchData"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups({"users", "products", "ads", "categories", "searchData"})
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=Ad::class, inversedBy="products", cascade={"remove"})
     * @Groups({"users", "products", "categories", "searchData"})
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

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updated_at = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setPicture(?string $picture): void
    {
        $this->picture = $picture;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
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

    public function getSerialNumber(): ?string
    {
        return $this->serial_number;
    }

    public function setSerialNumber(?string $serial_number): self
    {
        $this->serial_number = $serial_number;

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
