<?php

namespace App\Entity;

use Assets\Length;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields="username", message="Ce pseudo est déjà associé à un compte")
 * @UniqueEntity(fields="email", message="Cet e-mail est déjà associé à un compte")
 * @UniqueEntity(fields="phone_number", message="Ce numéro de téléphone est déjà associé à un compte")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface

{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"users", "products", "address", "ads", "searchData", "transaction", "categories"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64, nullable=false, unique=true)
     * @Groups({"users", "products", "address", "ads", "searchData"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=65, nullable=false)
     * @Assert\NotBlank
     * @Groups({"users", "products", "address", "ads"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=65, nullable=false)
     * @Assert\NotBlank
     * @Groups({"users", "products", "address", "ads"})
     */
    private $lastname;

    /**
     * @Vich\UploadableField(mapping="image_user_avatar", fileNameProperty="avatar")
     */
    private ?File $imageFileAvatar = null;

    /**
     * @ORM\Column(nullable="true")
     * @Groups({"users", "products", "address", "ads", "searchData"})
     */
    private ?string $avatar = null;

    /**
     * @Vich\UploadableField(mapping="image_user_banner", fileNameProperty="banner")
     */
    private ?File $imageFileBanner = null;

    /**
     * @ORM\Column(nullable="true")
     * @Groups({"users", "products", "address", "ads", "searchData"})
     */
    private ?string $banner = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     * @Groups({"users", "products", "address", "ads"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=15, nullable=false, unique=true)
     * @Groups({"users", "products", "address", "ads"})
     */
    private $phone_number;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(
     *      max = 5000,
     *      maxMessage = "Nombre de caractère maximum {{ limit }}")
     * @Groups({"users", "products", "address", "ads", "searchData"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=false)
     * @Groups({"users", "products", "address", "ads"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups({"users", "products", "address", "ads"})
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity=Address::class, mappedBy="user", cascade={"persist", "remove"})
     * @Groups({"users", "ads"})
     */
    private $addresses;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="user", cascade={"remove"})
     * @Groups({"users"})
     */
    private $product;

    /**
     * @ORM\OneToMany(targetEntity=Ad::class, mappedBy="user", cascade={"remove"})
     * @Groups({"users"})
     */
    private $ad;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\Regex("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^\w\d\s])\S{8,}$/")
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->product = new ArrayCollection();
        $this->ad = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFileAvatar
     */
    public function setimageFileAvatar(?File $imageFileAvatar = null): void
    {
        $this->imageFileAvatar = $imageFileAvatar;

        if (null !== $imageFileAvatar) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updated_at = new \DateTimeImmutable();
        }
    }

    public function getimageFileAvatar(): ?File
    {
        return $this->imageFileAvatar;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFileBanner
     */
    public function setimageFileBanner(?File $imageFileBanner = null): void
    {
        $this->imageFileBanner = $imageFileBanner;

        if (null !== $imageFileBanner) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updated_at = new \DateTimeImmutable();
        }
    }

    public function getimageFileBanner(): ?File
    {
        return $this->imageFileBanner;
    }

    public function getBanner(): ?string
    {
        return $this->banner;
    }

    public function setBanner(?string $banner): self
    {
        $this->banner = $banner;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
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

    /**
     * @return Collection<int, Address>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setUser($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getUser() === $this) {
                $address->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
            $product->setUser($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->product->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getUser() === $this) {
                $product->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ad>
     */
    public function getAd(): Collection
    {
        return $this->ad;
    }

    public function addAd(Ad $ad): self
    {
        if (!$this->ad->contains($ad)) {
            $this->ad[] = $ad;
            $ad->setUser($this);
        }

        return $this;
    }

    public function removeAd(Ad $ad): self
    {
        if ($this->ad->removeElement($ad)) {
            // set the owning side to null (unless already changed)
            if ($ad->getUser() === $this) {
                $ad->setUser(null);
            }
        }

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->created_at = new \DateTime();
    }
}
