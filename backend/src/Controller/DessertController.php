<?php

namespace App\Controller;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Entity\DessertDuChef;
use App\Repository\DessertDuChefRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class DessertController extends AbstractController
{


    public function __construct(private EntityManagerInterface $manager,
    private DessertDuChefRepository $dessertRepository,
    private CategoryRepository $catRepository,
    private SerializerInterface $serializer,
    private UrlGeneratorInterface $urlGenerator,

     )
    {
        
    }


//OK

    #[Route('/dessert', name: 'app_dessert',methods:'GET')]
    public function index(): Response
    {
       $des = new DessertDuChef();
        $dessert = $this->dessertRepository->findAll($des);
       if($dessert){
$data = $this->serializer->serialize($dessert,'json',[ObjectNormalizer::ENABLE_MAX_DEPTH =>true,
ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER=>function($object){
    return $object->getId();
}
]);

return new JsonResponse($data,Response::HTTP_OK,[],true);
       }

       return new JsonResponse(null,Response::HTTP_NOT_FOUND);
    }


    

    #[Route('/dessert/new/{id}', name: 'app_dessert_new',methods:'POST')]
    public function new(Request $request,$id): JsonResponse
    {
$category= $this->catRepository->findOneBy(['id'=>$id]);
        $dessert = $this->serializer->deserialize($request->getContent(),DessertDuChef::class,'json');

        
        if($dessert){
            $dessert->setCategory($category);

            $this->manager->persist($dessert);
            $this->manager->flush();
            return new JsonResponse(['message'=>'Dessert ajouté'],Response::HTTP_ACCEPTED);

        }
       


        return new JsonResponse(null,Response::HTTP_BAD_REQUEST);

    }




    #[Route('/dessert/find/{id}',name:'app_dessert_show',methods:'GET')]
    public function show(int $id):JsonResponse
    {
$dessert = $this->dessertRepository->findOneBy(['id'=>$id]);
if($dessert){

    $data = $this->serializer->serialize($dessert,'json',[ObjectNormalizer::ENABLE_MAX_DEPTH =>true,
ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER=>function($object){
    return $object->getId();
}
]);
   


   





    return new JsonResponse($data,Response::HTTP_OK,[],true);
}
return new JsonResponse(null,Response::HTTP_NOT_FOUND);

    }






    #[Route('/dessert/edit/{id}',name:'app_dessert_edit',methods:'PUT')]
    public function edit(int $id,Request $request):JsonResponse
    {
$dessert = $this->dessertRepository->findOneBy(['id'=>$id]);
if($dessert){

    $data = $this->serializer->deserialize($request->getContent(),DessertDuChef::class,'json', [AbstractNormalizer::OBJECT_TO_POPULATE=> $dessert]);
    $this->manager->flush($data);
    return new JsonResponse(['message'=>'dessert modifié avec succes'],Response::HTTP_ACCEPTED);
    


    }

    return new JsonResponse(null,Response::HTTP_BAD_REQUEST);
}

#[Route('/dessert/delete/{id}',name:'app_dessert_delete',methods:'DELETE')]
public function delete(int $id):JsonResponse
{
$dessert = $this->dessertRepository->findOneBy(['id'=> $id]);
if($dessert){
$this->manager->remove($dessert);
$this->manager->flush();

return new JsonResponse(['message'=>'Dessert supprimé avec succes'],Response::HTTP_ACCEPTED);
}
    return new JsonResponse(null,Response::HTTP_NOT_FOUND);


}

}


