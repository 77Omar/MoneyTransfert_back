<?php

// src/DataPersister

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Compte;
use Doctrine\ORM\EntityManagerInterface;

class CompteDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Compte;
    }

    public function persist($data, array $context = [])
    {
        // TODO: Implement persist() method.
    }

    public function remove($data, array $context = [])
    {
        $compte= $data->getAgence();
        $compte->setArchived(true);
        $this->entityManager->flush();
    }
}
