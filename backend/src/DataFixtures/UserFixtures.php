<?php

namespace App\DataFixtures;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use FFI\Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class UserFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $passworHasher) {  }


    public function load(ObjectManager $manager): void

    {

        $faker = FaKer\Factory::create();
        // $product = new Product();
        // $manager->persist($product);

  for ($i=0; $i < 20 ; $i++) { 
    $user =  (new User())
    ->setFirstName($faker->firstName())
    ->setLastName($faker->lastName())
    ->setGuestNumber(random_int(0,10))
    ->setEmail($faker->email())
    ->setCreatedAt(new DateTimeImmutable());
    
    
    $user->setPassword($this->passworHasher->hashPassword($user,'Perpignan474$'));

    $manager->persist($user);
    


  }

        


        $manager->flush();
    }



}
