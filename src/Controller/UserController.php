<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

     class UserController extends AbstractController
   {
    /**
     * @Route("api/admin/users", name="add_user",methods={"POST"})
     */
    public function addUsers(Request $request, UserService $service){
        $data = $service->addUser($request);
        return new JsonResponse($data,Response::HTTP_CREATED);
    }

    /*/**
     * @Route("api/admin/users/{id}", name="put_user",methods={"PUT"})
     */
    /*public function ModifyUser(Request $request,int $id){
        $this->userService->updateUser($request, $id);
        return new JsonResponse("l'Utilisateur a été modifié avec succés",Response::HTTP_CREATED);
    }*/
}
