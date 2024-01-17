<?php

namespace App\Entity;

use App\Entity\Photos;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdsRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AdsRepository::class)]
class Ads
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 2000)]
    private ?string $text = null;

    #[ORM\ManyToOne(inversedBy: 'ads')]
    private ?Cities $city = null;
     #[Groups(['exclude_city'])]

    #[ORM\OneToMany(mappedBy: 'ad', targetEntity: Photos::class, cascade:['persist','remove'])]
    private Collection $photos;

     #[ORM\ManyToOne(inversedBy: 'ads')]
     private ?User $user = null;

     #[ORM\ManyToOne(inversedBy: 'ads')]
     private ?Type $Type = null;

     #[ORM\ManyToOne(inversedBy: 'ads')]
     private ?Transaction $Transaction = null;

     #[ORM\ManyToOne(inversedBy: 'ads')]
     private ?Status $Status = null;

     #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
     private ?string $price = null;

     #[ORM\Column(length: 255, nullable: true)]
     private ?string $price_unit = null;

     #[ORM\OneToMany(mappedBy: 'Ad', targetEntity: Message::class)]
     private Collection $messages;

   
    public function __construct()
    {

        $this->createdAt = new \DateTimeImmutable();
        $this->photos = new ArrayCollection();
        $this->messages = new ArrayCollection();
    
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getCity(): ?Cities
    {
        return $this->city;
    }

    public function setCity(?Cities $city): static
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection<int, Photos>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photos $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setAd($this);
        }

        return $this;
    }

    public function removePhoto(Photos $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getAd() === $this) {
                $photo->setAd(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->Type;
    }

    public function setType(?Type $Type): static
    {
        $this->Type = $Type;

        return $this;
    }

    public function getTransaction(): ?Transaction
    {
        return $this->Transaction;
    }

    public function setTransaction(?Transaction $Transaction): static
    {
        $this->Transaction = $Transaction;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->Status;
    }

    public function setStatus(?Status $Status): static
    {
        $this->Status = $Status;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getPriceUnit(): ?string
    {
        return $this->price_unit;
    }

    public function setPriceUnit(?string $price_unit): static
    {
        $this->price_unit = $price_unit;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setAd($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getAd() === $this) {
                $message->setAd(null);
            }
        }

        return $this;
    }

  


}
