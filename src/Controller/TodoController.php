<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoIsDoneFilterType;
use App\Form\TodoType;
use App\Repository\TodoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/todo')]
class TodoController extends AbstractController
{
    #[Route('/', name: 'app_todo_index', methods: ['GET', 'POST'])]
    public function index(TodoRepository $todoRepository, Request $request): Response
    {

        $orderby = $request->query->get('orderby') ?? 'id';
        $order = $request->query->get('order') ?? 'ASC';

        $filterForm = $this->createForm(TodoIsDoneFilterType::class);
        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {

            $criteria = [];

            $stillTodo = $filterForm->get('stillTodo')->getData();
            if ($stillTodo === true) {
                $criteria = [
                    'done' => false,
                ];
            }

            $searchTerms = $filterForm->get('searchTerms')->getData();

            $todos = $todoRepository->search($searchTerms, $criteria);

        }else{

            $todos =  $todoRepository->findFullTodo($orderby, $order);

        }

        return $this->render('todo/index.html.twig', [
            'todos' => $todos,
            'order' => $order == 'ASC'?'DESC':'ASC',
            'filterForm' => $filterForm
        ]);
    }

    #[Route('/new', name: 'app_todo_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TodoRepository $todoRepository): Response
    {
        $todo = new Todo();
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $todoRepository->save($todo, true);

            return $this->redirectToRoute('app_todo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('todo/new.html.twig', [
            'todo' => $todo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_todo_show', methods: ['GET'])]
    public function show(Todo $todo): Response
    {
        return $this->render('todo/show.html.twig', [
            'todo' => $todo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_todo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Todo $todo, TodoRepository $todoRepository): Response
    {
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $todoRepository->save($todo, true);

            return $this->redirectToRoute('app_todo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('todo/edit.html.twig', [
            'todo' => $todo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_todo_delete', methods: ['POST'])]
    public function delete(Request $request, Todo $todo, TodoRepository $todoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$todo->getId(), $request->request->get('_token'))) {
            $todoRepository->remove($todo, true);
        }

        return $this->redirectToRoute('app_todo_index', [], Response::HTTP_SEE_OTHER);
    }
}
