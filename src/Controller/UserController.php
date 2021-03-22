<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserController extends AbstractController
   {
    /**
     * @Route("api/admin/users", name="add_user",methods={"POST"})
     */
    public function addUsers(Request $request, UserService $service){
        $data = $service->addUser($request);
        return new JsonResponse($data,Response::HTTP_CREATED);
    }

    /**
     * @Route("api/admin/users/solde_Compte", name="get_user", methods={"GET"})
     */
    public function getsoldeCompte( TokenStorageInterface $tokenStorage){
        $user = $tokenStorage->getToken()->getUser();
        $agence = $user->getAgence()->getCompte()->getSolde();
        return $this->json($agence, Response::HTTP_OK);
    }

}
