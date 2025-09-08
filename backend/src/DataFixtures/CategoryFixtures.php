<?php

namespace App\DataFixtures;
use App\Entity\Category;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
$faker = Faker\Factory::create();
        for ($i=0; $i <5 ; $i++) { 
            $category =(new Category())
            ->setTitle('categorie-'.$i)
            ->setUpdatedAt(new DateTimeImmutable())
            ->setCreatedAt(new DateTimeImmutable())
            ->setComments($faker->text(100));
            
            $manager->persist($category);
            $this->addReference('categorie'.$i, $category);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
