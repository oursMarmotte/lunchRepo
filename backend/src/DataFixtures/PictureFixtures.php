<?php

namespace App\DataFixtures;
use App\Entity\Picture;
use App\Service\Utils;
use App\Entity\Restaurant;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker;

class PictureFixtures extends Fixture implements DependentFixtureInterface
{
    
    
    public function load(ObjectManager $manager): void
    {
        
$faker  = Faker\Factory::create();

        for ($i=0; $i < 20; $i++) { 
         $restaurant = $this->getReference('restaurant-'.random_int(1,19),Restaurant::class);
        // $manager->persist($product);
$title ="Article".$i;
        $picture = (new Picture())
        ->setTitle($title)
        ->setImgname($faker->imageUrl(400,400,'nature'))
        ->setSlug("slug".$i)
        ->setRestaurant($restaurant)
        ->setCreatedAt(new DateTimeImmutable());
        $manager->persist($picture);
        }
       

        $manager->flush();
    }

    public function getDependencies():array
    {
return [RestaurantFixtures::class];
    }
}
