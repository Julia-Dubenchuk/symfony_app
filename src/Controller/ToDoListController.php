<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;

class ToDoListController extends AbstractController
{
    /**
     * @Route("/", name="app_to_do_list")
     */
    public function index(): Response
    {
        $tasks = $this->getDoctrine()
            ->getRepository(Task::class)
            ->findBy([], ['id' => 'DESC']);

        return $this->render('to_do_list/index.html.twig', [
            'controller_name' => 'ToDoListController',
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/create", name="create_task", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $title = trim($request->request->get('title'));

        if (empty($title)) {
            return $this->redirectToRoute('app_to_do_list');
        }

        $entityManager = $this->getDoctrine()->getManager();

        $task = new Task();
        $task->setTitle($title);

        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('app_to_do_list');
    }

    /**
     * @Route("/switch-status/{id}", name="switch_status")
     */
    public function switchStatus($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        $task->setStatus(!$task->getStatus());
        $entityManager->flush();
        return $this->redirectToRoute('app_to_do_list');
    }

    /**
     * @Route("/delete/{id}", name="task_delete")
     */
    public function delete($id): Response
    {
        exit('to do: delete a task with the id of ' . $id);
    }
}
