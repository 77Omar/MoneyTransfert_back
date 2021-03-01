<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ClientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ClientsRepository::class)
 * @ApiResource(
 *     routePrefix="/admin",
 *     normalizationContext={"groups"={"client:read"}},
 *     attributes={
 *  "security"="is_granted('ROLE_AdminSystem')",
 * "security_message"="Ressource accessible que par l'Admin",
 *  "denormalization_context"={"groups"={"client:write"}},
 * },
 *     collectionOperations={
 *     "get"={"path"="/client"},
 *      "post"={"path"="/client"},
 *     },
 *      itemOperations={
 *     "get"={"path"="/client/{id}"},
 *     "put"={"path"="/client/{id}"},
 *     "delete"={"path"="/client/{id}"},
 *     }
 * )
 */
class Clients
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"client:read","client:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"client:read","client:write"})
     */
    private $nomComplet;

    /**
     * @ORM\Column(type="integer")
     * @Groups ({"client:read","client:write"})
     */
    private $phone;

    /**
     * @ORM\Column(type="integer")
     * @Groups ({"client:read","client:write"})
     */
    private $cni;

    /**
     * @ORM\OneToMany(targetEntity=Transactions::class, mappedBy="clients")
     */
    private $transaction;



    public function __construct()
    {
        $this->transaction = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomComplet(): ?string
    {
        return $this->nomComplet;
    }

    public function setNomComplet(string $nomComplet): self
    {
        $this->nomComplet = $nomComplet;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCni(): ?int
    {
        return $this->cni;
    }

    public function setCni(int $cni): self
    {
        $this->cni = $cni;

        return $this;
    }

    /**
     * @return Collection|Transactions[]
     */
    public function getTransaction(): Collection
    {
        return $this->transaction;
    }

    public function addTransaction(Transactions $transaction): self
    {
        if (!$this->transaction->contains($transaction)) {
            $this->transaction[] = $transaction;
            $transaction->setClients($this);
        }

        return $this;
    }

    public function removeTransaction(Transactions $transaction): self
    {
        if ($this->transaction->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getClients() === $this) {
                $transaction->setClients(null);
            }
        }

        return $this;
    }


}
