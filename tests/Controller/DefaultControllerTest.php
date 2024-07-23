<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    // Test la page d'accueil
    public function testIndex()
    {
        $client = static::createClient();
        
        $client->request('GET', '/');
        
        $this->assertResponseIsSuccessful();
    }
}
