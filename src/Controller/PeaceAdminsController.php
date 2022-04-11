<?php

namespace App\Controller;
use DateTime;

use App\Entity\Ads;
use App\Entity\User;
use App\Classe\Mail;
use App\Form\AdsType;
use App\Form\UserType;
use App\Entity\Calendar;
use App\Entity\Category;
use App\Form\CalendarType;
use App\Form\CategoryType;
use App\Form\ChangePasswordType;
use App\Form\reserverDirectAssociation;
use App\Form\ReservationAssociationType;
use App\Form\associationinfoType;
use App\Entity\Reservation;
use App\Form\MotifRefusType;
use App\Form\modifierAdsType;
use App\Repository\CalendarRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/PeaceAdmins")
 */
class PeaceAdminsController extends AbstractController
{
    /**
     * @Route("/indexAdmin", name="indexAdmin", methods={"GET"})
     */
    public function indexAdmin(ReservationRepository $reservationRepository, Security $security,EntityManagerInterface $entityManager): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        $con = $security->getuser();

        $events = $entityManager->createQuery("select reserv from App\Entity\Reservation reserv,App\Entity\Ads ads ")->getResult();
        // dd($events[0]->getAds()->getUser()->getDomination());
        $rdvs = [];
        foreach ($events as $event) {
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getDeteheure()->format('Y-m-d H:i:s'),
                'end' => $event->getClient()->getLastname(),
                'title' => $event->getAds()->getUser()->getDomination(),
                'description' => $event->getAds()->getTitre(),
            ];
        }
        $data = json_encode($rdvs);
        return $this->render('admin/indexAdmin.html.twig', compact('data'));
    }


     /**
     * @Route("/profileAdmin", name="profileAdmin")
     */
    public function profileAdmin(Request $request, EntityManagerInterface $entityManager, Security $security, UserPasswordEncoderInterface $encoder): Response
    {
        $err = "";
        $user = $security->getUser();
        

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

        
        return $this->render('admin/profile.html.twig', [
            'user' => $user,
            'formChangePassword' => $formChangePassword->createView(),
            'err'=>$err,

            
        ]);
    }



    /**
     * @Route("/listProfessionnel", name="listProfessionnel")
     */
    public function listProfessionnel(UserRepository $userRepository, Security $security,EntityManagerInterface $entityManager): Response
    {


        $partenaire = [];
        $partenaire = $entityManager->createQuery("SELECT User from App\Entity\User User where User.roles like '%ROLE_COM%'")
            ->getResult();
        //dd($partenaire);            

        return $this->render('admin/listProfessionelle.html.twig', [
            'users' => $partenaire,
            'con' => $security->getUser(),

        ]);
    }
    /**
     * @Route("/listClient", name="listClient")
     */
    public function listClient(UserRepository $userRepository, Security $security,EntityManagerInterface $entityManager): Response
    {


        $partenaire = [];
        $partenaire = $entityManager->createQuery("SELECT User from App\Entity\User User where User.roles like '%ROLE_USER%'")
            ->getResult();
        //dd($partenaire);            

        return $this->render('admin/listClient.html.twig', [
            'users' => $partenaire,
            'con' => $security->getUser(),

        ]);
    }
     /**
     * @Route("/listAdminProduitPro/{id}", name="listAdminProduitPro")
     */
    public function listAdminProduitPro(User $user,UserRepository $userRepository, Security $security,EntityManagerInterface $entityManager): Response
    {


        $produits = [];
        $produits = $entityManager->createQuery("SELECT Ads from App\Entity\User User , App\Entity\Ads Ads where Ads.user = $user and User.id = Ads.user")
            ->getResult();
        // dd($produits);            

        return $this->render('admin/listAdminProduitPro.html.twig', [
            'produits' => $produits,
            'con' => $security->getUser(),
            'user' => $user,

        ]);
    }
    /**
     * @Route("/listAdminReservationsPro/{id}", name="listAdminReservationsPro")
     */
    public function listAdminReservationsPro(User $user,UserRepository $userRepository, Security $security,EntityManagerInterface $entityManager): Response
    {


        $reservations = [];
        $reservations = $entityManager->createQuery("SELECT Reservation from App\Entity\User User , App\Entity\Reservation Reservation, App\Entity\Ads Ads where Reservation.ads = Ads.id and Ads.user = $user and Reservation.isPaid = '1'")
            ->getResult();
        // dd($reservations);            

        return $this->render('admin/listAdminReservationsPro.html.twig', [
            'reservations' => $reservations,
            'con' => $security->getUser(),
            'user' => $user,

        ]);
    }
  
}
