<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class ConversationsTest extends ApiTestCase
{
    public function testPostCreatedConversation()
    {
        $user = static::createClient();
        $user->disableReboot();
        $user->request('POST', '/api/users', ['json' => [
            'nickname' => 'vincent',
            'email' => 'vincent@gmail.com',
            'plainPassword' => 'azerty123',
        ]]);

        $response = $user->request('POST', '/auth', ['json' => [
            'email' => 'vincent@gmail.com',
            'password' => 'azerty123',
        ]]);
        $this->assertResponseIsSuccessful();

        $data = $response->toArray();
        $user->setDefaultOptions(['headers' => ['authorization' => 'Bearer ' . $data['token']]]);

        $user->request('POST', '/api/conversations', ['json' => [
            'targetUser' => '/api/users/2',
        ]]);

        $this->assertResponseStatusCodeSame(201);
    }
}