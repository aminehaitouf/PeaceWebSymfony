<?php
namespace App\Security;

use App\Classe\Mail;
use App\Entity\User; // your user entity
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class GoogleAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $em;
    private $router;
    private $encoder;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router,UserPasswordEncoderInterface $encoder)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
	$this->router = $router;
    $this->encoder = $encoder;

    }

    public function supports(Request $request)
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->getPathInfo() == '/connect/google/check' && $request->isMethod('GET');
    }

    public function getCredentials(Request $request)
    {

        return $this->fetchAccessToken($this->getGoogleClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var GoogleUser $googleUser */
        $googleUser = $this->getGoogleClient()
            ->fetchUserFromToken($credentials);

        $email = $googleUser->getEmail();

        $user = $this->em->getRepository(User::class)
            ->findOneBy(['email' => $email]);

        if (!$user) {
            $user=new User();
            $code = rand(9999, 99999);
            $password = $this->encoder->encodePassword($user,$googleUser->getLastname().'&'.$code);
            $pass=$googleUser->getLastname().'&'.$code;
            $user->setEmail($googleUser->getEmail());
            $user->setRoles(["ROLE_USER"]);
            $user->setFirstname($googleUser->getFirstname());
            $user->setLastname($googleUser->getLastname());
            $user->setIllustration($googleUser->getAvatar());
            $user->setPassword($password);
            // $user->setCdg(true);

             $this->em->persist($user);
            $this->em->flush();
            $email = $user->getEmail();
            $mail = new Mail();
            $mail->send($email, $lastname, "Bonjour ", "Merci d'avoir rejoignez notre plateforme PEACE. ");

            // NOTE : A FAIRE LE MAILER 
            // $mail = new Mailer();
            // $userName = $googleUser->getLastname();
            // $mail->send($googleUser->getEmail(), $googleUser->getFirstname(), "Bienvenue chez THOUMA", "Monsieur/Madame <h5>$userName</h5> Votre inscription s'est bien déroulée. Votre mot de passe est : <h5>$pass/h5> .</br>Pensez vous de changer votre mot de passe aprés la connexion. Merci d'avoir choisi Thouma");
        }
        

        return $user;
    }

    /**
     * @return \KnpU\OAuth2ClientBunble\Client\OAuth2Client
     */
    private function getGoogleClient()
    {
        return $this->clientRegistry
            // "facebook_main" is the key used in config/packages/knpu_oauth2_client.yaml
            ->getClient('google');
	}

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        
        
        $role = $token->getRoleNames();
        if (in_array("ROLE_ADMIN", $role)) {
            return new RedirectResponse($this->router->generate('indexAdmin'));
        }
        elseif (in_array("ROLE_COM", $role)){
            return new RedirectResponse($this->router->generate('indexProfessionnel'));
        }
        elseif (in_array("ROLE_ASSO", $role)){
            return new RedirectResponse($this->router->generate('indexAssociation'));
        }
        
        if ($req=$request->query->get("name")){
            unset($req['name']);
           // dd($req);
             return new RedirectResponse($this->router->generate($request->query->get('name'),$req));
        }
        return new RedirectResponse($this->router->generate('indexPeace'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse("/login");
    }

    // ...
}
