<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;
use Doctrine\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route as AnnotationRoute;
use Symfony\Component\Routing\Attribute\Route;


#[Route('api/restaurant',name:'app_api_restaurant')]
class RestaurantController extends AbstractController
{

    public function __construct(private EntityManagerInterface $manager,private RestaurantRepository $repository )
    {
        
    }


    #[Route(name:'new',methods:'POST')]
   public function new():Response{

$restaurant = new Restaurant();

$restaurant->setName(name:'Restaurant vietnamien');
$restaurant->setDescription(description:'cette qualité et ce gout par le chef diem nhoc');
$restaurant->setMaxGuest(40);
$restaurant->setCreatedAt(new \DateTimeImmutable());
// a stocker enbase

// Tell Doctrine you want to (eventually) save the restaurant (no queries yet)
$this->manager->persist($restaurant);
// Actually executes the queries (i.e. the INSERT query)
$this->manager->flush();
return $this->json(['message '=>"Restaurant ressource  created with {$restaurant->getId()} id"],status: RESPONSE::HTTP_CREATED);
   }


   
   #[Route('/{id}',name:'show',methods:'GET')]
   public function show(int $id):Response{
    

    $restaurant = $this->repository->findOneBy(['id'=>$id]);
    if(! $restaurant){
        throw $this->createNotFoundException(("No restaurant found for {$id} id"));
    }

    return $this->json(['message'=>"Restaurant was found{$restaurant->getName()} for {$restaurant->getId()}id"]);
   }




   #[Route('/{id}',name:'edit',methods:'PUT')]
   public function edit(int $id):Response{
    $restaurant = $this->repository->findOneBy(['id'=>$id]);
    if(!$restaurant){
        throw $this->createNotFoundException("No restaurant found for {$id} id");
    }

    $restaurant->setName('Restaurant vietnamien le loại sóc');

    $this->manager->flush();

    return $this->redirectToRoute('app_api_restaurantshow', ['id' => $restaurant->getId()]);
   }



   #[Route('/{id}',name:'delete',methods: 'DELETE')]
   public function delete(int $id):Response{
    $restaurant = $this->repository->findOneBy(['id' => $id]);

if(!$restaurant){
    throw new \Exception(message:"no restaurant found for {$id} id");
    
   
   }
$this->manager->remove($restaurant);
$this->manager->flush();

   return $this->json(['message'=>'Restaurant resource deleted'],status:Response::HTTP_NO_CONTENT);
}

}
