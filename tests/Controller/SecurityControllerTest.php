<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    // Test de la page de connexion
    public function testLogin()
    {
        // Création d'un client
        $client = static::createClient();

        // Requête pour accéder à la page de connexion
        $client->request('GET', '/login');

        // Vérifier que la page de connexion est accessible
        $form = $client->getCrawler()->selectButton('Se connecter')->form();

        // Remplir les champs du formulaire
        $form['username'] = 'user';
        $form['password'] = 'password123';

        // Soumettre le formulaire
        $client->submit($form);

        // Vérifier que la connexion a réussi
        $this->assertResponseRedirects('/');
    }
}
