<?php

namespace App\Entity;

use App\Repository\MessageContactRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageContactRepository::class)]
class MessageContact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'message_contact')]
    #[ORM\JoinColumn(name: 'user_product_id', nullable: false)]
    private $user_product = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'message_contact')]
    #[ORM\JoinColumn(name: 'user_send_id', nullable: false)]
    private $user_send = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'message_contact')]
    #[ORM\JoinColumn(name: 'user_receive_id', nullable: false)]
    private $user_receive = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'message_contact')]
    #[ORM\JoinColumn(name: 'product_id', nullable: false)]
    private $product = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\Column]
    private $created_at = null;

    #[ORM\Column]
    private $updated_at = null;

    #[ORM\Column]
    private $conversation_code = null;

    #[ORM\Column]
    private $user_send_id_status = null;

    #[ORM\Column]
    private $user_receive_id_status = null;

    #[ORM\Column]
    private $user_send_id_read = null;

    #[ORM\Column]
    private $user_receive_id_read = null;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserProduct()
    {
        return $this->user_product;
    }

    public function setUserProduct($user_product)
    {
        $this->user_product = $user_product;

        return $this;
    }

    public function getUserSend()
    {
        return $this->user_send;
    }

    public function setUserSend($user_send)
    {
        $this->user_send = $user_send;

        return $this;
    }

    public function getUserReceive()
    {
        return $this->user_receive;
    }

    public function setUserReceive($user_receive)
    {
        $this->user_receive = $user_receive;

        return $this;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setProduct($product)
    {
        $this->product = $product;

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

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getConversationCode()
    {
        return $this->conversation_code;
    }

    public function setConversationCode($conversation_code)
    {
        $this->conversation_code = $conversation_code;

        return $this;
    }

    public function getUserSendIdStatus()
    {
        return $this->user_send_id_status;
    }

    public function setUserSendIdStatus($user_send_id_status)
    {
        $this->user_send_id_status = $user_send_id_status;

        return $this;
    }

    public function getUserReceiveIdStatus()
    {

        return $this->user_receive_id_status;
    }

    public function setUserReceiveIdStatus($user_receive_id_status)
    {

        $this->user_receive_id_status = $user_receive_id_status;

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
