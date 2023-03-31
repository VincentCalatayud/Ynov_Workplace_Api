<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class PrivateMessageTest extends ApiTestCase
{
  public function testPostPrivateMessageUserIsInConversation()
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
    $token = $data['token'];
    $user->setDefaultOptions(['headers' => ['authorization' => 'Bearer ' . $token]]);

    // We create a new conversation
    $response = $user->request('POST', '/api/conversations', ['json' => [
      'targetUser' => '/api/users/2',
    ]]);
    $this->assertResponseIsSuccessful();

    $data = $response->toArray();

    $user->request('POST', '/api/private_messages', ['json' => [
      'content' => 'Salut',
      'relatedConversation' => $data['@id']
    ]]);

    $this->assertResponseStatusCodeSame(201);
  }

  public function testPostPrivateMessageUserIsNotInConversation()
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
    $token = $data['token'];
    $user->setDefaultOptions(['headers' => ['authorization' => 'Bearer ' . $token]]);

    $user->request('POST', '/api/private_messages', ['json' => [
      'content' => 'Salut',
      'relatedConversation' => '/api/conversations/1'
    ]]);

    $this->assertResponseStatusCodeSame(400);
  }
}