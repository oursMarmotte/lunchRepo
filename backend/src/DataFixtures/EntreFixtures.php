<?php

namespace App\DataFixtures;
use App\Entity\EntreDuChef;
use App\Entity\Category;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Exception;
use Faker;

class EntreFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        

        $faker = Faker\Factory::create();


        for ($i=0; $i <30 ; $i++) { 

        $categorie = $this->getReference('categorie'.random_int(0,4),Category::class);
        $entre = (new EntreDuChef())
        ->setNom($faker->name())
        ->setDescription($faker->text())
        ->setPrix(random_int(30,40))
        ->setCategorie($categorie)
    
        ->setQte(random_int(30,100));
         $manager->persist($entre);
         $this->addReference("entré".$i,$entre);
         
         ;
        
         
         
        }
        
        // $manager->persist($product);


        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CategoryFixtures::class];
    }
}
