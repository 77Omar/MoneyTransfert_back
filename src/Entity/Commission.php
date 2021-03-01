<?php

namespace App\Entity;

use App\Repository\CommissionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommissionRepository::class)
 */
class Commission
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $partEtat;

    /**
     * @ORM\Column(type="integer")
     */
    private $partSysteme;

    /**
     * @ORM\Column(type="integer")
     */
    private $PartDepot;

    /**
     * @ORM\Column(type="integer")
     */
    private $partRetrait;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPartEtat(): ?int
    {
        return $this->partEtat;
    }

    public function setPartEtat(int $partEtat): self
    {
        $this->partEtat = $partEtat;

        return $this;
    }

    public function getPartSysteme(): ?int
    {
        return $this->partSysteme;
    }

    public function setPartSysteme(int $partSysteme): self
    {
        $this->partSysteme = $partSysteme;

        return $this;
    }

    public function getPartDepot(): ?int
    {
        return $this->PartDepot;
    }

    public function setPartDepot(int $PartDepot): self
    {
        $this->PartDepot = $PartDepot;

        return $this;
    }

    public function getPartRetrait(): ?int
    {
        return $this->partRetrait;
    }

    public function setPartRetrait(int $partRetrait): self
    {
        $this->partRetrait = $partRetrait;

        return $this;
    }
}
