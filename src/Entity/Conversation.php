<?php

namespace App\Entity;

use App\Repository\ConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConversationRepository::class)]
class Conversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // #[ORM\ManyToOne(targetEntity: Ads::class, inversedBy: 'conversations')]
    // // #[ORM\JoinColumn(name: 'ad_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    // #[ORM\JoinColumn(name: 'ad_id', referencedColumnName: 'id')]
    // private ?Ads $ad = null;
    
    #[ORM\ManyToOne(inversedBy: 'Message')]
    #[ORM\JoinColumn(name: 'ad_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    private ?Ads $ad = null;

    #[ORM\OneToMany(mappedBy: 'conversation', targetEntity: Message::class)]

    private Collection $message;

    // #[ORM\ManyToOne(inversedBy: 'User1')]
    // #[ORM\JoinColumn(name: 'user1_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    // private ?User $user1 = null;

    // #[ORM\ManyToOne(inversedBy: 'User2')]
    // #[ORM\JoinColumn(name: 'user2_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    // private ?User $user2 = null;
     #[ORM\ManyToOne(targetEntity:User::class, inversedBy: "conversations1")]
     #[ORM\JoinColumn(name: "user1_id", referencedColumnName: "id", onDelete: "SET NULL")]
    
   private ?User $user1 = null;

   
    #[ORM\ManyToOne(targetEntity:User::class, inversedBy: "conversations2")]
    #[ORM\JoinColumn(name: "user2_id", referencedColumnName: "id", onDelete: "SET NULL")]
   
   private ?User $user2 = null;
   
    public function __construct()
    {
        $this->message = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAd(): ?Ads
    {
        return $this->ad;
    }

    public function setAd(?Ads $Ad): static
    {
        $this->ad = $Ad;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessage(): Collection
    {
        return $this->message;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->message->contains($message)) {
            $this->message->add($message);
            $message->setConversation($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->message->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getConversation() === $this) {
                $message->setConversation(null);
            }
        }

        return $this;
    }

    public function getUser1(): ?User
    {
        return $this->user1;
    }

    public function setUser1(?User $User1): static
    {
        $this->user1 = $User1;

        return $this;
    }

    public function getUser2(): ?User
    {
        return $this->getUser2();
    }

    public function setUser2(?User $User2): static
    {
        $this->user2 = $User2;

        return $this;
    }
}
