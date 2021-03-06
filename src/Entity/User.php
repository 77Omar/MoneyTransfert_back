<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiResource(
 *     routePrefix="/admin",
 *     normalizationContext={"groups"={"user:read"}},
 *     attributes={
 *  "security"="is_granted('ROLE_AdminSystem') or is_granted('ROLE_AdminAgence')  or is_granted('ROLE_UserAgence')",
 *  "security_message"="Ressource accessible que par l'Admin Systeme",
 *  "denormalization_context"={"groups"={"users:write"}},
 * },
 *     collectionOperations={
 *     "get"={"path"="/users"},
 *      "post"={"path"="/users"},
 *  "get_user" = {
 *        "method"="GET",
 *         "path"="/users/solde_Compte",
 *          "security"="is_granted('ROLE_AdminAgence')  or is_granted('ROLE_UserAgence')",
 *          "security_message"="Ressource accessible que par l'AdminAgence et UserAgence",
 *           },
 *     },
 *      itemOperations={
 *     "get"={"path"="/users/{id}"},
 *     "put"={"path"="/users/{id}"},
 *     "delete"={"path"="/users/{id}"},
 *     }
 * )
 */

class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"user:read","users:write","depot:read","transaction"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Le email est obligatoire")
     * @Groups ({"user:read","users:write","depot:read","transaction"})
     */
    protected $email;


    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Le password est obligatoire")
     * @Groups ({"user:read","users:write","depot:read","transaction"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le prenom est obligatoire")
     * @Groups ({"user:read","users:write","depot:read","trans:write","transaction","trans:read"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom est obligatoire")
     * @Groups ({"user:read","users:write","depot:read","trans:write","transaction","trans:read"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Le phone est obligatoire")
     * @Groups ({"user:read","users:write","depot:read","trans:write","transaction"})
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'adresse est obligatoire")
     * @Groups ({"user:read","users:write","depot:read","transaction"})
     */
    private $adresse;


    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Le profil est obligatoire")
     * @Groups ({"user:read","users:write"})
     */
    private $profil;


    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Le cni est obligatoire")
     * @Groups ({"user:read","users:write","depot:read","trans:write","transaction"})
     */
    private $cni;

    /**
     * @ORM\OneToMany(targetEntity=Depot::class, mappedBy="user")
     * @Groups ({"user:read"})
     * @ApiSubresource()
     */
    private $depots;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     * @Groups ({"user:read","users:write","depot:read","agence:write"})
     */
    private $archive=false;

    /**
     * @ORM\ManyToOne(targetEntity=Agence::class, inversedBy="users",cascade={"persist"})
     */
    private $agence;

    /**
     * @ORM\OneToMany(targetEntity=Transactions::class, mappedBy="userDepot")
     */
    private $transactionDepot;

    /**
     * @ORM\OneToMany(targetEntity=Transactions::class, mappedBy="userRetrait")
     */
    private $transactionRetrait;


    public function __construct()
    {
        $this->depots = new ArrayCollection();
        $this->transactionDepot = new ArrayCollection();
        $this->transactionRetrait = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->profil->getLibelle();
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }


    public function getProfil(): ?profil
    {
        return $this->profil;
    }

    public function setProfil(?profil $profil): self
    {
        $this->profil = $profil;

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
            $depot->setUser($this);
        }

        return $this;
    }

    public function removeDepot(Depot $depot): self
    {
        if ($this->depots->removeElement($depot)) {
            // set the owning side to null (unless already changed)
            if ($depot->getUser() === $this) {
                $depot->setUser(null);
            }
        }

        return $this;
    }

    public function getArchive(): ?bool
    {
        return $this->archive;
    }

    public function setArchive(bool $archive): self
    {
        $this->archive = $archive;

        return $this;
    }

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): self
    {
        $this->agence = $agence;

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
            $transactionDepot->setUserDepot($this);
        }

        return $this;
    }

    public function removeTransactionDepot(Transactions $transactionDepot): self
    {
        if ($this->transactionDepot->removeElement($transactionDepot)) {
            // set the owning side to null (unless already changed)
            if ($transactionDepot->getUserDepot() === $this) {
                $transactionDepot->setUserDepot(null);
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
            $transactionRetrait->setUserRetrait($this);
        }

        return $this;
    }

    public function removeTransactionRetrait(Transactions $transactionRetrait): self
    {
        if ($this->transactionRetrait->removeElement($transactionRetrait)) {
            // set the owning side to null (unless already changed)
            if ($transactionRetrait->getUserRetrait() === $this) {
                $transactionRetrait->setUserRetrait(null);
            }
        }

        return $this;
    }


}
