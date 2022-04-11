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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/association")
 */
class AssociationController extends AbstractController
{
    /**
     * @Route("/indexAssociation", name="indexAssociation", methods={"GET"})
     */
    public function index(ReservationRepository $reservationRepository, Security $security,EntityManagerInterface $entityManager): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        $con = $security->getuser();

        $events = $entityManager->createQuery("select reserv from App\Entity\Reservation reserv,App\Entity\Ads ads where 
        reserv.client = $con")->getResult();
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
        return $this->render('association/indexAssociation.html.twig', compact('data'));
    }
        /**
     * @Route("/profilAssociation", name="profilAssociation", methods={"GET", "POST"})
     */
    public function profilAssociation(UserPasswordEncoderInterface $encoder,Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        $user = $security->getUser();
        $form = $this->createForm(associationinfoType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('illustration')->getData();
            $fichier = md5(uniqid()) . '.' . $image->guessExtension();

            // On copie le fichier dans le dossier uploads
            $image->move(
                $this->getParameter('Ads_files_directory'),
                $fichier
            );

            // On crée l'image dans la base de données
            $user->setIllustration($fichier);
            $entityManager->flush();
            

            return $this->redirectToRoute('indexProfessionnel', [], Response::HTTP_SEE_OTHER);
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
            return $this->redirectToRoute('indexProfessionnel', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('association/profile.html.twig', [
            'user' => $user,
            'form' => $form,
            'formChangePassword' => $formChangePassword,
        ]);
    }

    /**
     * @Route("/commandesAssociation", name="commandesAssociation")
     */
    public function commandesAssociation(ReservationRepository $reservationRepository, Security $security): Response
    {
        $user = $security->getUser();
        return $this->render('association/commandes.html.twig', [
            'reservations' => $reservationRepository->findBy(["isBenevolat" => 1], ['createdAt' => 'desc']),
            'user' => $user,
        ]);
    }


    /**
     * @Route("/reserverAssociation/{id}", name="reserverAssociation")
     */
    public function reserverAssociation(Request $request,User $user, EntityManagerInterface $entityManager, Security $security): Response
    {   
        
        $con= $security->getUser();
        $produit = $user->getAds()[1];
        $reservation = new Reservation();
        $form = $this->createForm(ReservationAssociationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $reservation->setClient($con);
            $reservation->setCreatedAt(new \DateTime('now'));
            $reservation->setAds($produit);
            $reservation->setIsBenevolat(True);
            $reservation->setDeteheure(new DateTime($request->get("deteheure")));
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('commandesAssociation');
        }
       
        return $this->render('association/reserverAssociation.html.twig', [
            'user' => $user,
            'formt' => $form->createView(),
        ]);
    }
    /**
     * @Route("/reserverDirectAssociation/{id}", name="reserverDirectAssociation")
     */
    public function reserverDirectAssociation(Request $request,User $user, EntityManagerInterface $entityManager, Security $security): Response
    {   
        
        $con= $security->getUser();
        $produit = $user->getAds()[1];
        $reservation = new Reservation();
        $form = $this->createForm(reserverDirectAssociation::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $reservation->setClient($con);
            $reservation->setCreatedAt(new \DateTime('now'));
            $reservation->setAds($produit);
            $reservation->setIsBenevolat(True);
            $reservation->setDeteheure(new DateTime('2020-01-01 10:00:00'));
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('commandesAssociation');
        }
       
        return $this->render('association/reserverDirectAssociation.html.twig', [
            'user' => $user,
            'formt' => $form->createView(),
        ]);
    }

  
}
