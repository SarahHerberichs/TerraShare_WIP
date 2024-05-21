<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
class Type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: Ads::class)]
    private Collection $ads;

    #[ORM\OneToMany(mappedBy: 'Type', targetEntity: TypeTransaction::class)]
    private Collection $Transaction;

    public function __construct()
    {
        $this->ads = new ArrayCollection();
        $this->Transaction = new ArrayCollection();
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
    public function getAds(): Collection
    {
        return $this->ads;
    }

    public function addAd(Ads $ad): static
    {
        if (!$this->ads->contains($ad)) {
            $this->ads->add($ad);
            $ad->setType($this);
        }

        return $this;
    }

    public function removeAd(Ads $ad): static
    {
        if ($this->ads->removeElement($ad)) {
            // set the owning side to null (unless already changed)
            if ($ad->getType() === $this) {
                $ad->setType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TypeTransaction>
     */
    public function getTransaction(): Collection
    {
        return $this->Transaction;
    }

    public function addTransaction(TypeTransaction $transaction): static
    {
        if (!$this->Transaction->contains($transaction)) {
            $this->Transaction->add($transaction);
            $transaction->setType($this);
        }

        return $this;
    }

    public function removeTransaction(TypeTransaction $transaction): static
    {
        if ($this->Transaction->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getType() === $this) {
                $transaction->setType(null);
            }
        }

        return $this;
    }
}
