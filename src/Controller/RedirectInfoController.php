<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RedirectInfoController extends AbstractController
{
    #[Route('/redirect-info', name: 'app_redirect_info')]
    public function index(): Response
    {
        $newServerUrl = $this->getParameter('new_server_url');

        return $this->render('redirect_info/index.html.twig', [
            'new_server_url' => $newServerUrl,
            'delay' => 15, // secondes
        ]);
    }
}