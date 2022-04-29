<?php 
namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordEncoderSubscriber implements EventSubscriberInterface{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder; 
    } 
    public static function getSubscribedEvents() {
         return [ KernelEvents::VIEW => ['encodePassword', EventPriorities::PRE_WRITE] ]; 
        } 
    public function encodePassword(ViewEvent $event) { 
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();//POST,GET,PUT.. 
            if($user instanceof User && $method === "POST") {
                // dd($user);
                $hash = $this->encoder->encodePassword($user,$user->getPassword());
                //  dd($hash);
                  $user->setPassword($hash); 
                //   dd($user);
                }
             }
 }