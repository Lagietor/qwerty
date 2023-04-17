<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile/{slug}', name: 'app_profile')]
    public function index($slug, Request $request, EntityManagerInterface $entityManager): Response
    {
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->find($slug);

        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newUserData = $form->getData();
            
            $imagePath = $form->get('image')->getData();
            if ($imagePath) {
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();
                
                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/images',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $newUserData->setImage('images/' . $newFileName);
            }

            $entityManager->persist($newUserData);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile', ['slug' => $slug]);
        }

        return $this->render('profile/profile.html.twig', [
            'controller_name' => 'ProfileController',
            'form' => $form,
        ]);
    }
}
