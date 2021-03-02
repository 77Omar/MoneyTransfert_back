<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Repository\CommissionRepository;
use App\Repository\TransactionsRepository;
use App\Service\FraisService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Transactions;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class TransactionsController extends AbstractController
{

    function code(){
        $chars = '0123456789';
        $string = '';
        for($i=0; $i<9; $i++){
            $string .= $chars[rand(0, strlen($chars)-1)];
            if ($i==2 || $i==5){
                $string.='-';
            }
        }
        return $string;
    }

    private $fraisService;
    public function __construct(FraisService $fraisService)
    {
        $this->fraisService=$fraisService;
    }
    /**
     * @Route("api/admin/transaction/depot", name="add_frais",methods={"POST"})
     */
    public function postFrais(Request $request,
                              TokenStorageInterface $tokenStorage,
                              EntityManagerInterface $manager,
                              SerializerInterface $serializer,
                              CommissionRepository $commissionRepository
                             ){

        $adminAgence= $tokenStorage->getToken()->getUser();
        $compte= $adminAgence->getAgence()->getCompte();
        //dd($compte);
        if ($compte->getSolde() < 5000){
            return $this->json("le depot ne peut pas etre effectué car le solde du compte est inferieur à 5000f",403);
        }
        $code=$this->code();
        $comissions= $commissionRepository->findAll();
        $comissions=$comissions[0];
        //dd($comissions);
        $data=$request->getContent();
        $dataTab= $serializer->decode($data,'json');
        $dataObject= $serializer->denormalize($dataTab, Transactions::class,true);
        $client= $serializer->denormalize($dataTab['client'], Clients::class,true);
        $taxe= $this->fraisService->Prix($dataObject->getMontant());
        $dataObject->setDateDepot(new \DateTime());
        $dataObject->setFrais($taxe);
        $dataObject->setFraisEtat($taxe * $comissions->getPartEtat()/100);
        $dataObject->setFraisSysteme($taxe * $comissions->getPartSysteme()/100);
        $dataObject->setFraisDepot($taxe * $comissions->getPartDepot()/100);
        $dataObject->setFraisRetrait($taxe * $comissions->getPartRetrait()/100);
        $dataObject->setCode($code);
        $compte->setSolde($compte->getSolde() - $dataObject->getMontant());
        $dataObject->setCompte($compte);
        $dataObject->setUser($adminAgence);

        $manager->persist($client);
        $dataObject->setClients($client);
        $manager->persist($dataObject);
        $manager->flush();
        return $this->json('dépot effectué avec succés code de transaction: '.$code,200);
    }




    /**
     * @Route("api/admin/transaction/{id}/depot", name="put_frais",methods={"PUT"})
     */
    public function putFrais(Request $request,
                              TokenStorageInterface $tokenStorage,
                              EntityManagerInterface $manager,
                              SerializerInterface $serializer,
                              TransactionsRepository $transactionsRepository
    ){

        $data = $request->getContent();
        $dataTab = $serializer->decode($data,'json');
        $codes = $transactionsRepository->findOneBy(["code"=>$dataTab['code']]);
        dd($codes);
        if($codes->getDateRetrait() != null){
          return $this->json('desolé la date du code est expiré',403);
        }
        $adminAgence = $tokenStorage->getToken()->getUser();
        $compte = $adminAgence->getAgence()->getCompte();
        if($compte->getSolde() < $codes->getMontant()){
            return $this->json('le montant du compte ne peut pas etre effectué car le solde du compte est inferieur au montant demander!', 403);
        }
        $codes->setDateRetrait(new\DateTime());
        $compte->setSolde($compte->getSolde() + $codes->getMontant());
        $manager->flush();
        return $this->json('Retrait effectué avec succés', 200);
    }
}
