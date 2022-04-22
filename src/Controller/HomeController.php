<?php

namespace App\Controller;
use DateTime;
use App\Entity\Ads;
use App\Classe\Mail;
use App\Entity\User;
use App\Entity\Category;
use App\Form\UserType;
use App\Form\Categorieregister;

use App\Form\Adstyperestaurant;
use Doctrine\ORM\Query;
use App\Entity\Commentaire;
use App\Entity\Reservation;
use App\Form\RechercheType;
use App\Form\RegistreAssociationType;
use App\Form\CommentaireType;
use App\Form\RemboursementType;
use App\Form\ConfirmMailType;
use App\Form\ReservationType;
use App\Form\ChangePasswordType;
use App\Form\PasswordChangeType;
use App\Form\RegistreClientType;
use App\Repository\UserRepository;
use App\Form\RegistreOrganismeType;
use App\Form\ChangeFirstLastNameType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('client/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/comingsoon", name="comingsoon")
     */
    public function comingsoon(): Response
    {
        return $this->render('client/comingsoon.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/indexPeace", name="indexPeace")
     */
    public function indexPeace(EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $partenaire = $entityManager->createQuery("SELECT User from App\Entity\User User where User.roles like '%ROLE_COM%' and User.isVisible = 1")
            ->getResult();
            $form = $this->createForm(RechercheType::class, $user);
        return $this->render('client/index-hotel.html.twig', [
            'controller_name' => 'HomeController',
            'salons' => $partenaire,
            'form' => $form->createView(),
        ]);
    }
     /**
     * @Route("/login", name="app_login" , methods={"GET", "POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {   
        // if ($this->getUser()) {
        //     //  dd("blabla");

        //     return $this->redirectToRoute('indexPeace');
        // }
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('client/auth-login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'parameters' => $request->query->all(),
        ]);
    }

    /**
     * @Route("/recherche", name="recherche_home",methods={"POST","GET"})
     */
    public function recherche(Request $request, UserRepository $userRepository, Security $security, EntityManagerInterface $entityManager): \Symfony\Component\HttpFoundation\Response
    {
        $user = new User();

        $form = $this->createForm(RechercheType::class, $user);
        $form->handleRequest($request);
        $partenaire = [];

        $requete = "";
        if ($user->getDomaine()) {
            $recherche = $user->getDomaine();
            $requete .= " and User.domaine like '%$recherche%'";
            // dd($requete);
        }
        if ($user->getDomination()) {
            $recherhce = $user->getDomination();
            $requete .= " and (User.domination like '%$recherhce%' or Ads.titre like '%$recherhce%' or User.domaine like '%$recherhce%')";
            // dd($requete);
        }


        

        $partenaire = $entityManager->createQuery("SELECT User,Ads from App\Entity\User User , App\Entity\Ads Ads where Ads.user = User.id and User.roles like '%ROLE_COM%' and User.isVisible = 1 and Ads.isDisplay = 1 
                                            $requete")
            ->getResult();
        // dd($partenaire);
        $produitUser = [];
        $produitAds = [];
        foreach ($partenaire as $produit) {
            if ($produit instanceof User)
                array_push($produitUser, $produit);
            else
                array_push($produitAds, $produit);
        }


        return $this->render('client/recherche.html.twig', [
            'salons' => $produitUser,
            'ads' => $produitAds,
            'con' => $security->getUser(),
            'form' => $form->createView()

        ]);
    }


     /**
     * @Route("/registreClient", name="registreClient" , methods={"GET", "POST"})
     */
    public function registreClient(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager): Response
    {
        $err="";
        $user = new User();
        $form = $this->createForm(RegistreClientType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && !$form->isValid()) {
            $err = "Vous avez pas bien remplie les champs";
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(["ROLE_USER"]);
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            // dd($user);
            $entityManager->persist($user);
            $entityManager->flush();
            $lastname = $user->getLastname();
            $email = $user->getEmail();
            $mail = new Mail();
            $mail->send($email, $lastname, "Bonjour ", "Merci d'avoir rejoignez notre plateforme PEACE. ");

            return $this->redirectToRoute(('app_login'));
        }

        return $this->render('client/authClient.html.twig', [
            'form' => $form->createView(),
            'err'=>$err,
        ]);
    }

    /**
     * @Route("/registreOrganisation", name="registreOrganisation")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function registreOrganisation(Request $request, UserPasswordEncoderInterface $encoder, AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistreOrganismeType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && !$form->isValid()) {
            dd('ggggg');
        }

        if ($form->isSubmitted() ) {
            $user = $form->getData();
            $user->setRoles(["ROLE_COM"]);
            $asso = $user->getAssociation();
            $image = $form->get('illustration')->getData();
            $partenaire = $entityManager->createQuery("SELECT User from App\Entity\User User where User.id = $asso ")
            ->getResult();
             //dd($user);
            $fichier = md5(uniqid()) . '.' . $image->guessExtension();

            // On copie le fichier dans le dossier uploads
            $image->move(
                $this->getParameter('Ads_files_directory'),
                $fichier
            );

            // On crée l'image dans la base de données
            $user->setIllustration($fichier);

            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setBlocked(true);
            $code = rand(99999, 9999999);

            $user->setActivation($code);
            $user-> setAssociation($partenaire[0]->getDomination());
            // dd($user);
            $entityManager->persist($user);
            $entityManager->flush();
            $lastname = $user->getLastname();
            $email = $user->getEmail();
            $mail = new Mail();
            $mail->send($email, $lastname, "Bonjour ", "Merci d'avoir rejoignez notre plateforme PEACE. le code d'activation est $code");
            $error = $authenticationUtils->getLastAuthenticationError();
            $lastUsername = $authenticationUtils->getLastUsername();
            if($user->getDomaine() == "Restaurant"){
                
                $ads = new Ads();
                $form2 = $this->createForm(Adstyperestaurant::class, $ads);
                $form2->handleRequest($request);
                $ads->setCategorie("Reservation");
                $ads->setUser($user);
                $ads->setTitre("Réserver une table");
                $ads->setPrix("4.5");
                
                $entityManager->persist($ads);
                $entityManager->flush();
    
                $category = new Category();
                $form3 = $this->createForm(Categorieregister::class, $category);
                $form3->handleRequest($request);
                $category->setUser($user);
                $category->setTitre("Reservation");
                $entityManager->persist($category);
                $entityManager->flush();
        
    
                    
            }
            return $this->redirectToRoute(('app_login'));
            
        }
        

        return $this->render('client/authOrganisation.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/registreAssociation", name="registreAssociation")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function registreAssociation(Request $request, UserPasswordEncoderInterface $encoder, AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistreAssociationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            dd('ggggg');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setRoles(["ROLE_ASSO"]);
            $image = $form->get('illustration')->getData();
            $fichier = md5(uniqid()) . '.' . $image->guessExtension();

            // On copie le fichier dans le dossier uploads
            $image->move(
                $this->getParameter('Ads_files_directory'),
                $fichier
            );

            // On crée l'image dans la base de données
            $user->setIllustration($fichier);

            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setBlocked(true);
            $code = rand(99999, 9999999);

            $user->setActivation($code);
            $entityManager->persist($user);
            $entityManager->flush();
            $lastname = $user->getLastname();
            $email = $user->getEmail();
            $mail = new Mail();
            $mail->send($email, $lastname, "Bonjour ", "Merci d'avoir rejoignez notre plateforme PEACE. le code d'activation est $code");
            $error = $authenticationUtils->getLastAuthenticationError();
            $lastUsername = $authenticationUtils->getLastUsername();
            return $this->redirectToRoute(('app_login'));
        }

        return $this->render('client/authAssociation.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/verification", name="verification")
     */
    public function verification(Request $request, Security $security, EntityManagerInterface $entityManager)
    {
        $error = "";
        if ($request->get("code") == $security->getUser()->getActivation()  && $request->get("code"))  {
            $error = "";
            $security->getUser()->setBlocked(false);


            if ($security->getUser()->getRoles()[0] == "ROLE_USER1") {
                $security->getUser()->setRoles(["ROLE_USER"]);
                $entityManager->persist($security->getUser());
                $entityManager->flush();
            }
            if ($security->getUser()->getRoles()[0] == "ROLE_COM1") {
                $security->getUser()->setBlocked(false);
                $security->getUser()->setRoles(["ROLE_COM"]);
                $mail = new Mail();
                if ($security->getUser()->getLastname() == null) {
                    $userName = $security->getUser()->getDomination();
                }
                else{
                    $userName = $security->getUser()->getLastname();
                }
                
                $societeName = $security->getUser()->getDomination();
                $mail->send($security->getUser()->getEmail(), $security->getUser()->getFirstname(), "Votre mail a été confirmé", "Madame/Monsieur <h5>$userName</h5>Votre adresse mail a bien été confirmé");

                $entityManager->persist($security->getUser());
                $entityManager->flush();
            }
            if ($security->getUser()->getRoles()[0] == "ROLE_ASSO1") {
                $security->getUser()->setBlocked(false);
                $security->getUser()->setRoles(["ROLE_ASSO"]);
                $mail = new Mail();
                $userName = $security->getUser()->getLastname();
                $societeName = $security->getUser()->getDomination();
                $mail->send($security->getUser()->getEmail(), $security->getUser()->getFirstname(), "Votre mail a été confirmé", "Madame/Monsieur <h5>$userName</h5>votre adresse mail a bien été confirmé");

                $entityManager->persist($security->getUser());
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_login');
        } elseif($request->get("code")){
            $error = "Votre code de vérification est incorrecte ";
        }
        return $this->render("client/ActivationForm.html.twig", ['controller_name' => 'verification','error' => $error]);
    }
     /**
     * @Route("/confirmeMail", name="confirmeMail") 
     */
    public function confirmeMail(Request $request, UserRepository $userRepository, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        // $user = $userRepository->findOneBy(['email' => $user->getEmail()]) ? $userRepository->findOneBy(['email' => $user->getEmail()]) : $user;
        // dd($user);

        $form = $this->createForm(ConfirmMailType::class, ($user));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $user = $userRepository->findOneBy(['email' => $user->getEmail()])) {
            // dd($user);
            $form = $this->createForm(ConfirmMailType::class, $user);
            $user = $form->getData();
            $code = rand(99999, 9999999);
            $user->setReinit($code);


            $entityManager->persist($user);
            $entityManager->flush();
            $mail = new Mail();
            $userName = $user->getLastname();
            $mail->send($user->getEmail(), $user->getFirstname(), "Modification du mot de passe", "Bonjour Monsieur/Madame <h5>$userName</h5> Votre demande de modification de mot de passe à bien été pris en compte. Afin de finaliser votre demande nous vous prions de bien vouloir entrer ce code <h5>$code</h5> Dans l'espace demandé sur le site.");

            return $this->redirectToRoute('reinitialisation');
        }
        $error = $form->isSubmitted() ? "L'utilisateur n'existe pas" : null;

        return $this->render('client/mdpForget.html.twig', [
            'form' => $form->createView(),
            "error" => $error
        ]);
    }
     /**
     * @Route("/reinitialisation", name="reinitialisation") 
     */
    public function reinitialisation(Request $request, Security $security, UserRepository $userRepository, UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager)
    {
        $error = "";
        $user = new User();
        $form = $this->createForm(PasswordChangeType::class, $user);
        $form->handleRequest($request);
        // dd($user);
        // dd($request->get("code"));
        if ((!($user1 = $userRepository->findOneBy(["reinit" => $request->get("code")])) && $request->get("code")) || !$request->get("code"))
            return $this->render("client/codeRecuperation.html.twig");
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($user);
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user1->setPassword($password);
            $form = $this->createForm(PasswordChangeType::class, $user1);
            // dd($user->getPassword());

            // dd($user->getPassword());

            $user->setReinit(null);
            $entityManager->persist($user1);
            $entityManager->flush();
            return $this->redirectToRoute("app_login");
        }

        return $this->render("client/PasswordChangeForm.html.twig", [
            "form" => $form->createView(),
            "code" => $request->get("code")

        ]);
    }

    /**
     * @Route("/codeVerification",name="codeVerification", methods={"GET","POST"})
     */
    public function codeVerification(Request $request)
    {

        if ($request->get('code'))
            return $this->get('router')->generate('reinitialisation', array('user' => 'id'));
        return $this->render("client/codeRecuperation.html.twig");
    }


    /**
     * @Route("/ajaxRecherche", name="ajaxRecherche")
     */
    public function ajaxRecher(Request $request)
    {

        $recherche = $request->query->get('recherche');

        return new JsonResponse(
            [
                "ads" => $this->getDoctrine()->getManager()->createQuery(
                    "SELECT  Ads
                            FROM App\Entity\Ads Ads
                            WHERE Ads.titre LIKE '%$recherche%' and Ads.isDisplay = 1"
                )->getArrayResult(),
                "Magasin" => $this->getDoctrine()->getManager()->createQuery(
                    "SELECT  User
                            FROM App\Entity\User User
                            WHERE User.domination LIKE '%$recherche%'"
                )->getArrayResult(),
                "Domaine" => $this->getDoctrine()->getManager()->createQuery(
                    "SELECT  User
                            FROM App\Entity\User User
                            WHERE User.domaine LIKE '%$recherche%'"
                )->getArrayResult()
            ]
        );
        return new JsonResponse($userRepository->search($recherche));
    }

    /**
     * @Route("/getCalendar/{id}", name="getCalendar", methods={"GET","POST"})"
     */
    public function getCalendar(Ads $ads, EntityManagerInterface $entityManager)
    {
        $user = $ads->getUser();
        $array = "";
        foreach ($user->getAds() as $ad) {
            // array_push($array,$ad->getId());
            $array .= $ad->getId() . ",";
        }
        return new JsonResponse([
            "calendar" => $entityManager->createQuery("select Calendar from App\Entity\Calendar Calendar where Calendar.user= $user ")->getArrayResult(),
            "reservations" => $entityManager->createQuery("select reservation from App\Entity\Reservation reservation  where reservation.ads in (select ads.id from App\Entity\Ads ads where ads.user=$user ) ")->getArrayResult()
        ]);
    }


    /**
     * @Route("/detailProduitClient/{id}", name="detailProduitClient")
     */
    public function detailProduitClient(Request $request,User $user, EntityManagerInterface $entityManager,CommentaireRepository $commentaireRepository, Security $security): Response
    {
        $domaineuser = $user->getDomaine();
        $partenaire = $entityManager->createQuery("SELECT User from App\Entity\User User where User.roles like '%ROLE_COM%' and User.isVisible = 1 and User.domaine = '$domaineuser' ")
            ->getResult();
        // $reservations = $entityManager->createQuery("SELECT Reservation from App\Entity\Reservation Reservation where Reservation.ads.user='$user'")
        // ->getResult();
        // dd("SELECT Reservation from App\Entity\Reservation Reservation where Reservation.ads.user='$user'");

            $commentaire = new Commentaire();
            $form = $this->createForm(CommentaireType::class, $commentaire);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $commentaire->setClient($security->getUser());
                $commentaire->setUser($user);
                $commentaire->setCreatedAt(new DateTime("now"));
                $entityManager->persist($commentaire);
                $entityManager->flush();
                return $this->redirectToRoute('detailProduitClient', ['id' => $user->getId()]);
    
            }

            
        return $this->render('client/blog-detail.html.twig', [
            'user' => $user,
            'salons' => $partenaire,
            'form' => $form->createView(),
            'commentaires' => $commentaireRepository->findBy([],['createdAt' => 'desc']),
            // 'commentaires' => $commentaireRepository->findBy(["isPaid" => 1], ['createdAt' => 'desc']),
        ]);
    }
    /**
     * @Route("/{id}/supprimerCommentaire/{user}", name="supprimerCommentaire", methods={"GET", "POST"})
     */
    public function supprimerCommentaire(Request $request, Commentaire $commentaire,User $user, CommentaireRepository $commentaireRepository): Response
    {
            $commentaireRepository->remove($commentaire);

            return $this->redirectToRoute('detailProduitClient', ['id' => $user->getId()]);
    }



     /**
     * @Route("/listOrganisme", name="listOrganisme",methods={"POST","GET"})
     */
    public function listOrganisme(Request $request, UserRepository $userRepository, Security $security, EntityManagerInterface $entityManager): \Symfony\Component\HttpFoundation\Response
    {
        $user = new User();

        $form = $this->createForm(RechercheType::class, $user);
        $form->handleRequest($request);
        $partenaire = [];

        $requete = "";
        
        if ($user->getDomaine() == null && $user->getDomination() == null){
            $partenaire = $entityManager->createQuery("SELECT User from App\Entity\User User where User.roles like '%ROLE_COM%' and User.isVisible = 1 ")
            ->getResult();
            //dd($partenaire);
        }
        else{
            if ($user->getDomaine()) {
                $recherche = $user->getDomaine();
                $requete .= " and User.domaine like '%$recherche%'";
            }
            if ($user->getDomination()) {
                $recherhce = $user->getDomination();
                $requete .= " and (User.domination like '%$recherhce%' or Ads.titre like '%$recherhce%' or User.domaine like '%$recherhce%')";
            }
            $partenaire = $entityManager->createQuery("SELECT User,Ads from App\Entity\User User , App\Entity\Ads Ads where Ads.user = User.id and User.roles like '%ROLE_COM%' and Ads.isDisplay = 1 
                                            $requete")
            ->getResult();
        }
        


        

        

         //dd($partenaire);
        $produitUser = [];
        $produitAds = [];
        foreach ($partenaire as $produit) {
            if ($produit instanceof User)
                array_push($produitUser, $produit);
            else
                array_push($produitAds, $produit);
        }


        return $this->render('client/list-organisme.html.twig', [
            'salons' => $produitUser,
            'ads' => $produitAds,
            'con' => $security->getUser(),
            'form' => $form->createView(),
            'assetic'  => array(
                'vars'  => array(
                    'height'  => '400px',
                    'width'  => '500px'
                ))
        

        ]);
    }

    /**
     * @Route("/listProduitOrganisme", name="listProduitOrganisme")
     */
    public function listProduitOrganisme(): Response
    {
        return $this->render('client/list-product-organisme.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/demanderRemboursement/{id}", name="demanderRemboursement")
     */
    public function demanderRemboursement(Request $request,Reservation $reservation, EntityManagerInterface $entityManager, Security $security): Response
    {   
        
        $user= $security->getUser();
        $form = $this->createForm(RemboursementType::class, $reservation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() ) {
            $reservation->setDemanderemboursement(1);
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('profileClient');
        }
       
        return $this->render('client/demandeRemboursement.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/choixReservation/{id}", name="choixReservation")
     */
    public function choixReservation(Request $request,Ads $ads, EntityManagerInterface $entityManager, Security $security): Response
    {   
        
        $user= $security->getUser();
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() ) {
            
            $reservation->setClient($user);
            $reservation->setCreatedAt(new \DateTime('now'));
            $reservation->setAds($ads);
            // $reservation->setEndDate(new  DateTime($request->get("deteheure"))'+ 30 minutes');
            // dd(new \DateTime(date('Y-m-d H:i:s', strtotime('+30 minutes', strtotime($request->get("deteheure"))))));
            $reservation->setEndDate(new \DateTime(date('Y-m-d H:i:s', strtotime('+30 minutes', strtotime($request->get("deteheure"))))));

            // dd($reservation);
            // if($ads->getTitre() == "Reservation"){
            //     $reservation->setPrix(($ads->getPrix()*0.15)+1);
            // }
            $reservation->setDeteheure(new DateTime($request->get("deteheure")));
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('totalProduit', array(
                'reservation' => $reservation->getId()
            ));
        }
       
        return $this->render('client/page-terms.html.twig', [
            'ads' => $ads,
            'formt' => $form->createView(),
        ]);
    }


    /**
     * @Route("/totalProduit", name="totalProduit")
     */
    public function totalProduit(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $reservation = $request->get('reservation');
        $user= $security->getUser();
        $reservations = $entityManager->createQuery("SELECT Reservation from App\Entity\Reservation Reservation where Reservation.client = $user and  Reservation.id = $reservation ")
            ->getResult();
        //  dd($reservations[0]->getAds());
        return $this->render('client/shop-cart.html.twig', [
            'reservations' => $reservations[0],
        ]);
    }
     /**
     * @Route("/profileClient", name="profileClient")
     */
    public function profileClient(Request $request, EntityManagerInterface $entityManager, Security $security, UserPasswordEncoderInterface $encoder): Response
    {
        $err = "";
        $user = $security->getUser();
        $formChangeFirstLastName = $this->createForm(ChangeFirstLastNameType::class, $user);
        $formChangeFirstLastName->handleRequest($request);

        if ($formChangeFirstLastName->isSubmitted() && $formChangeFirstLastName->isValid()) {
            $entityManager->flush();

            // return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        $formChangePassword = $this->createForm(ChangePasswordType::class, $user);
        $formChangePassword->handleRequest($request);
        if ($formChangePassword->isSubmitted() && !$formChangePassword->isValid()) {
            $err = "Vous avez pas bien remplie les champs";
        }
        if ($formChangePassword->isSubmitted() && $formChangePassword->isValid()) {
            //dd($user);
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            // dd($user);
            $entityManager->persist($user);
            $entityManager->flush();
        }

        $reservations = $entityManager->createQuery("SELECT Reservation from App\Entity\Reservation Reservation where Reservation.client = $user ")
            ->getResult();
        return $this->render('client/shop-myaccount.html.twig', [
            'user' => $user,
            'reservations' => $reservations,
            'formChangeFirstLastName' => $formChangeFirstLastName->createView(),
            'formChangePassword' => $formChangePassword->createView(),
            'err'=>$err,

            
        ]);
    }
    /**
     * @Route("/commande/merci/{id}", name="commandeMerci", methods={"GET","POST"})
     */
    public function commandeMerci(Security $security,Request $request, $id, ReservationRepository $reservationRepository): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        $reservation = $reservationRepository->findOneBy(['stripeSessionId' => $id]);
        

       if( $reservation->getAds()->getUser()->getDomaine() == "Restaurant" && $reservation->getAds()->getTitre() == "Réserver une table"){
            if($reservation->getAds()->getUser()->getDone() != null ){
            $reservation->setPrixpeace($reservation->getAds()->getPrix());
            $reservation->setPrixproduit(($reservation->getAds()->getPrix())+$reservation->getAds()->getUser()->getDone());
            $reservation->setPrixdon($reservation->getAds()->getUser()->getDone());
            $reservation->setPrixprofesionnelle(0);
        }
            else{
                $reservation->setPrixpeace($reservation->getAds()->getPrix()*0.2);
            $reservation->setPrixproduit(($reservation->getAds()->getPrix()*0.2)+ 1);
            $reservation->setPrixdon(1);
            $reservation->setPrixprofesionnelle(0);

            }
       }
       else{
            if ( $reservation->getAds()->getUser()->getReduction() != null)
            {
                if($reservation->getAds()->getUser()->getDone() != null ){
                $reservation->setPrixpeace($reservation->getAds()->getPrix()*0.2);
                $reservation->setPrixproduit($reservation->getAds()->getPrix()- ($reservation->getAds()->getPrix() * $reservation->getAds()->getUser()->getReduction() / 100));
                $reservation->setPrixdon($reservation->getAds()->getUser()->getDone());
                $reservation->setPrixprofesionnelle(($reservation->getAds()->getPrix()- ($reservation->getAds()->getPrix() * $reservation->getAds()->getUser()->getReduction() / 100)) - $reservation->getAds()->getUser()->getDone() -$reservation->getAds()->getPrix()*0.2);
            }
                else{
                    $reservation->setPrixpeace($reservation->getAds()->getPrix()*0.2);
                $reservation->setPrixproduit($reservation->getAds()->getPrix()- ($reservation->getAds()->getPrix() * $reservation->getAds()->getUser()->getReduction() / 100));
                $reservation->setPrixdon(1);
                $reservation->setPrixprofesionnelle(($reservation->getAds()->getPrix()- ($reservation->getAds()->getPrix() * $reservation->getAds()->getUser()->getReduction() / 100)) - 1 -$reservation->getAds()->getPrix()*0.2);

                }
            }
        
            else {


                if($reservation->getAds()->getUser()->getDone() != null ){
                    $reservation->setPrixpeace($reservation->getAds()->getPrix()*0.2);
                    $reservation->setPrixproduit($reservation->getAds()->getPrix());
                    $reservation->setPrixdon($reservation->getAds()->getUser()->getDone());
                    $reservation->setPrixprofesionnelle($reservation->getAds()->getPrix() - $reservation->getAds()->getUser()->getDone()-$reservation->getAds()->getPrix()*0.2);
                }
                    else{
                        $reservation->setPrixpeace($reservation->getAds()->getPrix()*0.2);
                    $reservation->setPrixproduit($reservation->getAds()->getPrix());
                    $reservation->setPrixdon(1);
                    $reservation->setPrixprofesionnelle($reservation->getAds()->getPrix() - 1 -$reservation->getAds()->getPrix()*0.2);
        
                    }

                    
            }
       }
        $reservation->setIsPaid(1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reservation);
        $entityManager->flush();
        $email = $security->getUser()->getEmail();
        $lastname= $security->getUser()->getLastname();
        $emailprofessionnel = $reservation->getAds()->getUser()->getEmail();
        $dominationprofessionnel = $reservation->getAds()->getUser()->getDomination();
        $produit = $reservation->getAds()->getTitre();
        $dateresrvation = $reservation->getDeteheure()->format('d-m-Y à H:i:s');
        $adresse = $reservation->getAds()->getUser()->getAdresse()->getNom();
        $mail = new Mail();
        $mail->send($email, $lastname, "Bonjour ", "Monsieur/Madame $lastname Nous vous remercions pour votre commande : $produit  qui est prévu le : <h5>$dateresrvation</h5></br>A l'adresse suivante :<h5> $adresse </h5>. ");
        $mail2 = new Mail();
        $mail2->send($emailprofessionnel, $dominationprofessionnel, "Demande de réservation ", "Monsieur/Madame $lastname souhaite réserver un RDV. la reservation contient le produit :<h3> $produit pour le $dateresrvation</h3>  ");
        return $this->render('reservation_validate/index.html.twig', [
            'reservation' => $reservation,
        ]);
    }
     /**
     * @Route("/commande/erreur/{id}", name="commandeErreur", methods={"GET","POST"})
     */
    public function commandeErreur(Security $security,Request $request, Reservation $reservation): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        $reservation->setIsPaid(0);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reservation);
        $entityManager->flush();
        $email = $security->getUser()->getEmail();
        $lastname= $security->getUser()->getLastname();
        $mail = new Mail();
        $mail->send($email, $lastname, "Réservation non-confirmée ", "Merci d'avoir comander sur notre plateforme PEACE. On vous informe que le paiement n'est bien passé merci de repayer ,vous allez trouvez la commande sur votre espace.  ");
        return $this->render('reservation_cancel/index.html.twig', [
            'reservation' => $reservation,
        ]);
    }
}
