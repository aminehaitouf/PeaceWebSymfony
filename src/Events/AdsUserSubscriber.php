<?php 
namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Ads;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Security\Core\Security;

class AdsUserSubscriber implements EventSubscriberInterface{

    private $security;
    public function __construct(Security $security){
        $this->security = $security; 
    } 
    public static function getSubscribedEvents() {
         return [ KernelEvents::VIEW => ['setUserForAds', EventPriorities::PRE_VALIDATE] ]; 
        } 
    public function setUserForAds(ViewEvent $event) { 
        $ads = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();//POST,GET,PUT.. 
        
            if($ads instanceof Ads && $method === "POST") {
                $user = $this->security->getUser();
                // dd($user);
                $ads->setUser($user);
                
                }
             }
 }