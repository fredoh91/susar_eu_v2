<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class RedirectSubscriber implements EventSubscriberInterface
{
    private bool $enableRedirection;
    private string $newServerUrl;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(bool $enableRedirection, string $newServerUrl, UrlGeneratorInterface $urlGenerator)
    {
        $this->enableRedirection = $enableRedirection;
        $this->newServerUrl = $newServerUrl;
        $this->urlGenerator = $urlGenerator;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        // On agit uniquement sur la requête principale (pas les sous-requêtes)
        if (!$event->isMainRequest()) {
            return;
        }

        if ($this->enableRedirection && !empty($this->newServerUrl)) {
            $requestHost = $event->getRequest()->getHost();
            $newServerHost = parse_url($this->newServerUrl, PHP_URL_HOST);

            // Ne pas rediriger si on est déjà sur la page d'information pour éviter une boucle
            if ($event->getRequest()->attributes->get('_route') === 'app_redirect_info') {
                return;
            }

            // On ne redirige pas si on est déjà sur le bon serveur (pour éviter les boucles infinies)
            if ($requestHost === $newServerHost) {
                return;
            }

            $infoUrl = $this->urlGenerator->generate('app_redirect_info');
            $response = new RedirectResponse($infoUrl);
            $event->setResponse($response);
        }
    }

    public static function getSubscribedEvents(): array
    {
        // La priorité doit être inférieure à celle du RouterListener (32)
        // pour que le contexte de la requête (y compris le chemin de base) soit disponible
        // pour le UrlGenerator, mais supérieure à celle du ControllerResolver (0) pour agir avant le contrôleur.
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 31],
        ];
    }
}
