<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;
use DateTimeImmutable;
use Doctrine\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route as AnnotationRoute;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/restaurant',name:'app_api_restaurant')]
class RestaurantController extends AbstractController
{

    public function __construct(private EntityManagerInterface $manager,
    private RestaurantRepository $repository,
    private SerializerInterface $serializer,
    private UrlGeneratorInterface $urlGenerator,
     )
    {
        
    }


    #[Route(name:'new',methods:'POST')]
   public function new(Request $request):JsonResponse{
$restaurant = $this->serializer->deserialize($request->getContent(),Restaurant::class,'json');
$restaurant->setCreatedAt(new DateTimeImmutable());
$restaurant->setMaxGuest(30);


// Tell Doctrine you want to (eventually) save the restaurant (no queries yet)
$this->manager->persist($restaurant);
// Actually executes the queries (i.e. the INSERT query)
$this->manager->flush();


return new JsonResponse(data:null,status:Response::HTTP_CREATED,json: true);
   }


   
   #[Route('/{id}',name:'show',methods:'GET')]
   public function show(int $id):JsonResponse{
    

    $restaurant = $this->repository->findOneBy(['id'=>$id]);
    if($restaurant){
        $responseData = $this->serializer->serialize($restaurant,'json');
        return new JsonResponse($responseData,Response::HTTP_OK,[],true);

   }
return new JsonResponse(null,Response::HTTP_NOT_FOUND);

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
