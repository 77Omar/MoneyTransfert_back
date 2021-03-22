<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TransactionsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TransactionsRepository::class)
 * @ApiResource(
 *     routePrefix="/admin",
 *     normalizationContext={"groups"={"trans:read"}},
 *     attributes={
 *     {"pagination_partial"=true},
 *    "security"="is_granted('ROLE_AdminAgence')  or is_granted('ROLE_UserAgence')",
 *   "security_message"="Ressource accessible que par l'AdminAgence et UserAgence",
 *  "denormalization_context"={"groups"={"trans:write"}},
 * },
 *
 *     collectionOperations={
 *       "post"={"path"="/transaction/depot"},
 *   "get_commis"={
 *   "methods"="GET",
 *   "path"="/transaction/commission/depot",
 *   "route_name"="commis_liste",
 *   },
 *    "get"={
 *   "methods"="GET",
 *   "path"="/transaction/commission/retrait",
 *   "route_name"="liste",
 *   },
 *     "get_trans"={
 *   "methods"="GET",
 *   "path"="/transaction/userDepot",
 *   "route_name"="trans_liste",
 *   },
 *    "get_retrait"={
 *   "methods"="GET",
 *   "path"="/transaction/userRetrait",
 *   "route_name"="retrait_liste",
 *   },
 *     "calcul"={
 *   "methods"="GET",
 *   "path"="/transaction/calculfrais/{type}/{montant}",
 *   "route_name"="calcul",
 *   },
 *     },
 *      itemOperations={
 *      "get"={"path"="/transaction/{id}/imprimer"},
 *      "put",
 *      "delete"={"path"="/transaction/{id}"},
 *     }
 * )
 */
class Transactions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"trans:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups ({"trans:read","trans:write", "client"})
     */
    private $montant;

    /**
     * @ORM\Column(type="date")
     * @Groups ({"trans:read","depot","client"})
     */
    private $date_depot;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups ({"trans:read", "trans:write","client"})
     */
    private $date_retrait;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"trans:read", "trans:write","depot","client"})
     */
    private $code;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups ({"trans:read"})
     */
    private $date_annulation;

    /**
     * @ORM\Column(type="integer")
     * @Groups ({"trans:read"})
     */
    private $frais;

    /**
     * @ORM\Column(type="integer")
     * @Groups ({"trans:read"})
     */
    private $frais_depot;

    /**
     * @ORM\Column(type="integer")
     * @Groups ({"trans:read"})
     */
    private $frais_retrait;

    /**
     * @ORM\Column(type="integer")
     * @Groups ({"trans:read"})
     */
    private $frais_etat;

    /**
     * @ORM\Column(type="integer")
     * @Groups ({"trans:read"})
     */
    private $frais_systeme;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transactionDepot")
     * @ORM\JoinColumn(nullable=false)
     * @Groups ({"trans:read"})
     */
    private $userDepot;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transactionRetrait")
     * @ORM\JoinColumn(nullable=true)
     * @Groups ({"trans:read"})
     */
    private $userRetrait;

    /**
     * @ORM\ManyToOne(targetEntity=Clients::class, inversedBy="transactionDepot")
     * @ORM\JoinColumn(nullable=false)
     * @Groups ({"client"})
     */
    private $clientDepot;

    /**
     * @ORM\ManyToOne(targetEntity=Clients::class, inversedBy="transactionRetrait")
     * @ORM\JoinColumn(nullable=false)
     * @Groups ({"client"})
     */
    private $clientRetrait;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="transaction_depot")
     * @ORM\JoinColumn(nullable=false)
     */
    private $compte_depot;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="transaction_retrait")
     */
    private $compte_retrait;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDateDepot(): ?\DateTimeInterface
    {
        return $this->date_depot;
    }

    public function setDateDepot(\DateTimeInterface $date_depot): self
    {
        $this->date_depot = $date_depot;

        return $this;
    }

    public function getDateRetrait(): ?\DateTimeInterface
    {
        return $this->date_retrait;
    }

    public function setDateRetrait(\DateTimeInterface $date_retrait): self
    {
        $this->date_retrait = $date_retrait;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDateAnnulation(): ?\DateTimeInterface
    {
        return $this->date_annulation;
    }

    public function setDateAnnulation(\DateTimeInterface $date_annulation): self
    {
        $this->date_annulation = $date_annulation;

        return $this;
    }

    public function getFrais(): ?int
    {
        return $this->frais;
    }

    public function setFrais(int $frais): self
    {
        $this->frais = $frais;

        return $this;
    }

    public function getFraisDepot(): ?int
    {
        return $this->frais_depot;
    }

    public function setFraisDepot(int $frais_depot): self
    {
        $this->frais_depot = $frais_depot;

        return $this;
    }

    public function getFraisRetrait(): ?int
    {
        return $this->frais_retrait;
    }

    public function setFraisRetrait(int $frais_retrait): self
    {
        $this->frais_retrait = $frais_retrait;

        return $this;
    }

    public function getFraisEtat(): ?int
    {
        return $this->frais_etat;
    }

    public function setFraisEtat(int $frais_etat): self
    {
        $this->frais_etat = $frais_etat;

        return $this;
    }

    public function getFraisSysteme(): ?int
    {
        return $this->frais_systeme;
    }

    public function setFraisSysteme(int $frais_systeme): self
    {
        $this->frais_systeme = $frais_systeme;

        return $this;
    }


    public function getUserDepot(): ?User
    {
        return $this->userDepot;
    }

    public function setUserDepot(?User $userDepot): self
    {
        $this->userDepot = $userDepot;

        return $this;
    }

    public function getUserRetrait(): ?User
    {
        return $this->userRetrait;
    }

    public function setUserRetrait(?User $userRetrait): self
    {
        $this->userRetrait = $userRetrait;

        return $this;
    }

    public function getClientDepot(): ?Clients
    {
        return $this->clientDepot;
    }

    public function setClientDepot(?Clients $clientDepot): self
    {
        $this->clientDepot = $clientDepot;

        return $this;
    }

    public function getClientRetrait(): ?Clients
    {
        return $this->clientRetrait;
    }

    public function setClientRetrait(?Clients $clientRetrait): self
    {
        $this->clientRetrait = $clientRetrait;

        return $this;
    }

    public function getCompteDepot(): ?Compte
    {
        return $this->compte_depot;
    }

    public function setCompteDepot(?Compte $compte_depot): self
    {
        $this->compte_depot = $compte_depot;

        return $this;
    }

    public function getCompteRetrait(): ?Compte
    {
        return $this->compte_retrait;
    }

    public function setCompteRetrait(?Compte $compte_retrait): self
    {
        $this->compte_retrait = $compte_retrait;

        return $this;
    }


}
