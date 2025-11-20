<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Address;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\DataFixtures\Provider\CategoryProvider;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {


        $faker = Factory::create('fr_FR');

        $faker->addProvider(new CategoryProvider());

        //!  USER

        $userList = [];

        for ($i = 0; $i < 40; $i++) {

            $user = new User();

            $user->setUsername($faker->userName());
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setAvatar($faker->imageUrl(640, 480, 'people'));
            $user->setBanner($faker->imageUrl(800, 800, 'people'));
            $user->setEmail($faker->unique()->safeEmail());
            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setPhoneNumber((string)$faker->numerify('06########'));
            $user->setRoles(['ROLE_USER']);

            $userList[] = $user;

            $manager->persist($user);
        }

        //! ADDRESS

        for ($j = 0; $j < 20; $j++) {

            $address = new Address();

            $address->setNameAddress($faker->streetName());
            $address->setStreetNumber((string)random_int(1, 200));
            $address->setStreet($faker->streetName());
            $address->setPostalCode($faker->postcode());
            $address->setCity($faker->city());
            $address->setCountry($faker->country());
            $address->setUser($faker->randomElement($userList));

            $manager->persist($address);
        }

        //! CATEGORY

        $categoryProvider = new CategoryProvider();
        $allCategories = $categoryProvider->allCategories();
        $categoryList = [];

        foreach ($allCategories as $categoryData) {
            $category = new Category();
            $category->setName($categoryData['name']);
            $category->setImage($categoryData['image']);

            $categoryList[] = $category;

            $manager->persist($category);
        }

        //! AD

        $adList = [];

        for ($k = 0; $k < 40; $k++) {

            $ad = new Ad();

            $ad->setTitle($faker->sentence(3, true));
            $ad->setDescription($faker->paragraph());
            $ad->setPrice(random_int(5, 1500));
            $ad->setState(rand(1, 6));
            $ad->setLocation($faker->city());
            $ad->setCreatedAt(new \DateTimeImmutable());
            $ad->setCategory($faker->randomElement($categoryList));
            $ad->setUser($userList[$k]);

            $adList[] = $ad;

            $manager->persist($ad);
        }

        //! PRODUCT

        $productList = [];

        for ($l = 0; $l < 40; $l++) {

            $product = new Product();

            $product->setTitle($faker->words(3, true));
            $product->setYear($faker->year());
            $product->setSerialNumber((string)rand(100, 30000));
            $product->setCreatedAt(new \DateTimeImmutable());
            $product->setCategory($faker->randomElement($categoryList));
            $product->setUser($userList[$l]);
            $product->setAd($adList[$l]);

            $productList[] = $product;

            $manager->persist($product);
        }

        $manager->flush();
    }
}
