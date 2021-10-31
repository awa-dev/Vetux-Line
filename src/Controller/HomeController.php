<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Upload;
use App\Form\UploadType;
use Symfony\Flex\Response;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function HomeController(Request $request)
    {
        $upload =new Upload();
        $form = $this->createForm(UploadType::class, $upload);
    //traitement du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $files = $upload->getNames(); //récupération du fichier
            $fileName0 = $files[0]->getClientOriginalName(); // définition d'un nouveau nom de fichier
            $fileName1 = $files[1]->getClientOriginalName();

            $files[0]->move($this->getParameter('upload_directory'), $fileName0); //recupération du paramètre
            $files[1]->move($this->getParameter('upload_directory'), $fileName1);
            $upload->setNames(($fileName0));
            $upload->setNames(($fileName1));



            return $this->redirectToRoute('home');//redirection sur le home
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/home/new")
     */
    public function new(MessageGenerator $messageGenerator): Response{
        $message = $messageGenerator->getMessage();
        $this->addFlash('success', $message);
    }
}
