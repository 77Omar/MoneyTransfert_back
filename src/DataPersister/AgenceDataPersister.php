<?php

// src/DataPersister

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Agence;
use Doctrine\ORM\EntityManagerInterface;

class AgenceDataPersister implements ContextAwareDataPersisterInterface
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
        return $data instanceof Agence;
    }

    public function persist($data, array $context = [])
    {
        // TODO: Implement persist() method.
    }

    public function remove($data, array $context = [])
    {
        $compte= $data->getCompte();
        $compte->setIsArchived(true);
        $this->entityManager->flush();
    }
}
