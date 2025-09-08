<?php

namespace App\Controller;

use OpenApi\Attributes as OA;

use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;
use DateTimeImmutable;
use Doctrine\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\ORM\EntityManagerInterface;
use NumberFormatter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route as AnnotationRoute;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Exception\CircularReferenceException;


#[Route('api/restaurant',name:'app_api_restaurant_')]
class RestaurantController extends AbstractController
{

    public function __construct(private EntityManagerInterface $manager,
    private RestaurantRepository $repository,
    private SerializerInterface $serializer,
    private UrlGeneratorInterface $urlGenerator,
     )
    {
        
    }




    


    #[OA\Get(
      path:"/api/restaurant/list",
      summary:"Afficher tous les restaurants",

  )]



  #[OA\Response(
   response:200,
   description:'restaurants trouvé avec success',
   content:new OA\JsonContent(
       properties:[
           new OA\Property(property:'id',type:'int',example:'1'),
           new OA\Property(property:'name',type:'string',example:'nom du restaurant'),
           new OA\Property(property:'description',type:'string',example:'description'),
           new OA\Property(property:'créatedAt',type:'string',format:'date-time'),
           new OA\Property(property:'max_guest',type:'int',example:'nombre de convives')
       ]
         
   )
)]


#[OA\Response(
   response:404,
   description:'pas de restaurant'
   
)]





#[Route('/list',name:'all',methods:'GET')]

    public function allRestaurant(){

$restaurant = new Restaurant();

      $tabData = $this->repository->findAll($restaurant);

      if($tabData){
         $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function (object $object, ?string $format, array $context): string {
                if (!$object instanceof Restaurant) {
                    throw new CircularReferenceException('A circular reference has been detected when serializing the object of class "'.get_debug_type($object).'".');
                }
        
                // serialize the nested Organization with only the name (and not the members)
                return $object->getName();
            },
        ];




         $tabRestaurant = $this->serializer->serialize($tabData,'json',$context);

         return new JsonResponse($tabRestaurant,Response::HTTP_OK,[]);
      }
return new JsonResponse(['message'=>'le restaurant n\'existe pas'],Response::HTTP_NOT_FOUND);

    }




    #[OA\Post(
      path:"/api/restaurant",
      summary:"créer un restaurant"
      
  )]


    #[OA\RequestBody(
      required:true,
      description:"Données du restaurant à créer",
      content:new OA\JsonContent(
          properties:[
              new OA\Property(property:'nom',type:'string',example:'Nom du restaurant'),
              new OA\Property(property:'description',type:'string',example:'description du restaurant'),
            

          ]
      )
  )]
  
  
  #[OA\Response(
      response:201,
      description:'restaurant créé avec success',
      content:new OA\JsonContent(
          properties:[
              new OA\Property(property:'id',type:'int',example:'1'),
              new OA\Property(property:'name',type:'string',example:'nom du restaurant'),
              new OA\Property(property:'description',type:'string',example:'description'),
              new OA\Property(property:'créatedAt',type:'string',format:'date-time'),
              new OA\Property(property:'max_guest',type:'int',example:'nombre de convives')
          ]
            
      )
  )]

    #[Route(name:'new',methods:'POST')]
   public function new(Request $request):JsonResponse
   {
$restaurant = $this->serializer->deserialize($request->getContent(),Restaurant::class,'json');
$restaurant->setCreatedAt(new DateTimeImmutable());
$restaurant->setMaxGuest(30);


// Tell Doctrine you want to (eventually) save the restaurant (no queries yet)
$this->manager->persist($restaurant);
// Actually executes the queries (i.e. the INSERT query)
$this->manager->flush();
$responseData = $this->serializer->serialize($restaurant,format:'json');

$location = $this->urlGenerator->generate( 'app_api_restaurantshow',['id' => $restaurant->getId()], UrlGeneratorInterface::ABSOLUTE_URL,
); 

return new JsonResponse($responseData,Response::HTTP_CREATED , ["Location"=>$location], true);
   }



   #[OA\Get(
      path:"/api/restaurant/{id}",
      summary:"Afficher un restaurant par id",

  )]

  #[OA\Parameter(
   name: 'id',
   required: true,
   in: 'path',
   description:"ID du restaurant a afficher"
)]
#[OA\Schema(type:"integer")]

