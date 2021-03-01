<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\DepotRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DepotRepository::class)
 * @ApiResource(
 *     routePrefix="/admin",
 *     normalizationContext={"groups"={"depot:read"}},
 *     attributes={
 *   "security"="is_granted('ROLE_AdminSystem')",
 *   "security_message"="Ressource accessible que par l'Admin",
 *  "denormalization_context"={"groups"={"depot:write"}},
 * },
 *     collectionOperations={
 *     "get"={"path"="/depot"},
 *      "post"={"path"="/depot"},
 *     },
 *      itemOperations={
 *     "get"={"path"="/depot/{id}"},
 *     "put"={"path"="/depot/{id}"},
 *     "delete"={"path"="/depot/{id}"},
 *     }
 * )
 */
class Depot
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"depot:read","depot:write","user:read","user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     *  @Groups ({"depot:read","depot:write","user:read","user:read"})
     */
    private $dateDepot;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"depot:read","depot:write","user:read","user:read"})
     */
    private $montantDepot;

    /**
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="depots",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups ({"depot:read"})
     * @ApiSubresource()
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="depots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $compte;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDepot(): ?\DateTimeInterface
    {
        return $this->dateDepot;
    }

    public function setDateDepot(\DateTimeInterface $dateDepot): self
    {
        $this->dateDepot = $dateDepot;

        return $this;
    }

    public function getMontantDepot(): ?string
    {
        return $this->montantDepot;
    }

    public function setMontantDepot(string $montantDepot): self
    {
        $this->montantDepot = $montantDepot;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCompte(): ?compte
    {
        return $this->compte;
    }

    public function setCompte(?compte $compte): self
    {
        $this->compte = $compte;

        return $this;
    }
}
