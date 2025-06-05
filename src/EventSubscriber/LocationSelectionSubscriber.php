<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\KernelEvents;

class LocationSelectionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        private RouterInterface $router
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        // Ignorer certaines routes (login, sélection, admin, etc.)
        $ignoredRoutes = [
            'app_login',
            'user_select_location',
            'app_logout',
        ];

        if (in_array($request->attributes->get('_route'), $ignoredRoutes, true)) {
            return;
        }

        $user = $this->security->getUser();

        if ($user && in_array('ROLE_USER', $user->getRoles(), true)) {
            $session = $request->getSession();

            if (!$session->has('selected_location_id')) {
                $url = $this->router->generate('user_select_location');
                $event->setResponse(new RedirectResponse($url));
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }
}
