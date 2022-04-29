<?php
namespace App\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JwtCreatedEvent;

class JwtCreatedSubscriber{
    
    public function updateJwtData(JwtCreatedEvent $event){
        // dd($event);
    }
}