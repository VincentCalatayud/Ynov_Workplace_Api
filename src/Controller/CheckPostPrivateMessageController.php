<?php

namespace App\Controller;

use App\Entity\PrivateMessage;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CheckPostPrivateMessageController extends AbstractController
{
    public function __invoke(PrivateMessage $privateMessage): PrivateMessage
    {
        /** @var User $user */
        $user = $this->getUser();
        $conversation = $privateMessage->getRelatedConversation();
        if ($user === $conversation->getOwner() || $user === $conversation->getTargetUser()) {
            return $privateMessage;
        } else {
            throw new BadRequestException();
        }
    }
}