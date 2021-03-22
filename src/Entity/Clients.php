<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ClientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank(message="Le nomComplet est obligatoire")
     * @Groups ({"client:read","client:write","client"})
     */
    private $nomComplet;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Le champs ne doit pas être vide.")
     * @Assert\Regex("/^[7][0|7|8|6]([0-9]{7})$/", message="Entrez un numero de Telephone valide")
     * @Groups ({"client:read","client:write","client"})
     */
    private $phone;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups ({"client:read", "client:write","client"})
     * @Assert\NotBlank(message="Le champs ne doit pas etre vide")
     *      @Assert\Length(
     *      min = 13,
     *      minMessage = "Votre cni doit contenir au moins {{limit}} caractères",
     *      allowEmptyString = false
     * )
     */
    private $cni;

    /**
     * @ORM\OneToMany(targetEntity=Transactions::class, mappedBy="clientDepot")
     */
    private $transactionDepot;

    /**
     * @ORM\OneToMany(targetEntity=Transactions::class, mappedBy="clientRetrait")
     */
    private $transactionRetrait;

    public function __construct()
    {
        $this->transactionDepot = new ArrayCollection();
        $this->transactionRetrait = new ArrayCollection();
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

    public function getCni(): ?string
    {
        return $this->cni;
    }

    public function setCni(string $cni): self
    {
        $this->cni = $cni;

        return $this;
    }

    /**
     * @return Collection|Transactions[]
     */
    public function getTransactionDepot(): Collection
    {
        return $this->transactionDepot;
    }

    public function addTransactionDepot(Transactions $transactionDepot): self
    {
        if (!$this->transactionDepot->contains($transactionDepot)) {
            $this->transactionDepot[] = $transactionDepot;
            $transactionDepot->setClientDepot($this);
        }

        return $this;
    }

    public function removeTransactionDepot(Transactions $transactionDepot): self
    {
        if ($this->transactionDepot->removeElement($transactionDepot)) {
            // set the owning side to null (unless already changed)
            if ($transactionDepot->getClientDepot() === $this) {
                $transactionDepot->setClientDepot(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transactions[]
     */
    public function getTransactionRetrait(): Collection
    {
        return $this->transactionRetrait;
    }

    public function addTransactionRetrait(Transactions $transactionRetrait): self
    {
        if (!$this->transactionRetrait->contains($transactionRetrait)) {
            $this->transactionRetrait[] = $transactionRetrait;
            $transactionRetrait->setClientRetrait($this);
        }

        return $this;
    }

    public function removeTransactionRetrait(Transactions $transactionRetrait): self
    {
        if ($this->transactionRetrait->removeElement($transactionRetrait)) {
            // set the owning side to null (unless already changed)
            if ($transactionRetrait->getClientRetrait() === $this) {
                $transactionRetrait->setClientRetrait(null);
            }
        }

        return $this;
    }


}
