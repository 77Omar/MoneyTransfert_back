<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Repository\CommissionRepository;
use App\Repository\TransactionsRepository;
use App\Repository\UserRepository;
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

    private $frais;

    /**
     * @Route("api/user/{montant}/taxe" , methods={"GET"})
     */

    public function montantDepot(int $montant ,FraisService $fraisService){
        if($montant>0 && $montant<=5000000){
           $frais= floor($fraisService->Prix($montant));
           return $this->json(['frais'=>$frais],200);
        }
        return $this->json('entrer une valeur positif et inferieur a 5000000',403);
    }

    /**
     * @Route("api/transaction/{code}/retrait" , methods={"GET"})
     */

    public function montantRetrait(string $code, TransactionsRepository $transactionsRepository){
        $trans= $transactionsRepository->findOneBy(['code'=>$code]);
        if(!$trans){
            return $this->json('Le code de transaction est invalid!',403);
        }
        return $this->json($trans,200,[],['groups' =>'client']);
    }

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
        $clientD= $serializer->denormalize($dataTab['clientD'], Clients::class,true);
        $clientR= $serializer->denormalize($dataTab['clientR'], Clients::class,true);
        $taxe= $this->fraisService->Prix($dataObject->getMontant());
        $dataObject->setDateDepot(new \DateTime());
        $dataObject->setFrais($taxe);
        $dataObject->setFraisEtat($taxe * $comissions->getPartEtat()/100);
        $dataObject->setFraisSysteme($taxe * $comissions->getPartSysteme()/100);
        $dataObject->setFraisDepot($taxe * $comissions->getPartDepot()/100);
        $dataObject->setFraisRetrait($taxe * $comissions->getPartRetrait()/100);
        $dataObject->setCode($code);
        $compte->setSolde($compte->getSolde() - $dataObject->getMontant());
        $dataObject->setCompteDepot($compte);
        $dataObject->setUserDepot($adminAgence);

        $manager->persist($clientD);
        $dataObject->setClientDepot($clientD);
        $manager->persist($clientR);
        $dataObject->setClientRetrait($clientR);
        $manager->persist($dataObject);
        $manager->flush();
        return $this->json($dataObject,200,[],["groups"=>"depot"]);
    }




    /**
     * @Route("api/admin/transaction/retrait", name="put_frais",methods={"PUT"})
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
        $codes->setUserRetrait($adminAgence);
        $codes->getClientRetrait()->setCni($dataTab['cni']);
        $codes->setCompteRetrait($compte);
        $manager->flush();
        return $this->json('Retrait effectué avec succés', 200);
    }

    /**
     * @Route(
     * name="commis_liste",
     * path="api/admin/transaction/commission/depot",
     * methods={"GET"},
     * defaults={
     * "_controller"="\App\Controller\TransactionsController::getCommission",
     * "_api_resource_class"=Transactions::class,
     * "_api_collection_operation_name"="get_commis"
     * }
     * )
     */
    public function getCommission(TransactionsRepository $transactionsRepository, TokenStorageInterface $tokenStorage, Request $request){

        $page = (int) $request->query->get('page',1);

        $adminAgence= $tokenStorage->getToken()->getUser();
        $agenceId= $adminAgence->getAgence()->getId();
        return $transactionsRepository->finByCommssionDepot($agenceId, $page);
    }


    /**
     * @Route(
     * name="liste",
     * path="api/admin/transaction/commission/retrait",
     * methods={"GET"},
     * defaults={
     * "_controller"="\App\Controller\TransactionsController::CommissionRetrait",
     * "_api_resource_class"=Transactions::class,
     * "_api_collection_operation_name"="get"
     * }
     * )
     */
    public function CommissionRetrait(TransactionsRepository $transactionsRepository, TokenStorageInterface $tokenStorage, Request $request){

        $page = (int) $request->query->get('page',1);

        $adminAgence= $tokenStorage->getToken()->getUser();
        $agencesId= $adminAgence->getAgence()->getId();
        return $transactionsRepository->findByCommissionRetrait($agencesId, $page);
    }



    /**
     * @Route(
     * name="trans_liste",
     * path="api/admin/transaction/userDepot",
     * methods={"GET"},
     * defaults={
     * "_controller"="\App\Controller\TransactionsController::transactionDepot",
     * "_api_resource_class"=Transactions::class,
     * "_api_collection_operation_name"="get_trans"
     * }
     * )
     */
    public function  transactionDepot(TransactionsRepository $transactionsRepository, TokenStorageInterface $tokenStorage, Request $request){

        $page = (int) $request->query->get('page',1);

        $users= $tokenStorage->getToken()->getUser();
        $depotId = $users->getId();
        return $transactionsRepository->findByTransDepot($depotId, $page);
    }


    /**
     * @Route(
     * name="retrait_liste",
     * path="api/admin/transaction/userRetrait",
     * methods={"GET"},
     * defaults={
     * "_controller"="\App\Controller\TransactionsController::transactionRetrait",
     * "_api_resource_class"=Transactions::class,
     * "_api_collection_operation_name"="get_retrait"
     * }
     * )
     */
    public function  transactionRetrait(TransactionsRepository $transactionsRepository, TokenStorageInterface $tokenStorage,  Request $request) {
        $page = (int) $request->query->get('page',1);

        $user= $tokenStorage->getToken()->getUser();
        $depotId = $user->getId();
        return $transactionsRepository->findByTransRetrait($depotId, $page);
    }

    /**
     * @Route(
     * name="calcul",
     * path="api/admin/transaction/calculfrais/{type}/{montant}",
     * methods={"GET"},
     * defaults={
     * "_controller"="\App\Controller\TransactionsController::CalculFrais",
     * "_api_resource_class"=Transactions::class,
     * "_api_collection_operation_name"="calcul"
     * }
     * )
     */
    public function  CalculFrais( string $type, int $montant) {
       if($type=="depot"){
           $this->frais = $this->fraisService->Prix($montant) *10/100;
       }else {
           $this->frais = $this->fraisService->Prix($montant) *20/100;
       }
       return $this->json(['frais'=>$this->frais]);
    }

}
