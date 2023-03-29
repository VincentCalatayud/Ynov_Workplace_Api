<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\GetConversationCollectionController;
use App\Controller\GetConversationController;
use App\Repository\ConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ConversationRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/conversations',
            controller: GetConversationCollectionController::class,
            normalizationContext: ['groups' => ['conversation:read']],
            security: "is_granted('ROLE_USER')",
            name: 'conversation_limited'
        ),
        new Post(denormalizationContext: ['groups' => ['conversation:write']], security: "is_granted('ROLE_USER')"),
        new Get(
            uriTemplate: '/conversation/{id}',
            controller: GetConversationController::class,
            normalizationContext: ['groups' => ['conversation:read', 'conversation:inspect']],
            security: "is_granted('ROLE_USER')",
            name: 'conversation_limited'
            
        ),
        new Delete(security: "is_granted('ROLE_ADMIN') or object.owner == user"),
    ],
    normalizationContext: ['groups' => ['conversation:read']],
    denormalizationContext: ['groups' => ['conversation:write', 'conversation:update']],
)]
class Conversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ownedConversation')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[Groups(['conversation:write'])]
    #[ORM\ManyToOne(inversedBy: 'participedConversation')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $targetUser = null;

    #[ORM\OneToMany(mappedBy: 'relatedConversation', targetEntity: PrivateMessage::class, orphanRemoval: true)]
    private Collection $privateMessages;

    public function __construct()
    {
        $this->privateMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTargetUser(): ?User
    {
        return $this->targetUser;
    }

    public function setTargetUser(?User $targetUser): self
    {
        $this->targetUser = $targetUser;

        return $this;
    }

    /**
     * @return Collection<int, PrivateMessage>
     */
    public function getPrivateMessages(): Collection
    {
        return $this->privateMessages;
    }

    public function addPrivateMessage(PrivateMessage $privateMessage): self
    {
        if (!$this->privateMessages->contains($privateMessage)) {
            $this->privateMessages->add($privateMessage);
            $privateMessage->setRelatedConversation($this);
        }

        return $this;
    }

    public function removePrivateMessage(PrivateMessage $privateMessage): self
    {
        if ($this->privateMessages->removeElement($privateMessage)) {
            // set the owning side to null (unless already changed)
            if ($privateMessage->getRelatedConversation() === $this) {
                $privateMessage->setRelatedConversation(null);
            }
        }

        return $this;
    }
}
