<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CheckPostConversationController extends AbstractController
{
    public function __invoke(Conversation $conversation): Conversation
    {
        /** @var User $user */
        $user = $this->getUser();
        $targetUser = $conversation->getTargetUser();
        $allOwnedConversation =[];
        foreach ($targetUser->getParticipedConversation()->getValues() as $conv) {
            array_push($allOwnedConversation, $conv->getOwner());
        }
        if (($user !== $targetUser) && (!in_array($user, $allOwnedConversation))) {
            return $conversation;
        } else {
            throw new BadRequestException();
        }
    }
}