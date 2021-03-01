<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\ProfilRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{

    private $manager;
    private $serializer;
    private $validator;
    private $encoder;
    private $profilRepository;
    private $userRepository;

    public function __construct(EntityManagerInterface $manager, SerializerInterface $serializer,UserRepository $userRepository, ProfilRepository $profilRepository, UserPasswordEncoderInterface $encoder, ValidatorInterface $validator)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->encoder = $encoder;
        $this->profilRepository = $profilRepository;
        $this->userRepository = $userRepository;

    }

    //Ajouter un utilisateur
    public function addUser(Request $request)
    {
        $user=$request->request->all();
        $users=$this->serializer->denormalize($user,"App\Entity\User",true);
        $password=$users->getPassword();
        $users->setPassword($this->encoder ->encodePassword($users,$password));
        $this->manager->persist($users);
        $this->manager->flush();

        return $users;

    }


    /*public function updateUser(Request $request, int $id){
        $dataUser= $request->request->all();
        //dd($dataUser);
        $avatar= $request->files->get("avatar");
        if ($avatar){
            $avatar= fopen($avatar->getRealPath(),'rb');
        }

        $typeUser=$this->userRepository->find($id);
        //dd($typeUser);

        foreach ($dataUser as $key=>$value){
            //dd($value);
            if ($key !== "_method"){
                $key=ucfirst($key);
                $set= "set".$key;
                //dd($set);
                $typeUser->$set($value);
                //dd($typeUser);
            }
        }
        $this->manager->persist($typeUser);
        $this->manager->flush();
        if ($avatar){
            fclose($avatar);
        }
        return new JsonResponse("l'Utilisateur a été modifié avec succés",Response::HTTP_CREATED);
    }
    */
}
