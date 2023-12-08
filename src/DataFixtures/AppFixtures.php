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

        for ($i = 0; $i < 20; $i++) {

            $user = new User();

            $user->setUsername($faker->name());
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setAvatar($faker->imageUrl(640, 480, 'cats'));
            $user->setEmail($faker->email());
            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setPhoneNumber($faker->isbn10());
            $user->setRoles(['ROLE_USER']);
            
            $userList[] = $user;

            $manager->persist($user);

        }

        //! ADDRESS

        for ($j = 0; $j < 20; $j++) {

            $address = new Address();

            $address->setNameAddress($faker->streetName());
            $address->setStreetNumber(random_int(1, 22));
            $address->setStreet($faker->streetName());
            $address->setPostalCode($faker->postcode());
            $address->setCity($faker->city());
            $address->setCountry($faker->country());
            $address->setUser($faker->randomElement($userList));

            $manager->persist($address);
            
        }

        //! CATEGORY

        $categoryList = [];
        $allCategories = $faker->allCategories(); 

        foreach ($allCategories as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);

            $category->setImage($faker->imageUrl());

            $categoryList[] = $category; 
            $manager->persist($category);

        }

        //! AD

        $adList = [];

        for ($k = 0; $k < 20; $k++) {

            $ad = new Ad();
   
            $ad->setDescription($faker->text());
            $ad->setPrice($faker->randomFloat(2, 0, 1000));
            $ad->setDescription($faker->text());
            $ad->setState(rand(1,6));
            $ad->setLocation($faker->city());
            $ad->setCreatedAt(new \DateTimeImmutable());
            $ad->setCategory($faker->randomElement($categoryList));
            $ad->setUser($userList[$k]);

            $adList[] = $ad;
            
            $manager->persist($ad);
        }

        //! PRODUCT

        $productList = [];

        for ($l = 0; $l < 20; $l++) {

            $product = new Product();

            $product->setTitle($faker->title());
            $product->setPicture($faker->imageUrl(640, 480, 'dogs'));
            $product->setYear($faker->year());
            $product->setSerieNumber(rand(100, 30000));
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
