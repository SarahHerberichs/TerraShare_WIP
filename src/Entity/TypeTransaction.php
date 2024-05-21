<?php

namespace App\Entity;

use App\Repository\TypeTransactionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeTransactionRepository::class)]
class TypeTransaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'typeTransactions')]
    private ?Type $Type = null;

    #[ORM\ManyToOne(inversedBy: 'typeTransactions')]
    private ?Transaction $Transaction = null;

    public function getId(): ?int
    {
        return $this->id;
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
}
