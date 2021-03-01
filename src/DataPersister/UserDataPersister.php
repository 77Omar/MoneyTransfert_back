<?php

// src/DataPersister

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserDataPersister implements ContextAwareDataPersisterInterface
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
        return $data instanceof User;
    }

    public function persist($data, array $context = [])
    {
        // TODO: Implement persist() method.
    }

    public function remove($data, array $context = [])
    {
        $data->setArchive(true);
        $this->entityManager->flush();
    }
}
