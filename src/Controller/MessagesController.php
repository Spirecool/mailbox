<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessagesType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessagesController extends AbstractController
{
    #[Route('/messages', name: 'app_messages')]
    public function index(): Response
    {
        return $this->render('messages/index.html.twig', [
            'controller_name' => 'MessagesController',
        ]);
    }

    #[Route('/send', name: 'app_send')]
    public function send(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $message = new Message();
        $form = $this->createForm(MessagesType::class, $message);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $message->setSender($this->getUser());

            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash("success", "Message successfully sent");
            return $this->redirectToRoute("app_messages");
        }

        return $this->render("messages/send.html.twig", [
            "form" => $form->createView()
        ]);
    }

    
    #[Route('/received', name: 'app_received')]
    public function received(): Response
    {
        return $this->render('messages/received.html.twig');
    }
}


    