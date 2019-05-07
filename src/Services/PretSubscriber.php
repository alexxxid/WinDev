<?php
namespace App\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PretSubscriber implements EventSubscriberInterface
{
    private $token;
    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['getAuthenticatedUser', EventPriorities::PRE_WRITE]
        ];
    }
    public function getAuthenticatedUser(GetResponseForControllerResultEvent $event)
    {
        $entity = $event->getControllerResult(); //récupère l'entité qui a déclenché l'event
        $method = $event->getRequest()->getMethod(); //Methode invoquer dans la request
        $adherent = $this->token->getToken()->getUser(); //adhérent connecter
        if ($entity instanceof Pret && $method == 'POST') {
            $entity->setAdherent($adherent);
        }
        return;
    }
}
