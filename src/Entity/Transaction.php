<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'Transaction', targetEntity: Ads::class)]
    private Collection $Status;

    public function __construct()
    {
        $this->Status = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Ads>
     */
    public function getStatus(): Collection
    {
        return $this->Status;
    }

    public function addStatus(Ads $status): static
    {
        if (!$this->Status->contains($status)) {
            $this->Status->add($status);
            $status->setTransaction($this);
        }

        return $this;
    }

    public function removeStatus(Ads $status): static
    {
        if ($this->Status->removeElement($status)) {
            // set the owning side to null (unless already changed)
            if ($status->getTransaction() === $this) {
                $status->setTransaction(null);
            }
        }

        return $this;
    }
}
