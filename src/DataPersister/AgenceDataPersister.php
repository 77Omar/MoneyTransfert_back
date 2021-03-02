<?php

// src/DataPersister

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Agence;
use App\Entity\Compte;
use App\Repository\CompteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;

class AgenceDataPersister implements ContextAwareDataPersisterInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    private $compteRepository;
    private $request;
    private $userRepository;
    private $serializer;

    public function __construct(
        EntityManagerInterface $entityManager,
        CompteRepository $compteRepository,
        RequestStack $request,
        UserRepository $userRepository,
        SerializerInterface $serializer

    ) {
        $this->entityManager = $entityManager;
        $this->compteRepository=$compteRepository;
        $this->request = $request;
        $this->userRepository = $userRepository;
        $this->serializer = $serializer;
    }
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Agence;
    }

    public function persist($data, array $context = [])
    {
        if (isset($context['collection_operation_name'])){
            $content= $this->request->getCurrentRequest()->getContent();
            $content= $this->serializer->decode($content, 'json');
            $account= $this->serializer->denormalize($content['compte'], Compte::class, true);
            $agency= $this->serializer->denormalize($content, Agence::class, true);
            $this->entityManager->persist($account);
            $agency->setCompte($account);
            $this->entityManager->persist($agency);
            $this->entityManager->flush();
        }
        if (isset($context['item_operation_name'])){
            $this->entityManager->persist($data);
            $this->entityManager->flush();
        }
    }

    public function remove($data, array $context = [])
    {
        $userId = $this->request->getCurrentRequest()->get('idu');
        $users=$data->getUsers();
        foreach ($users as $user){
            if($user->getId()==$userId){
                $user->setArchive(true);
                $data->removeUser($user);
            }
        }
        $this->entityManager->flush();
    }
}
