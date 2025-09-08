<?php

namespace App\Controller;
use OpenApi\Attributes as OA;
use App\Entity\Booking;
use App\Entity\Restaurant;
use App\Entity\User;
use App\Repository\BookingRepository;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

 class BookingController extends AbstractController
{

    public function __construct(private EntityManagerInterface $manager,
    private BookingRepository $repository,
    private SerializerInterface $serializer,
    private UrlGeneratorInterface $generator,
    private RestaurantRepository $restaurantRepo) {
    
    }

    
    #[OA\Get(
        path:"/api/booking",
        summary:"liste des réservations"
        
    )]


    #[Route('/api/booking', name: 'app_booking',methods:'GET')]
    public function index(): Response
    {
        $resa = new Booking();
      $tabResa =  $this->repository->findAll(['reservations'=>$resa]);

        return $this->render('booking/index.html.twig', [
            'controller_name' => $tabResa,
        ]);
    }

    #[OA\Post(
        path:"/api/booking/new",
        summary:"Ajouter une réservation"
        
    )]

    #[OA\RequestBody(
        required:true,
        description:"Données de la réservation à créer",
        content:new OA\JsonContent(
            properties:[
                new OA\Property(property:'guest_number',type:'int',example:'Nombre de convives'),
                new OA\Property(property:'order_date',type:'string',example:'Date de réservation'),
                new OA\Property(property:'allergies',type:'string',example:'cacahuetes'),
              
  
            ]
        )
    )]


  

    #[Route('/api/booking/new/{id}',name:'app_booking_new',methods:'POST')]
    public function new(Request $request,$id){
$resa = $this->serializer->deserialize($request->getContent(),Booking::class,'json');

if($resa){
$restaurant = $this->restaurantRepo->findOneBy(['id'=>$id]);

   $resa->setClient($this->getUser());

$resa->setRestaurant($restaurant);
    $resa->setCreatedAt(new \DateTimeImmutable);
    $resa->setUpdatedAt(new \DateTimeImmutable);
    $this->manager->persist($resa);
    $this->manager->flush();
    return new JsonResponse(['message'=>'reservation ajouté'],Response::HTTP_CREATED);
}

    return new JsonResponse(null,Response::HTTP_BAD_REQUEST);


}


#[OA\Get(
    path:"/api/booking/find",
    summary:"Votre reservation"
    
)]

#[Route('/api/booking/find/{id}',name:'app_booking_find',methods:'GET')]

public function schow(int $id){
    $resa = $this->repository->findOneBy(['id'=>$id]);

$data = $this->serializer->serialize($resa,'json');

if($data){
    return new JsonResponse($data,Response::HTTP_OK,[],true);
}
return new JsonResponse(null,Response::HTTP_NOT_FOUND);

}


#[Route('/api/booking/customer',name:'app_booking_customer',methods:'GET')]


public function showByCustomer(){

$customer = $this->getUser();
if($customer){
    $resa = $this->repository->findAllById(['id'=>$customer]);
    $data = $this->serializer->serialize($resa,'json');
    if($data){
        return new JsonResponse($data,Response::HTTP_OK,[],true);
    }
    return new JsonResponse(null,Response::HTTP_NOT_FOUND);
}
}



#[OA\Put(
    path:"/api/booking/edit",
    summary:"Modifier votre reservation"
    
)]

#[Route('/api/booking/edit/{id}',name:'app_booking_edit',methods:'PUT')]

public function edit(int $id,Request $request){

    $resa = $this->repository->findOneBy(['id'=>$id]);

    if($resa){

    $data = $this->serializer->deserialize($request->getContent(),Booking::class,'json',[AbstractNormalizer::OBJECT_TO_POPULATE=>$resa]);
if($data){
    $this->manager->flush();
}
  

return new JsonResponse(['message'=>'Réservation modifié avec succès'],Response::HTTP_ACCEPTED);
    }

    return new JsonResponse(null,Response::HTTP_NOT_FOUND);
}


#[OA\Delete(
    path:"/api/booking/delete",
    summary:"supprimer votre reservation"
    
)]


#[Route('/api/booking/delete/{id}',name:'app_booking_delete',methods:'DELETE')]
public function delete(int $id){
    $resa = $this->repository->findOneBy(['id'=>$id]);
    if($resa){
        $this->manager->remove($resa);
        $this->manager->flush();

        return new JsonResponse(['message'=>'Votre réservation a été supprimé avec succeès']);
    }
return new JsonResponse(null,Response::HTTP_NOT_FOUND);
}




}