#[OA\Response(
   response:200,
   description:'restaurant trouvé avec success',
   content:new OA\JsonContent(
       properties:[
           new OA\Property(property:'id',type:'int',example:'1'),
           new OA\Property(property:'name',type:'string',example:'nom du restaurant'),
           new OA\Property(property:'description',type:'string',example:'description'),
           new OA\Property(property:'créatedAt',type:'string',format:'date-time'),
           new OA\Property(property:'max_guest',type:'int',example:'nombre de convives')
       ]
         
   )
)]

#[OA\Response(
   response:404,
   description:'restaurant n\'existe pas'
   
)]

   #[Route('/{id}',name:'show',methods:'GET')]
   public function show(int $id):JsonResponse{
    

    $restaurant = $this->repository->findOneBy(['id'=>$id]);
    if($restaurant){
      $context = [
         AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function (object $object, ?string $format, array $context): string {
             if (!$object instanceof Restaurant) {
                 throw new CircularReferenceException('A circular reference has been detected when serializing the object of class "'.get_debug_type($object).'".');
             }
     
             // serialize the nested Organization with only the name (and not the members)
             return $object->getName();
         },
     ];




        $responseData = $this->serializer->serialize($restaurant,'json',$context);
        return new JsonResponse($responseData,Response::HTTP_OK,[],true);

   }
return new JsonResponse(null,Response::HTTP_NOT_FOUND);

   }

   #[OA\Put(
      path:"/api/restaurant/{id}",
      summary:"modifier un restaurant par id",

  )]

  #[OA\Parameter(
   name: 'id',
   required: true,
   in: 'path',
   description:"ID du restaurant a modifier"
)]

#[OA\RequestBody(
   required:true,
   description:"Données du restaurant à modifier",
   content:new OA\JsonContent(
       properties:[
           new OA\Property(property:'name',type:'string',example:'Nom du restaurant'),
           new OA\Property(property:'description',type:'string',example:'description du restaurant'),
           new OA\Property(property:'max_guest',type:'int',example:'nombre de convives')
         

       ]
   )
)]

#[OA\Response(
   response:204,
   description:'restaurant modifié avec success',
   content:new OA\JsonContent(
       properties:[
           new OA\Property(property:'id',type:'int',example:'1'),
           new OA\Property(property:'name',type:'string',example:'nom du restaurant'),
           new OA\Property(property:'description',type:'string',example:'description'),
           new OA\Property(property:'créatedAt',type:'string',format:'date-time'),
           new OA\Property(property:'max_guest',type:'int',example:'nombre de convives')
       ]
         
   )
)]

#[OA\Response(
   response:404,
   description:'échec modification'
   
)]

   #[Route('/{id}',name:'edit',methods:'PUT')]
   public function edit(int $id,Request $request):Response{
    $restaurant = $this->repository->findOneBy(['id'=>$id]);
if($restaurant){
   $restaurant = $this->serializer->deserialize($request->getContent(),Restaurant::class,'json',[AbstractNormalizer::OBJECT_TO_POPULATE=>$restaurant]);
   $restaurant->setUpdatedat(new DateTimeImmutable());

   $this->manager->flush();
   return new JsonResponse(null,Response::HTTP_NO_CONTENT);
}
   

   
    return new JsonResponse(null,Response::HTTP_NOT_FOUND);
   }



   #[OA\Delete(
      path:"/api/restaurant/{id}",
      summary:"Supprimer un restaurant par id",

  )]

  #[OA\Parameter(
   name: 'id',
   required: true,
   in: 'path',
   description:"ID du restaurant a supprimer"
)]

#[OA\Response(
   response:204,
   description:'Supprimé'
   
)]

#[OA\Response(
   response:404,
   description:'échec suppression'
   
)]

   #[Route('/{id}',name:'delete',methods: 'DELETE')]
   public function delete(int $id):Response{
    $restaurant = $this->repository->findOneBy(['id' => $id]);

if($restaurant){
   $this->manager->remove($restaurant);
$this->manager->flush();
return new JsonResponse(null,Response::HTTP_NO_CONTENT);
   
   }
   return new JsonResponse(null,Response::HTTP_NOT_FOUND);
   
}


}
