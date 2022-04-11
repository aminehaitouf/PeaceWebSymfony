<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\FacebookUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FacebookController extends AbstractController
{
    /**
     * Link to this controller to start the "connect" process
     * @Route("/connect/facebook", name="connect_facebook")
     */
    public function connectAction(ClientRegistry $clientRegistry): RedirectResponse
    {
        /** @var FacebookClient $client */
        $client = $clientRegistry->getClient('facebook');
        return $client->redirect(['public_profile', 'email']);
    }

    /**
     * @Route("/connect/facebook/check", name="connect_facebook_check")
     */
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
    {
        /** @var FacebookClient $client */
        $client = $clientRegistry->getClient('facebook');
        try {
            // the exact class depends on which provider you're using
            /** @var FacebookUser $user */
            $user = $client->fetchUser();
            // do something with all this new power!
            // e.g. $name = $user->getFirstName();
            // ...
        } catch (IdentityProviderException $e) {
            // something went wrong!
            // probably you should return the reason to the user
            var_dump($e->getMessage()); die;
        }
    }
}
