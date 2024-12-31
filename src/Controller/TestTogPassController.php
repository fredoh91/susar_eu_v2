<?php

namespace App\Controller;

use App\Form\TogglePasswordForm;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestTogPassController extends AbstractController
{
    #[Route('/test_tog_pass', name: 'app_test_tog_pass')]
    public function index(): Response
    {
        $form = $this->createForm(TogglePasswordForm::class);

        
        return $this->render('test_tog_pass/index.html.twig', [
            'controller_name' => 'TestTogPassController',
            'form' => $form->createView()
        ]);
    }
}
