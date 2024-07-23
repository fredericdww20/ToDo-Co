<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    // Test de la page de connexion
    public function loginUser(): void
    {
        // Requête pour accéder à la page de connexion
        $crawler = $this->client->request('GET', '/login');

        // Vérifier que la page de connexion est accessible
        $form = $crawler->selectButton('Se connecter')->form();
        $form['username'] = 'user';
        $form['password'] = 'password123';

        // Soumettre le formulaire
        $this->client->submit($form);

        // Vérifier que la connexion a réussi
        $this->assertResponseRedirects('/');

        // Suivre la redirection
        $this->client->followRedirect();
    }

    // Test de la page de liste des tâches
    public function testListAction(): void
    {
        // Se connecter avec un utilisateur ayant les permissions de créer un nouvel utilisateur
        $this->loginUser();
        // Requête pour accéder à la page de liste des tâches
        $this->client->request('GET', '/tasks');
        // Vérifier que la page de liste des tâches est accessible
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    // Test de la page de création d'une tâche
    public function testCreateAction(): void
    {
        // Se connecter avec un utilisateur ayant les permissions de créer un nouvel utilisateur
        $this->loginUser();

        // Requête pour accéder à la page de création d'une tâche
        $crawler = $this->client->request('GET', '/tasks/create');

        // Vérifier que la page de création d'une tâche est accessible
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Sélectionner le formulaire et remplir les champs
        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Titre de la tâche';
        $form['task[content]'] = 'Description de la tâche';

        // Soumettre le formulaire
        $this->client->submit($form);

        // Vérifier que la création de la tâche a réussi
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        // Suivre la redirection
        $crawler = $this->client->followRedirect();

        // Vérifier que la page de liste des tâches est accessible
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Vérifier que le message de succès est affiché
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());
    }

    // Test de la page de modification d'une tâche
    public function testEditAction()
    {
        // Se connecter avec un utilisateur ayant les permissions de créer un nouvel utilisateur
        $this->loginUser();

        // Requête pour accéder à la page de modification d'une tâche
        $crawler = $this->client->request('GET', '/tasks/16/edit');

        // Vérifier que la page de modification d'une tâche est accessible
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Sélectionner le formulaire et remplir les champs
        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'Titre modifié';
        $form['task[content]'] = 'Description modifiée';

        // Soumettre le formulaire
        $this->client->submit($form);

        // Vérifier que la modification de la tâche a réussi
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        // Suivre la redirection
        $crawler = $this->client->followRedirect();

        // Vérifier que la page de liste des tâches est accessible
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Vérifier que le message de succès est affiché
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());
    }

    // Test de la page qui permet de marquer une tâche comme faite
    public function testToggleAction()
    {
        // Se connecter avec un utilisateur ayant les permissions de créer un nouvel utilisateur
        $this->loginUser();

        // Requête pour marquer une tâche comme faite
        $this->client->request('GET', '/tasks/20/toggle');

        // Vérifier que la modification de la tâche a réussi
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        // Suivre la redirection
        $crawler = $this->client->followRedirect();

        //  Vérifier que la page de liste des tâches est accessible
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Vérifier que le message de succès est affiché
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());
    }

    // Test la suppression d'une tâche
    public function testDeleteAction()
    {
        // Se connecter avec un utilisateur ayant les permissions de supprimer un utilisateur
        $this->loginUser();

        // Requête pour supprimer une tâche
        $this->client->request('GET', '/tasks/23/delete');

        // Vérifier que la suppression a réussi
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        // Suivre la redirection
        $crawler = $this->client->followRedirect();

        // Vérifier que la page de liste des tâches est accessible
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        // Vérifier que le message de succès est affiché
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());
    }
}
