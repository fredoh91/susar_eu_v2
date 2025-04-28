<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Doctrine\ORM\EntityManagerInterface;

final class LoginListener
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[AsEventListener(event: 'security.interactive_login')]
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        // Récupérer l'utilisateur connecté
        $user = $event->getAuthenticationToken()->getUser();

        // Vérifier que l'utilisateur est une instance de votre entité User
        if ($user instanceof \App\Entity\User) {
            // Mettre à jour la date de dernière connexion
            $user->setDateDerniereConnexion(new \DateTime());

            // Persister les modifications
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }
}
