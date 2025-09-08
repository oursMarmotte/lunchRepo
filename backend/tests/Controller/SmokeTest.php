<?php
namespace App\Controller\Tests;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
class SmokeTest extends WebTestCase
{


    public function testApiDocUrlIsSuccessful():void{
        

        $client = static::createClient();
        $client->request('GET','api/doc');
        self::assertResponseIsSuccessful();
    

    }



    public function testApiAccountUrlIsSafe():void{
        $client = static::createClient();
        $client->request('GET','api/account/me');
        self::assertResponseStatusCodeSame(401);
    }


    public function testLoginRoute(){
        $client = static::createClient();
        $client->request('POST','api/login',
        [],
        [],
        [
            'Content-Type'=>'application/json',
        ],json_encode([
            'username'=>'john@rambo.us',
            'password'=>'vietnam'
        ])

    );
        $statusCode = $client->getResponse()->getStatusCode();
        

        self::assertResponseStatusCodeSame(401);
    }


       public function testApiRegistration(){
        $client = static::createClient();
        $client->followRedirects(false);
        $client->request('POST','api/registration',
        [],
        [],
        [
            'CONTENT_TYPE'=>'application/json',
        ],json_encode([
            'firstname'=>'john',
            'latsname'=>'rambo',
            'email'=>'john@rambo.us',
            'password'=>'vietnam'
        ],JSON_THROW_ON_ERROR)

    );
        $statusCode = $client->getResponse()->getStatusCode();
        
dd($statusCode);
       // self::assertResponseStatusCodeSame(200,$statusCode);
    }





}