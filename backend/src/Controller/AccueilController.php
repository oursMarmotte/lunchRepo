<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
#[Route('/')]
public function home(): Response
{
return new Response(content:'Bienvenue sur la page d accueil ');
}

}