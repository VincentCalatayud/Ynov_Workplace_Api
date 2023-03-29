<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetConversationCollectionController extends AbstractController
{
    public function __invoke(): array
    {
        $user = $this->getUser();
        /** @var User $user */
        $conversations = array_merge($user->getParticipedConversation()->getValues(), $user->getOwnedConversation()->getValues());
        return $conversations;
    }
}