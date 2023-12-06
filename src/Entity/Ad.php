<?php

namespace App\Entity;

use App\Repository\AdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AdRepository::class)
 */
class Ad
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
<<<<<<< HEAD
     * @Groups({"users", "ads", "products"})
=======
     * @Groups({"users", "categories", "ads"})
>>>>>>> 7940e1403944f848ce1dd967b88a7e17c32594a2
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 5,
     *      max = 5000,
     *      minMessage = "Nombre de caractère minimum {{ limit }}",
     *      maxMessage = "Nombre de caractère maximum {{ limit }}")
<<<<<<< HEAD
     * @Groups({"users", "ads", "products"})
=======
     * @Groups({"users", "categories", "ads"})
>>>>>>> 7940e1403944f848ce1dd967b88a7e17c32594a2
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Assert\NotBlank
     * @Assert\Positive
<<<<<<< HEAD
     * @Groups({"users", "ads", "products"})
=======
     * @Groups({"users", "categories", "ads"})
>>>>>>> 7940e1403944f848ce1dd967b88a7e17c32594a2
     */
    private $price;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Assert\NotBlank
<<<<<<< HEAD
     * @Groups({"users", "ads", "products"})
=======
     * @Groups({"users", "categories", "ads"})
>>>>>>> 7940e1403944f848ce1dd967b88a7e17c32594a2
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank
<<<<<<< HEAD
     * @Groups({"users", "ads", "products"})
=======
     * @Groups({"users", "categories", "ads"})
>>>>>>> 7940e1403944f848ce1dd967b88a7e17c32594a2
     */
    private $location;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=false)
<<<<<<< HEAD
     * @Groups({"users", "ads", "products"})
=======
     * @Groups({"users", "categories", "ads"})
>>>>>>> 7940e1403944f848ce1dd967b88a7e17c32594a2
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups({"users", "categories", "ads"})
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ad")
     * @Groups({"ads"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="ad")
     * @Groups({"ads"})
     */
    private $products;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="ads", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setAd($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getAd() === $this) {
                $product->setAd(null);
            }
        }

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
}
