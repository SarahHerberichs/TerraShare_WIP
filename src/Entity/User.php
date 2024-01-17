<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[UniqueEntity(fields: ['pseudo'], message: 'Ce pseudo est déjà utilisé.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    // #[ORM\Column]
    // private array $roles = ['ROLE_USER'];
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Ads::class)]
    private Collection $ads;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: Message::class)]
    private Collection $sendedMessages;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: Message::class)]
    private Collection $receivedMessages;

    public function __construct()
    {
        $this->roles = ['ROLE_USER'];
        $this->ads = new ArrayCollection();
        $this->sendedMessages = new ArrayCollection();
        $this->receivedMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
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
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

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
            $ad->setUser($this);
        }

        return $this;
    }

    public function removeAd(Ads $ad): static
    {
        if ($this->ads->removeElement($ad)) {
            // set the owning side to null (unless already changed)
            if ($ad->getUser() === $this) {
                $ad->setUser(null);
            }
        }

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getSendedMessages(): Collection
    {
        return $this->sendedMessages;
    }

    public function addSendedMessage(Message $sendedMessage): static
    {
        if (!$this->sendedMessages->contains($sendedMessage)) {
            $this->sendedMessages->add($sendedMessage);
            $sendedMessage->setSender($this);
        }

        return $this;
    }

    public function removeSendedMessage(Message $sendedMessage): static
    {
        if ($this->sendedMessages->removeElement($sendedMessage)) {
            // set the owning side to null (unless already changed)
            if ($sendedMessage->getSender() === $this) {
                $sendedMessage->setSender(null);
            }
        }

        return $this;
    }
    public function getReceivedMessages(): Collection
    {
        return $this->receivedMessages ;
    }

    public function addReceivedMessage(Message $sendedMessage): static
    {
        if (!$this->receivedMessages ->contains($sendedMessage)) {
            $this->receivedMessages ->add($sendedMessage);
            $sendedMessage->setReceiver($this); 
        }

        return $this;
    }

    public function removeReceivedMessage(Message $sendedMessage): static
    {
        if ($this->receivedMessages ->removeElement($sendedMessage)) {
            // set the owning side to null (unless already changed)
            if ($sendedMessage->getReceiver() === $this) {
                $sendedMessage->setReceiver(null);
            }
        }

        return $this;
    }
}
