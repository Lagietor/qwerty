<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Question;
use App\Entity\User;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Question::class);
        $questions = $repository->findAll();

        return $this->render('question/homepage.html.twig', [
            'questions' => $questions,
        ]);
    }

    /**
     * @Route("/questions/{slug}", name="app_question_show")
     */
    public function show($slug, EntityManagerInterface $entityManager, Request $request)
    {
        $repository = $entityManager->getRepository(Question::class);
        $question = $repository->find($slug);

        $comments = $question->getComments();

        $commentForm = $this->getCommentForm($request, $entityManager);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $this->saveNewComment($commentForm, $question, $this->getUser(), $entityManager);

            return $this->redirectToRoute('app_question_show', ['slug' => $slug]);
        }

        return $this->render('question/show.html.twig', [
            'question' => $question,
            'comments' => $comments,
            'controller_name' => 'QuestionController',
            'commentForm' => $commentForm,
        ]);
    }

    private function getCommentForm(Request $request)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        return $form;
    }

    private function saveNewComment($form, Question $question, User $user, EntityManagerInterface $entityManager)
    {
        $comment = new Comment();

        $comment = $form->getData();
        $comment->setQuestion($question);
        $comment->setUser($user);

        $entityManager->persist($comment);
        $entityManager->flush();
    }
}
