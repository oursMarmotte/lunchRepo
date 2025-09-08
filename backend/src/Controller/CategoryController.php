<?php

namespace App\Controller;
use App\Entity\Category;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoryRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\DateImmutableType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Annotation\Groups;

#[Route('/category', name: 'app_category')]
class CategoryController extends AbstractController
{

    public function __construct(private EntityManagerInterface $manager,
    private CategoryRepository $repository,
    private SerializerInterface $serializer
    )
    {
        
    }

#[Route('/home',name:'_home',methods:'GET')]
public function homeCategorie():jsonResponse
{

$categorie =new Category();

  $tabCategorie = $this->repository->findAll($categorie);

  if($tabCategorie){

   $data = $this->serializer->serialize($tabCategorie,'json');
   return new JsonResponse($data,Response::HTTP_OK,[],true);
  }

new JsonResponse(null,Response::HTTP_NOT_FOUND);
  
}





    #[Route( '/new',name:'_new',methods:'POST')]
   public function new(Request $request):Response
   {
$category = $this->serializer->deserialize($request->getContent(),Category::class,'json');
if($category){
   
   $category->setCreatedAt(new DateTimeImmutable());
   $category->setUpdatedAt(new DateTimeImmutable());
   $this->manager->persist($category);
$this->manager->flush();

return new JsonResponse(['message'=>'Categorie ajouté'],Response::HTTP_ACCEPTED);
   }

return new JsonResponse(null,Response::HTTP_BAD_REQUEST);

}


   


   



#[Route('/{id}',name:'_show',methods:'GET')]
   public function show(int $id):Response
   {

$category = $this->repository->findOneBy(['id'=>$id]);
if(!$category){
    throw $this->createNotFoundException("Nocategory found for {$id} id");

}
return $this->json(['message'=>"Category trouvé{$category->getTitle()} for{$category->getId()} id "]);
}




#[Route('/{id}',name:'_edit',methods:'PUT')]
   public function edit(int $id):Response
   {
    $category = $this->repository->findOneBy(['id'=>$id]);
    if(!$category){
        throw $this->createNotFoundException("No category found for {$id} id");
    
    }
    $category->setTitle("Entrées et plats");
    $this->manager->flush();
    return $this->redirectToRoute('app_category_show', ['id' => $category->getId()]);


   }


#[Route('/{id}',name:'_delete',methods:'DELETE')]
   #
   public function delete(int $id):Response
   {
$category = $this->repository->findOneBy(['id'=>$id]);
if(!$category){
    throw new \Exception(message:"no category found for {$id} id");
    
   
   }
$this->manager->remove($category);
$this->manager->flush();
return $this->json(['message'=>'Categorie resource deleted'],status:Response::HTTP_NO_CONTENT);
   }
}
