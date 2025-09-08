<?php

namespace App\Controller;

use OpenApi\Attributes as OA;
use App\Entity\Restaurant;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Annotations\MediaType;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Schema;
use OpenApi\Attributes\JsonContent;
use Nelmio\ApiDocBundle\Annotation\Model;
use DateTimeImmutable;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Service\Attribute\Required;

use function PHPSTORM_META\type;

#[Route('/api',name:'app_api')]
class SecurityController extends AbstractController
{
   
    public function __construct(private EntityManagerInterface $manager,private SerializerInterface $serializer, private UserRepository $repository)
    {
        
    }

    #[OA\Get(
        path:"/api/account/me",
        summary:"données de l'utilisateur connecté"
        
    )]


    #[OA\Response(
        response:201,
        description:'Informations utilisateur',
        content:new OA\JsonContent(
            properties:[
                
                new OA\Property(property:'email',type:'string',example:'adresse@email.com'),
                new OA\Property(property:'password',type:'string',example:'mot de passe'),
                new OA\Property(property:'first_name',type:'string',example:'name user'),
                new OA\Property(property:'last_name',type:'string',example:'last name user')

            ]
              
        )
    )]









    #[Route('/account/me',name:'_mon_profile',methods:'GET')]
    public function me():JsonResponse
    {

       $user = $this->getUser();
       $responseData = $this->serializer->serialize($user,'json');
    return new JsonResponse($responseData,Response::HTTP_OK,[],true);
    }



    #[OA\Put(
        path:"/api/account/edit",
        summary:"modification des données de l'utilisateur connecté"
        
    )]

    #[OA\RequestBody(
        required:true,
        description:"Données de l'utilisateur a modifié",
        content:new OA\JsonContent(
            properties:[
                new OA\Property(property:'password',type:'string',example:'mot de passe'),
                new OA\Property(property:'first_name',type:'string',example:'name user'),
                new OA\Property(property:'last_name',type:'string',example:'last name user')

            ]
        )
    )]



    #[OA\Response(
        response:201,
        description:'Informations utilisateur modifié avec succes',
        content:new OA\JsonContent(
            properties:[
                
                new OA\Property(property:'email',type:'string',example:'adresse@email.com'),
                new OA\Property(property:'password',type:'string',example:'mot de passe'),
                new OA\Property(property:'first_name',type:'string',example:'name user'),
                new OA\Property(property:'last_name',type:'string',example:'last name user')

            ]
              
        )
    )]
#[OA\Response(
response:404,
description:'Echec modification'

)]



    #[Route('/account/edit',name:'_edition',methods:'PUT')]
    public function edition(Request $request)
    {
$user = $this->getUser();

if($user){
    $userConnecté = $this->serializer->deserialize($request->getContent(),User::class,'json',[AbstractNormalizer::OBJECT_TO_POPULATE=>$user]);
    $userConnecté->setUpdatedat(new DateTimeImmutable());
    $this->manager->persist($user);
    $this->manager->flush();
    return new JsonResponse(['message'=>'modification réussi'],Response::HTTP_ACCEPTED);

 

}else{
    return new JsonResponse(['message'=>'echec modiication'],Response::HTTP_UNAUTHORIZED);
}

    }

    
    #[OA\Post(
        path:"/api/registration",
        summary:"inscription d'un nouvel utilisateur"
        
    )]
   
    #[OA\RequestBody(
        required:true,
        description:"Données de l'utilisateur a inscrire",
        content:new OA\JsonContent(
            properties:[
                new OA\Property(property:'email',type:'string',example:'adresse@email.com'),
                new OA\Property(property:'password',type:'string',example:'mot de passe'),
                new OA\Property(property:'first_name',type:'string',example:'name user'),
                new OA\Property(property:'last_name',type:'string',example:'last name user')


            ]
        )
    )]
    
    
    #[OA\Response(
        response:201,
        description:'utilisateur inscrit avec success',
        content:new OA\JsonContent(
            properties:[
                new OA\Property(property:'user',type:'string',example:'adresse@email.com'),
                new OA\Property(property:'apiToken',type:'string',example:'a778arta44at47ertasc158e')
            ]
              
        )
    )]


    #[Route('/registration', name: '_registration',methods:['POST'])]
   

    public function register(Request $request,UserPasswordHasherInterface $passwordHasher):JsonResponse
    {

        $user = $this->serializer->deserialize($request->getContent(),User::class,'json');
        $user->setPassword($passwordHasher->hashPassword($user,$user->getPassword()));
        $user->setCreatedAt(new \DateTimeImmutable());
        $this->manager->persist($user);
        $this->manager->flush();
        return new JsonResponse(['user'=>$user->getUserIdentifier(),'apiToken'=>$user->getApitoken(),
    'roles'=>$user->getRoles()],Response::HTTP_CREATED);

    }




    #[OA\Post(
        path:"/api/login",
        summary:"connexion d'un utilisateur"
        
    )]

    #[OA\RequestBody(
        required:true,
        description:"Données requise lors de l'authentification",
        content:new OA\JsonContent(
            properties:[
                new OA\Property(property:'username',type:'string',example:'adresse@email.com'),
                new OA\Property(property:'password',type:'string',example:'mot de passe')
                

            ]
        )
    )]
    #[OA\Response(
        response:200,
        description:'connexion réussie',
        content:new OA\JsonContent(
            properties:[
                new OA\Property(property:'user',type:'string',example:'Nom de l\'utilisateur'),
                new OA\Property(property:'apiToken',type:'string',example:'a778arta44at47ertasc158e')
                
            ]
              
        )
    )]
    
    #[Route('/login',name:'_login',methods:'POST')]
    public function login(#[CurrentUser()]?User $user):JsonResponse
    {
        if(null === $user){
            return new JsonResponse(['message'=>'Missing credential'],Response::HTTP_UNAUTHORIZED);

        }
        return new JsonResponse([
            'user'=>$user->getUserIdentifier(),
            'apiToken'=>$user->getApiToken(),
            'roles'=>$user->getRoles(),
        ]);
    }
}
