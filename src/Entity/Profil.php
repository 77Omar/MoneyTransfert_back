<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 * @ApiResource(
 *      routePrefix="/admin",
 *     attributes={
 * "security"="is_granted('ROLE_AdminSystem')",
 * "security_message"="Ressource accessible que par l'Admin",
 *  "denormalization_context"={"groups"={"profil:write"}},
 * },
 *     collectionOperations={
 *     "get"={"path"="/profils"},
 *      "post"={"path"="/profils"},
 *     },
 *      itemOperations={
 *     "get"={"path"="/profils/{id}"},
 *     "put"={"path"="/profils/{id}"},
 *     "delete"={"path"="/profils/{id}"}
 *     }
 * )
 */
class Profil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"user:read","profil:write","users:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"user:read","profil:write","users:write"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profil")
     * @ApiSubresource()
     */
    private $users;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archived;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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
            $user->setProfil($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getProfil() === $this) {
                $user->setProfil(null);
            }
        }

        return $this;
    }

    public function getArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): self
    {
        $this->archived = $archived;

        return $this;
    }
}
