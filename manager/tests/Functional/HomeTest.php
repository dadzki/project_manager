<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeTest extends WebTestCase
{
    public function testGuest(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertSame('http://localhost/login', $client->getResponse()->headers->get('Location'));
    }

    public function testSuccess(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'dadzki@yandex.ru',
            'PHP_AUTH_PW' => '123456',
        ]);
        $crawler = $client->request('GET', '/');

        $this->assertSame(302, $client->getResponse()->getStatusCode());
//        $this->assertContains('Hello', $crawler->filter('h1')->text());
    }
}
