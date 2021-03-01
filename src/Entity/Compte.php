<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\CompteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CompteRepository::class)
 * @ApiResource(
 *     routePrefix="/admin",
 *     denormalizationContext={"groups"={"compte:write"}},
 *     normalizationContext={"groups"={"compte:read"}},
 *     attributes={
 *   "security"="is_granted('ROLE_AdminSystem')",
 *   "security_message"="Ressource accessible que par l'Admin",
 * },
 *     collectionOperations={
 *     "get"={"path"="/compte"},
 *      "post"={"path"="/compte"},
 *     },
 *      itemOperations={
 *     "get"={"path"="/compte/{id}"},
 *     "put"={"path"="/compte/{id}"},
 *     "delete"={"path"="/compte/{id}"},
 *     }
 * )
 */
class Compte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"compte:read","compte:write","agence:read","agence:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups ({"compte:read","compte:write","agence:read","agence:write"})
     */
    private $numeroCompte;

    /**
     * @Assert\GreaterThan(700000)
     * @ORM\Column(type="integer")
     * @Groups ({"compte:read","compte:write","agence:read","agence:write"})
     */
    private $solde;

    /**
     * @ORM\Column(type="date")
     * @Groups ({"compte:read","compte:write","agence:read","agence:write"})
     */
    private $date_creation;

    /**
     * @ORM\OneToMany(targetEntity=Transactions::class, mappedBy="compte")
     */
    private $transactions;

    /**
     * @ORM\OneToMany(targetEntity=Depot::class, mappedBy="compte")
     */
    private $depots;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isArchived;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
        $this->depots = new ArrayCollection();
        $this->date_creation = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroCompte(): ?int
    {
        return $this->numeroCompte;
    }

    public function setNumeroCompte(int $numeroCompte): self
    {
        $this->numeroCompte = $numeroCompte;

        return $this;
    }

    public function getSolde(): ?int
    {
        return $this->solde;
    }

    public function setSolde(int $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    /**
     * @return Collection|transactions[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(transactions $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setCompte($this);
        }

        return $this;
    }

    public function removeTransaction(transactions $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getCompte() === $this) {
                $transaction->setCompte(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Depot[]
     */
    public function getDepots(): Collection
    {
        return $this->depots;
    }

    public function addDepot(Depot $depot): self
    {
        if (!$this->depots->contains($depot)) {
            $this->depots[] = $depot;
            $depot->setCompte($this);
        }

        return $this;
    }

    public function removeDepot(Depot $depot): self
    {
        if ($this->depots->removeElement($depot)) {
            // set the owning side to null (unless already changed)
            if ($depot->getCompte() === $this) {
                $depot->setCompte(null);
            }
        }

        return $this;
    }
    public function getIsArchived(): ?bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(bool $isArchived): self
    {
        $this->isArchived = $isArchived;

        return $this;
    }

}
