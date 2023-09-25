<?php

namespace App\DataFixtures;

use Faker;
use Bluemmb;
use Faker\Factory;
use App\Entity\Post;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostFixture extends Fixture
{   private $userPasswordHasherInterface;
    protected $slugger;
    public function __construct (UserPasswordHasherInterface $userPasswordHasherInterface, SluggerInterface $slugger ) 
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
        $this->slugger=$slugger;
    }
    public function load(ObjectManager $manager): void

    {   $faker = Factory::create();
        $faker->addProvider(new Bluemmb\Faker\PicsumPhotosProvider($faker));

        for ($i = 0; $i < 3; $i++) {
        $user=new User();
        $user->setLastName($faker->lastName);
        $user->setFirstName($faker->firstName);
        $user->setUserName($faker->firstName);
        $user->setEmail($faker->email);
        $user->setPassword(
            $this->userPasswordHasherInterface->hashPassword(
                $user, "Faker_user_Pass"
            )
        );
        $manager->persist($user);
       
        
    }
    for ($i = 0; $i < 5; $i++) {
        $categories=new Category();
        $categories->setName($faker->word);
        $categories->setDescription($faker->text);
        $categories->setCreatedAt(new DateTimeImmutable());
        $categories->setUpdatedAt(new DateTimeImmutable());
        $categories->setColor($faker->colorName);
        
        $manager->persist($categories);
       
    }
    for ($i = 0; $i < 100; $i++) {
        $post = new Post();
        $post->setTitle($faker->text(50));
        $post->setContent($faker->text);
        $post->setCreatedAt(new DateTimeImmutable());
        $post->setUpdatedAt(new DateTimeImmutable());
        $post->setUser($user);
        $post->addCategory($categories);
        $post->setImage($faker->imageUrl(600,400,true));
        $post->setSlug($this->slugger->slug($post->getTitle()));
        $manager->persist($post);

       
    }
    $manager->flush();
    }

}
