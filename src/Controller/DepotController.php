<?php

namespace App\Controller;

use App\Repository\CompteRepository;
use App\Repository\DepotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class DepotController extends AbstractController
{
        /**
         * @Route("api/admin/depot", name="add_depot",methods={"POST"})
         */
        public function addUsers(Request $request, CompteRepository $compteRepository,SerializerInterface $serializer,
                                 TokenStorageInterface $storage,EntityManagerInterface $manager){
            $depotjson= $request->getContent();
            $depotTab = $serializer->decode($depotjson, "json");
            $compte = $compteRepository->find($depotTab['idC']);
            $depotObjet = $serializer->denormalize($depotTab,'App\Entity\Depot',true);
            $depotObjet->setDateDepot(new \DateTime());
            $depotObjet->setCompte($compte);
            $recupSolde = $depotObjet->getCompte()->getSolde();
            $depotObjet->getCompte()->setSolde($depotTab['montantDepot']+$recupSolde);
            $user=$storage->getToken()->getUser();
            $depotObjet->setUser($user);
            $manager->persist($depotObjet);
            $manager->flush();
      return $this->json('Depot effectué avc succes',200);
    }
}
