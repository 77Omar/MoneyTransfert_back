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
     * @Assert\GreaterThan(700000),
     * message="la valeur est initialisé à 700000 ou plus "
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
     * @ORM\OneToMany(targetEntity=Depot::class, mappedBy="compte")
     */
    private $depots;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isArchived;

    /**
     * @ORM\OneToOne(targetEntity=Agence::class, mappedBy="compte", cascade={"persist", "remove"})
     */
    private $agence;

    /**
     * @ORM\OneToMany(targetEntity=Transactions::class, mappedBy="compte_depot")
     */
    private $transaction_depot;

    /**
     * @ORM\OneToMany(targetEntity=Transactions::class, mappedBy="compte_retrait")
     */
    private $transaction_retrait;

    public function __construct()
    {
        $this->depots = new ArrayCollection();
        $this->date_creation = new \DateTime('now');
        $this->transaction_depot = new ArrayCollection();
        $this->transaction_retrait = new ArrayCollection();
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

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): self
    {
        // unset the owning side of the relation if necessary
        if ($agence === null && $this->agence !== null) {
            $this->agence->setCompte(null);
        }

        // set the owning side of the relation if necessary
        if ($agence !== null && $agence->getCompte() !== $this) {
            $agence->setCompte($this);
        }

        $this->agence = $agence;

        return $this;
    }

    /**
     * @return Collection|Transactions[]
     */
    public function getTransactionDepot(): Collection
    {
        return $this->transaction_depot;
    }

    public function addTransactionDepot(Transactions $transactionDepot): self
    {
        if (!$this->transaction_depot->contains($transactionDepot)) {
            $this->transaction_depot[] = $transactionDepot;
            $transactionDepot->setCompteDepot($this);
        }

        return $this;
    }

    public function removeTransactionDepot(Transactions $transactionDepot): self
    {
        if ($this->transaction_depot->removeElement($transactionDepot)) {
            // set the owning side to null (unless already changed)
            if ($transactionDepot->getCompteDepot() === $this) {
                $transactionDepot->setCompteDepot(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transactions[]
     */
    public function getTransactionRetrait(): Collection
    {
        return $this->transaction_retrait;
    }

    public function addTransactionRetrait(Transactions $transactionRetrait): self
    {
        if (!$this->transaction_retrait->contains($transactionRetrait)) {
            $this->transaction_retrait[] = $transactionRetrait;
            $transactionRetrait->setCompteRetrait($this);
        }

        return $this;
    }

    public function removeTransactionRetrait(Transactions $transactionRetrait): self
    {
        if ($this->transaction_retrait->removeElement($transactionRetrait)) {
            // set the owning side to null (unless already changed)
            if ($transactionRetrait->getCompteRetrait() === $this) {
                $transactionRetrait->setCompteRetrait(null);
            }
        }

        return $this;
    }

}
