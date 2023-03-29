<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PrivateMessageRepository;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PrivateMessageRepository::class)]
#[ApiResource(
    operations: [
        new Post(denormalizationContext: ['groups' => ['privatemessage:write']], security: "is_granted('ROLE_USER')"),
        new Delete(security: "is_granted('ROLE_ADMIN') or object.owner == user"),
    ],
)]
#[ApiResource(
    uriTemplate: '/conversations/{conversation_id}/private_messages',
    operations: [ new GetCollection() ],
    uriVariables: [
        'conversation_id' => new Link(toProperty: 'relatedConversation', fromClass: Conversation::class),
    ],
    denormalizationContext: ['groups' => ['privatemessage:read']]
)]
class PrivateMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['privatemessage:write'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'ownedPrivateMessages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[Groups(['privatemessage:write'])]
    #[ORM\ManyToOne(inversedBy: 'privateMessages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Conversation $relatedConversation = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getRelatedConversation(): ?Conversation
    {
        return $this->relatedConversation;
    }

    public function setRelatedConversation(?Conversation $relatedConversation): self
    {
        $this->relatedConversation = $relatedConversation;

        return $this;
    }
}
