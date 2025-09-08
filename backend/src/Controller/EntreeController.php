<?php

namespace App\Controller;
use App\Entity\Category;
use App\Entity\EntreDuChef;
use App\Repository\CategoryRepository;
use App\Repository\EntreDuChefRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;




class EntreeController extends AbstractController
{


    public function __construct(private EntityManagerInterface    $manager,
    private EntreDuChefRepository $entreRepository,
    private CategoryRepository $catRepository,
    private SerializerInterface $serializer,
    private UrlGeneratorInterface $urlGenerator,

     )
    {
        
    }



    #[Route('/entree', name: 'app_entree',methods:'GET')]
    public function index(): Response
    {

        $entre = $this->entreRepository->findAll();

        if($entre){

            $data = $this->serializer->serialize($entre,'json',[ObjectNormalizer::ENABLE_MAX_DEPTH=>true,
        ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER=>function($object){
            return $object->getId();
        }]);

            return new JsonResponse($data,Response::HTTP_FOUND,[],true);
        }


       return new JsonResponse(null,Response::HTTP_NOT_FOUND);
    }


#[Route('/entree/new/{id}', name: 'app_entree_ajouter',methods:'POST')]
public function ajouter(Request $request,$id): Response
{
    $category= $this->catRepository->findOneBy(['id'=>$id]);
        $entre = $this->serializer->deserialize($request->getContent(),EntreDuChef::class,'json');

        
        if($entre){
            $entre->setCategorie($category);

            $this->manager->persist($entre);
            $this->manager->flush();
            return new JsonResponse(['message'=>'Entré ajouté'],Response::HTTP_ACCEPTED);

        }
        return new JsonResponse(null,Response::HTTP_BAD_REQUEST);
}



#[Route('/entree/show/{id}',name:'app_entree_find',methods:'GET')]
public function show($id):JsonResponse
{

    $entre= $this->entreRepository->findOneBy(['id'=>$id]);
    if($entre){

        $data =$this->serializer->serialize($entre,'json',[ObjectNormalizer::ENABLE_MAX_DEPTH=>true,
        ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER=>function($object){
            return $object->getId();
        }]);
        return new JsonResponse($data,Response::HTTP_FOUND,[],true);
    }
    return new JsonResponse(null,Response::HTTP_NOT_FOUND);
}





#[Route('/entree/edit/{id}',name:'app_entree_edit',methods:'PUT')]
public function edit(Request $request,$id){

    $entree = $this->entreRepository->findOneBy(['id'=>$id]);

    if($entree){

        $data = $this->serializer->deserialize($request->getContent(),EntreDuChef::class,'json',[ObjectNormalizer::OBJECT_TO_POPULATE =>$entree,
        ObjectNormalizer::ENABLE_MAX_DEPTH=>true,
    ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER=>function($object){
return $object->getId();
    }]);
        $this->manager->flush($data);
        return new JsonResponse(['message'=>'entrée modifié avec succès'],Response::HTTP_ACCEPTED);
    }
return new JsonResponse(null,Response::HTTP_NOT_FOUND);
}



#[Route('/entree/delete/{id}',name:'app_entree_delete',methods:'GET')]
public function delete($id):JsonResponse
{

    $entre= $this->entreRepository->findOneBy(['id'=>$id]);
    if($entre){

       $this->manager->remove($entre);
       $this->manager->flush();
        return new JsonResponse(['message'=>'entrée supprimé avec sucess'],Response::HTTP_LOCKED);
    }
    return new JsonResponse(null,Response::HTTP_NOT_FOUND);
}


}
