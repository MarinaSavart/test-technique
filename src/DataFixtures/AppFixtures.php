<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    
    public function load(ObjectManager $manager): void
    {
        // Création d'un user "normal"
        $user = new User();
        $user->setUsername("Jon");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "123"));
        $manager->persist($user);
        
        // Création d'un user admin
        $userAdmin = new User();
        $userAdmin->setUsername("Doe");
        $userAdmin->setRoles(["ROLE_ADMIN"]);
        $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "123"));
        $manager->persist($userAdmin);

        
        // Création de 5 categories
        $listCategory = [];
        for ($i=0; $i < 5; $i++) { 
            $category = new Category;
            $category->setName('Category ' . $i);
            $manager->persist($category);

            $listCategory[] = $category;
        }

        $manager->flush();
    }
}
