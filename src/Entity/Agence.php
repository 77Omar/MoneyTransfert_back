<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\AgenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AgenceRepository::class)
 * @ApiResource(
 *     routePrefix="/admin",
 *     normalizationContext={"groups"={"agence:read"}},
 *     attributes={
 *   "security"="is_granted('ROLE_AdminSystem')",
 *   "security_message"="Ressource accessible que par l'Admin",
 *  "denormalization_context"={"groups"={"agence:write"}},
 * },
 *     collectionOperations={
 *     "get"={"path"="/agence"},
 *     "post"={"path"="/agence"},
 *     },
 *      itemOperations={
 *     "get"={"path"="/agence/{id}"},
 *     "put"={"path"="/agence/{id}"},
 *     "delete"={"path"="/agence/{id}"},
 *     }
 * )
 */
class Agence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"user:read","users:write","agence:read","agence:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"user:read","users:write","agence:read","agence:write"})
     */
    private $nomAgence;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"user:read","users:write","agence:read","agence:write"})
     */
    private $adresseAgence;


    /**
     * @ORM\OneToOne(targetEntity=Compte::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups ({"agence:read","agence:write"})
     */
    private $compte;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups ({"agence:read"})
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups ({"agence:read"})
     */
    private $Longitude;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="agence")
     * @ApiSubresource()
     */
    private $users;


    public function __construct()
    {
        $this->users = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomAgence(): ?string
    {
        return $this->nomAgence;
    }

    public function setNomAgence(string $nomAgence): self
    {
        $this->nomAgence = $nomAgence;

        return $this;
    }

    public function getAdresseAgence(): ?string
    {
        return $this->adresseAgence;
    }

    public function setAdresseAgence(string $adresseAgence): self
    {
        $this->adresseAgence = $adresseAgence;

        return $this;
    }

    public function getCompte(): ?compte
    {
        return $this->compte;
    }

    public function setCompte(compte $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->Longitude;
    }

    public function setLongitude(?float $Longitude): self
    {
        $this->Longitude = $Longitude;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setAgence($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAgence() === $this) {
                $user->setAgence(null);
            }
        }

        return $this;
    }

}
