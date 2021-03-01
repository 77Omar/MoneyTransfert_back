<?php

namespace App\Entity;

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
 *  "security"="is_granted('ROLE_AdminSystem')",
 * "security_message"="Ressource accessible que par l'Admin",
 *  "denormalization_context"={"groups"={"trans:write"}},
 * },
 *     collectionOperations={
 *     "get"={"path"="/transaction"},
 *     "get"={"path"="/transaction"},
 *      "post"={"path"="/transaction/depot"},
 *     },
 *      itemOperations={
 *     "get"={"path"="/transaction/{id}/imprimer"},
 *     "put"={"path"="/transaction/{id}/depot"},
 *     "delete"={"path"="/transaction/{id}"},
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
     * @Groups ({"trans:read","trans:write"})
     */
    private $montant;

    /**
     * @ORM\Column(type="date")
     * @Groups ({"trans:read"})
     */
    private $date_depot;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups ({"trans:read", "trans:write"})
     */
    private $date_retrait;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"trans:read", "trans:write"})
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     *  @Groups ({"trans:read","trans:write"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $compte;

    /**
     * @ORM\ManyToOne(targetEntity=Clients::class, inversedBy="transaction")
     */
    private $clients;


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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(?Compte $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    public function getClients(): ?Clients
    {
        return $this->clients;
    }

    public function setClients(?Clients $clients): self
    {
        $this->clients = $clients;

        return $this;
    }


}
