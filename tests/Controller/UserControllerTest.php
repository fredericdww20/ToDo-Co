<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    // Test de la page de connexion
    public function testLoginUser(): void
    {
        // Requête pour accéder à la page de connexion
        $crawler = $this->client->request('GET', '/login');

        // Vérifier que la page de connexion est accessible
        $this->assertResponseIsSuccessful();

        // Remplir le formulaire de connexion
        $form = $crawler->selectButton('Se connecter')->form();
        $form['username'] = 'admin';
        $form['password'] = 'password123';

        // Soumettre le formulaire
        $this->client->submit($form);
        // Vérifier que la connexion a réussi
        $this->assertResponseRedirects('/');
        // Suivre la redirection
        $this->client->followRedirect();
    }


    // Test de la page de liste des utilisateurs
    public function testCreateAction(): void
    {
        // Se connecter avec un utilisateur ayant les permissions de créer un nouvel utilisateur
        $this->testLoginUser();

        // Requête pour accéder à la page de création d'utilisateur
        $crawler = $this->client->request('GET', '/users/create');
        $this->assertResponseIsSuccessful();

        // Sélectionner le formulaire et remplir les champs
        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'Maurdqsdsqdice', // Utiliser un nom d'utilisateur unique
            'user[password][first]' => 'password123',
            'user[password][second]' => 'password123',
            'user[email]' => 'uniqueeqsdsqmdddail@example.com', // Utiliser un email unique
            'user[roles]' => ['ROLE_USER']
        ]);

        // Soumettre le formulaire
        $crawler = $this->client->submit($form);

        // Vérifier le code de statut de la réponse
        if (!$this->client->getResponse()->isRedirect()) {
            echo $this->client->getResponse()->getContent();
        }

        // S'assurer que la réponse est une redirection vers la page de la liste des utilisateurs
        $this->assertResponseRedirects('/users', 302, 'Une redirection vers la liste des utilisateurs était attendue après la soumission du formulaire.');

        // Suivre la redirection
        $crawler = $this->client->followRedirect();

        // Vérifier que la réponse est réussie
        $this->assertResponseIsSuccessful();

        // Vérifier que le message de succès est affiché
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count(), 'Un message de succès était attendu.');
    }


    // Test de la page de liste des utilisateurs
    public function testEditAction(): void
    {
        // Se connecter avec un utilisateur ayant les permissions de créer un nouvel utilisateur
        $this->testLoginUser();

        // Requête pour accéder à la page de modification d'utilisateur
        $crawler = $this->client->request('GET', '/users/26/edit');
        // Vérifier que la page de modification d'utilisateur est accessible
        $this->assertResponseIsSuccessful();

        // Sélectionner le formulaire et remplir les champs
        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = 'Frederique';
        $form['user[password][first]'] = 'password123';
        $form['user[password][second]'] = 'password123';
        $form['user[email]'] = 'frederique@email.fr';

        // Soumettre le formulaire
        $this->client->submit($form);

        // Vérifier que la modification a réussi
        $this->assertResponseRedirects('/users');

        // Suivre la redirection
        $crawler = $this->client->followRedirect();

        // Vérifier que la réponse est réussie
        $this->assertResponseIsSuccessful();

        // Vérifier que le message de succès est affiché
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());
    }

    // Test la suppression d'un utilisateur
    public function testDeleteAction(): void
    {
        // Se connecter avec un utilisateur ayant les permissions de supprimer un utilisateur
        $this->testLoginUser();

        // Requête pour supprimer un utilisateur
        $this->client->request('GET', '/users/27/delete');

        // Vérifier que la suppression a réussi
        $this->assertResponseRedirects('/users');
    }
}
