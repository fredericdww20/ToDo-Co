<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class TaskController extends AbstractController
{
    // Afficher la liste des tâches
    #[Route('/tasks', name: 'task_list')]
    public function listAction(EntityManagerInterface $em, Security $security): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        // Récupérer l'utilisateur connecté
        $user = $security->getUser();

        // Récupérer les tâches de l'utilisateur connecté
        $tasks = $em->getRepository(Task::class)->findBy(['user' => $user]);

        // Afficher la liste des tâches
        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

    // Créer une tâche
    #[Route('/tasks/create', name: 'task_create')]
    public function createAction(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Créer une nouvelle tâche
        $task = new Task();

        // Créer le formulaire
        $form = $this->createForm(TaskType::class, $task);

        // Gérer la requête
        $form->handleRequest($request);

        // Vérifier si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($this->getUser());

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        // Afficher le formulaire
        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    // Modifier une tâche
    #[Route('/tasks/{id}/edit', name: 'task_edit')]
    public function editAction(Task $task, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('TASK_EDIT', $task);

        // Créer le formulaire
        $form = $this->createForm(TaskType::class, $task);

        // Gérer la requête
        $form->handleRequest($request);

        // Vérifier si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        // Afficher le formulaire de modification
        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    // Supprimer une tâche
    #[Route('/tasks/{id}/delete', name: 'task_delete')]
    public function deleteAction(Task $task, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('TASK_DELETE', $task);

        // Supprimer la tâche
        $em->remove($task);
        $em->flush();

        // Afficher un message de confirmation
        $this->addFlash('success', 'La tâche a bien été supprimée.');

        // Rediriger vers la liste des tâches
        return $this->redirectToRoute('task_list');
    }


    // Marquer une tâche comme faite ou non faite
    #[Route('/tasks/{id}/toggle', name: 'task_toggle')]
    public function toggleTaskAction(Task $task, EntityManagerInterface $entityManager): Response
    {
        $task->toggle(!$task->isDone());
        $entityManager->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    // Afficher la liste des tâches terminées
    #[Route('/tasks/completed', name: 'task_completed_list')]
    public function completedList(EntityManagerInterface $em, Security $security): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $security->getUser();

        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour voir cette page.');
        }

        // Récupérer les tâches terminées de l'utilisateur connecté
        $completedTasks = $em->getRepository(Task::class)->findBy([
            'user' => $user,
            'isDone' => true, // Filtrer les tâches terminées
        ]);

        return $this->render('task/completed_list.html.twig', [
            'completedTasks' => $completedTasks,
        ]);
    }
}
