<?php

namespace App\Controller;
use App\Entity\Category;
use App\Entity\PlatDuChef;
use App\Repository\CategoryRepository;
use App\Repository\PlatDuChefRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Annotations\Response as AnnotationsResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PlatController extends AbstractController
{
public function __construct(

private EntityManagerInterface $manager,
private PlatDuChefRepository $platRepository,
private CategoryRepository $catRepository,
private UrlGeneratorInterface $urlGenerator

)
{
    
}


    #[Route('/plat', name: 'app_plat',methods:'GET')]
    public function index(SerializerInterface $serializer): Response
    {
        $plats = $this->platRepository->findAll();

        if($plats){

            $data = $serializer->serialize($plats,'json',[ObjectNormalizer::ENABLE_MAX_DEPTH =>true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER =>function($object){
                return $object->getId();
            }
        ]);
            return new JsonResponse($data,Response::HTTP_OK,[],true);
        }
        return new JsonResponse(null,Response::HTTP_NOT_FOUND);
    }





    #[Route('/plat/new/{id}',name:'app_plat_new',methods:'POST')]
    public function new(Request $request,$id,SerializerInterface $serializer):Response
    {
$plat  = $serializer->deserialize($request->getContent(),PlatDuChef::class,'json');

$category = $this->catRepository->findOneBy(['id'=>$id]);

if($plat){

$plat->setCategory($category);
$this->manager->persist($plat);
$this->manager->flush();

return new JsonResponse(['message'=>'plat enregistré'],Response::HTTP_ACCEPTED);

}

return new JsonResponse(null,Response::HTTP_BAD_REQUEST);

    }


#[Route('/plat/find/{id}',name:'app_plat_show',methods:'GET')]
public function show($id,SerializerInterface $serializer):JsonResponse
{
$plat = $this->platRepository->findOneBy(['id'=>$id]);

if($plat){

    $data = $serializer->serialize($plat,'json',[ObjectNormalizer::ENABLE_MAX_DEPTH=>true,
    ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER=>function($object){
        return $object->getId();
    }]);
    return new JsonResponse($data,Response::HTTP_OK,[],true);
}
return new JsonResponse(null,Response::HTTP_NOT_FOUND);
}



#[Route('/plat/edit/{id}',name:'app_plat_edit',methods:'PUT')]
public function edit($id,Request $request,SerializerInterface $serializer):JsonResponse
{
$plat =$this->platRepository->findOneBy(['id'=>$id]);

if($plat){
$data = $serializer->deserialize($request->getContent(),PlatDuChef::class,'json',[AbstractNormalizer::OBJECT_TO_POPULATE => $plat]);

$this->manager->flush($data);
return new JsonResponse(['message'=>'plat modifié'],Response::HTTP_ACCEPTED);
}

return new JsonResponse(null,Response::HTTP_NOT_FOUND);
}




#[Route('/plat/delete/{id}',name:'app_plat_show_delete',methods:'DELETE')]
public function Delete($id,SerializerInterface $serializer):JsonResponse
{
$plat = $this->platRepository->findOneBy(['id'=>$id]);

if($plat){
$this->manager->remove($plat);
return new JsonResponse(['message'=>'Plat supprimé'],Response::HTTP_OK);
   
}
return new JsonResponse(null,Response::HTTP_NOT_FOUND);
}


}
