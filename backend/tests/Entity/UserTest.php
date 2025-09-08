<?php

namespace App\Tests\Entity;
use App\Entity\User;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use  Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class UserTest extends WebTestCase
{
private $name="Thomas";
private $email ="thomas@gmail.com";
    public function provideFirstName(): \Generator
    {
        
        yield['thomas'];
        yield['jacque'];
        yield['valerie'];
    }

    /**@dataProvider  provideFirstName */
    public function testFirstNameSetter(): void
    {
        $name = $this->name;
        $user = new User();
        $this->assertNotNull($user->getApiToken());
        $user->setFirstName($name);
        $user->setEmail($this->email);
        $this->assertSame($this->email,$user->getEmail());
        $this->assertSame($name,$user->getFirstName());
    }


    public function testThanAnUserHastAtLeastOneRoleUser():void
    {
        $user = new User();
        $this->assertContains('ROLE_USER',$user->getRoles());


    }






   
}
