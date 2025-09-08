<?php

namespace App\Controller\Tests;

use App\Entity\Food;
use App\Form\FoodType;
use App\Repository\FoodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Panther\PantherTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/food')]
class FoodControllerTest extends WebTestCase
{

    public function testProductList(): void
    {
        $client = static::createClient([],[
            'HTTP_USER_AGENT'=>'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:83.0) Gecko/20100101 Firefox/83.0'
        ]);
        $crawler = $client->request('GET', '/food');
        
        $this->assertSelectorTextContains('h1', 'liste des plats');
$mealIdentifier =  $crawler->filter('.body')->text();
        $this->assertStringContainsString(
            'Ajouter un plat',
          $mealIdentifier
           
        );


        $mealNames = $crawler->filter('.meal-names');
        $this->assertSame(2,$mealNames->count());
        $this->assertSame('Dessert',$mealNames->first()->text());

       // $client->takeScreenshot('screenshot.png');
    }



    

   
}
