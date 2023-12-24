<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CitiesRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CitiesRepository::class)]
class Cities
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['exclude_ads'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['exclude_ads'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['exclude_ads'])]
    private ?int $zipcode = null;

    #[ORM\Column(length: 10)]
    #[Groups(['exclude_ads'])]
    private ?string $department_number = null;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: Ads::class)]

    private Collection $ads;

    public function __construct()
    {
        $this->ads = new ArrayCollection();
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

    public function getZipcode(): ?int
    {
        return $this->zipcode;
    }

    public function setZipcode(int $zipcode): static
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getDepartmentNumber(): ?string
    {
        return $this->department_number;
    }

    public function setDepartmentNumber(string $department_number): static
    {
        $this->department_number = $department_number;

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
            $ad->setCity($this);
        }

        return $this;
    }

    public function removeAd(Ads $ad): static
    {
        if ($this->ads->removeElement($ad)) {
            // set the owning side to null (unless already changed)
            if ($ad->getCity() === $this) {
                $ad->setCity(null);
            }
        }

        return $this;
    }
}
