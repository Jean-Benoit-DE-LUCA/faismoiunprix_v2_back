<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'message')]
    #[ORM\JoinColumn(name: 'user_id')]
    private ?int $user_id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'message')]
    #[ORM\JoinColumn(name: 'user_receive_id')]
    private ?string $user_receive_id = null;

    #[ORM\ManyToOne(targetEntity: Offer::class, inversedBy: 'message')]
    #[ORM\JoinColumn(name: 'offer_id')]
    private ?int $offer_id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'message')]
    #[ORM\JoinColumn(name: 'user_offer_id')]
    private ?int $user_offer_id = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'message')]
    #[ORM\JoinColumn(name: 'product_id')]
    private ?int $product_id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column]
    private $user_send_id_read = null;

    #[ORM\Column]
    private $user_receive_id_read = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getUserReceiveId()
    {
        return $this->user_receive_id;
    }

    public function setUserReceiveId($user_receive_id)
    {
        $this->user_receive_id = $user_receive_id;

        return $this;
    }

    public function getOfferId(): ?int
    {
        return $this->offer_id;
    }

    public function setOfferId(int $offer_id): static
    {
        $this->offer_id = $offer_id;

        return $this;
    }

    public function getUserOfferId() 
    {
        return $this->user_offer_id;
    }

    public function setUserOfferId(int $user_offer_id)
    {
        $this->user_offer_id = $user_offer_id;

        return $this;
    }

    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    public function setProductId(int $product_id): static
    {
        $this->product_id = $product_id;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getUserReceiveIdRead()
    {

        return $this->user_receive_id_read;
    }

    public function setUserReceiveIdRead($user_receive_id_read)
    {

        $this->user_receive_id_read = $user_receive_id_read;

        return $this;
    }

    public function getUserSendIdRead()
    {

        return $this->user_send_id_read;
    }

    public function setUserSendIdRead($user_send_id_read)
    {

        $this->user_send_id_read = $user_send_id_read;

        return $this;
    }
}
