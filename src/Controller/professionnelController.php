<?php

namespace App\Controller;
use DateTime;
use App\Entity\User;
use App\Entity\Ads;
use App\Classe\Mail;
use App\Form\AdsType;
use App\Form\UserType;
use App\Form\DoneProType;
use App\Entity\Calendar;
use App\Entity\Category;
use App\Form\CalendarType;
use App\Form\ReductionProType;
use App\Form\ReservationAssociationType;
use App\Form\CategoryType;
use App\Form\BenevolatType;
use App\Form\ChangePasswordType;
use App\Form\professionnelinfoType;
use App\Entity\Reservation;
use App\Entity\Competence;
use App\Entity\Experience;
use App\Entity\Formation;
use App\Repository\CompetenceRepository;
use App\Repository\ExperienceRepository;
use App\Repository\FormationRepository;
use App\Form\CompetenceType;
use App\Form\ExperienceType;
use App\Form\FormationType;
use App\Form\MotifRefusType;
use App\Form\modifierAdsType;
use App\Repository\CalendarRepository;
use App\Repository\UserRepository;
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
 * @Route("/professionnel", )
 */
class professionnelController extends AbstractController
{
    /**
     * @Route("/indexProfessionnel", name="indexProfessionnel")
     */
    public function indexProfessionnel(Security $security,ReservationRepository $reservation,EntityManagerInterface $entityManager): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        $con = $security->getuser();

        $events = $entityManager->createQuery("select reserv from App\Entity\Reservation reserv,App\Entity\Ads ads where 
        ads.user = $con and reserv.ads = ads.id")->getResult();

        $rdvs = [];
        foreach ($events as $event) {
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getDeteheure()->format('Y-m-d H:i:s'),
                'end' => $event->getClient()->getLastname(),
                'title' => $event->getClient()->getLastname(),
                'description' => $event->getAds()->getTitre(),
            ];
        }
        $data = json_encode($rdvs);

        return $this->render('professionnel/index.html.twig', compact('data'));
    }

   
    /**
     * @Route("/profil", name="profil", methods={"GET", "POST"})
     */
    public function profil(UserPasswordEncoderInterface $encoder,Request $request, EntityManagerInterface $entityManager, Security $security, UserRepository $userRepository): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        $user = $security->getUser();
        
        $form = $this->createForm(professionnelinfoType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            
            $image = $form->get('illustration')->getData();
            if($image!= null){
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

            // On copie le fichier dans le dossier uploads
            $image->move(
                $this->getParameter('Ads_files_directory'),
                $fichier
            );
            
            $user->setIllustration($fichier);
            }
            $asso = $user->getAssociation();
            // dd($asso);
            $partenaire = $entityManager->createQuery("SELECT User from App\Entity\User User where User.id = $asso ")
            ->getResult();
            
            // dd("SELECT User from App\Entity\User User where User.id = '$asso' ");
            $user-> setAssociation($partenaire[0]->getDomination());
            //  dd($user);
            // On crée l'image dans la base de données
            
            $entityManager->flush();
            

            return $this->redirectToRoute('profil', [], Response::HTTP_SEE_OTHER);
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

        $formDone = $this->createForm(DoneProType::class, $user);
        $formDone->handleRequest($request);
        if ($formDone->isSubmitted() && !$formDone->isValid()) {
            $err = "Vous avez pas bien remplie les champs";
        }
        if ($formDone->isSubmitted() && $formDone->isValid()) {
            
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('indexProfessionnel', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('professionnel/profile.html.twig', [
            'user' => $user,
            'form' => $form,
            'formChangePassword' => $formChangePassword,
            'formDone'=>$formDone,
            'association' => $userRepository->findAll(),
        ]);
    }
    /**
     * @Route("/ajoutproduitCommercant", name="ajoutproduitCommercant")
     */
    public function ajoutproduit(Request $request, EntityManagerInterface $entityManager,Security $security): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        $ads = new Ads();
        $form = $this->createForm(AdsType::class, $ads);
        $form->handleRequest($request);
        //dd($form);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($ads);
            $entityManager->flush();

            return $this->redirectToRoute('gestionProduit');
        }

        return $this->render('professionnel/ajoutProduit.html.twig', [
            'form' => $form,
        ]);
    }
    /**
     * @Route("/ajoutReduction", name="ajoutReduction")
     */
    public function ajoutReduction(Request $request, EntityManagerInterface $entityManager,Security $security): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        $user = $security->getUser();
        $form = $this->createForm(ReductionProType::class, $user);
        $form->handleRequest($request);
        //dd($form);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('gestionProduit');
        }

        return $this->render('professionnel/ajoutReduction.html.twig', [
            'form' => $form->createView(),
        ]);
    }
     /**
     * @Route("/autreReservation", name="autreReservation")
     */
    public function autreReservation(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {   
        
        $con= $security->getUser();
        $produit = $con->getAds()[1];
        $reservation = new Reservation();
        $form = $this->createForm(ReservationAssociationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $reservation->setUser($con);
            $reservation->setClient($con);
            $reservation->setCreatedAt(new \DateTime('now'));
            $reservation->setAds($produit);
            $reservation->setIsPaid(1);
            $reservation->setEndDate(new \DateTime(date('Y-m-d H:i:s', strtotime('+30 minutes', strtotime($request->get("deteheure"))))));
            $reservation->setAvancement("Valider");
            $reservation->setIsBenevolat(false);
            $reservation->setDeteheure(new DateTime($request->get("deteheure")));
            $entityManager->persist($reservation);
            $entityManager->flush();
            

            return $this->redirectToRoute('commandes');
        }
       
        return $this->render('professionnel/autreReservation.html.twig', [
            'user' => $con,
            'formt' => $form->createView(),
        ]);
    }
    /**
     * @Route("/detailReservation/{id}", name="detailReservation", methods={"GET"})
     */
    public function show(Reservation $reservation): Response
    {
        return $this->render('professionnel/detailReservation.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * @Route("/ajoutproduitCommercant", name="ajoutproduitCommercant", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        $ad = new Ads();
        $form = $this->createForm(AdsType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ad->setUser($security->getUser());
            $entityManager->persist($ad);
            $entityManager->flush();

            return $this->redirectToRoute('gestionProduit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('professionnel/ajoutProduit.html.twig', [
            'ad' => $ad,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/madifierProduit/{id}", name="madifierProduit", methods={"GET", "POST"})
     */
    public function madifierProduit(Security $security,Request $request, Ads $ad, EntityManagerInterface $entityManager): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        //dd($ad);
        $form = $this->createForm(modifierAdsType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($ad->getTitre() == "Réserver une table"){
                $ad->setTitre("Réserver une table");
                // dd("bla bla");
            }
            $entityManager->flush();

            return $this->redirectToRoute('gestionProduit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('professionnel/madifierProduit.html.twig', [
            'ad' => $ad,
            'form' => $form,
        ]);
    }
    /**
     * @Route("/{id}/masquerProduit", name="masquerProduit", methods={"GET","POST"})
     */
    public function masquerProduit(Security $security,Request $request, Ads $ad): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        $ad->setIsDisplay(0);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($ad);
        $entityManager->flush();
        return $this->redirectToRoute('gestionProduit');
    }
    /**
     * @Route("/{id}/publierProduit", name="publierProduit", methods={"GET","POST"})
     */
    public function publierProduit(Security $security,Request $request, Ads $ad): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        $ad->setIsDisplay(1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($ad);
        $entityManager->flush();
        return $this->redirectToRoute('gestionProduit');
    }
    /**
     * @Route("/{id}/supprimerProduit", name="supprimerProduit", methods={"GET","POST"})
     */
    public function supprimerProduit(Security $security,Request $request, Ads $ad): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        $ad->setIsDeleted(1);
        $ad->setIsDisplay(0);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($ad);
        $entityManager->flush();
        return $this->redirectToRoute('gestionProduit');
    }
    /**
     * @Route("/{id}/valideCommande", name="valideCommande", methods={"GET","POST"})
     */
    public function valideCommande(Security $security,Request $request, Reservation $reservation): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        $reservation->setAvancement("Valider");
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reservation);
        $entityManager->flush();
        return $this->redirectToRoute('commandes');
    }
    /**
     * @Route("/{id}/commandeServie", name="commandeServie", methods={"GET","POST"})
     */
    public function commandeServie(Security $security,Request $request, Reservation $reservation): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        $reservation->setAvancement("Servie");
        $reservation->setIsPret(1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reservation);
        $entityManager->flush();
        return $this->redirectToRoute('commandes');
    }

    /**
     * @Route("/gestionCategory", name="gestionCategory", methods={"GET", "POST"})
     */
    public function gestionCategory(Security $security, Request $request, EntityManagerInterface $entityManager,CategoryRepository $categoryRepository): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setUser($security->getUser());
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('gestionCategory', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('professionnel/gestionCategory.html.twig', [
            'category' => $category,
            'form' => $form,
            'categories' => $categoryRepository->findAll(),
        ]);
    }
     /**
     * @Route("/modifierCategory/{id}", name="modifierCategory", methods={"GET", "POST"})
     */
    public function modifierCategory( Category $category,Security $security, Request $request, EntityManagerInterface $entityManager,CategoryRepository $categoryRepository): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setUser($security->getUser());
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('gestionCategory', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('professionnel/gestionCategory.html.twig', [
            'category' => $category,
            'form' => $form,
            'categories' => $categoryRepository->findAll(),
        ]);
    }
    /**
     * @Route("/competences", name="competences", methods={"GET", "POST"})
     */
    public function competences(Security $security,Request $request,CompetenceRepository $competenceRepository, EntityManagerInterface $entityManager): Response
    {
        $competence = new Competence();
        $form = $this->createForm(CompetenceType::class, $competence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $competence->setUser($security->getUser());
            $entityManager->persist($competence);
            $entityManager->flush();
            return $this->redirectToRoute('competences');
        }
        return $this->render('professionnel/competences.html.twig', [
            'competences' => $competenceRepository->findAll(),
            'competence' => $competence,
            'form' => $form->createView(),
        ]);
    }
     /**
     * @Route("/modifierCompetance/{id}", name="modifierCompetance" , methods={"GET","POST"})
     */
    public function modifierCompetance(Security $security,Competence $competence,Request $request,CompetenceRepository $competenceRepository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CompetenceType::class, $competence);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($request); 
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($competence);
            $entityManager->flush();

            return $this->redirectToRoute('competences');
        }

        return $this->render('professionnel/competences.html.twig', [
            'competences' => $competenceRepository->findAll(),
            'competence' => $competence,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/supprimerCompetence/{id}", name="supprimerCompetence", methods={"GET","DELETE"})
     */
    public function supprimerCompetence(Request $request, Competence $competence): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($competence);
            $entityManager->flush();


        return $this->redirectToRoute('competences');
    }
    /**
     * @Route("/experiences", name="experiences", methods={"GET", "POST"})
     */
    public function experiences(Security $security,Request $request,ExperienceRepository $experienceRepository, EntityManagerInterface $entityManager): Response
    {
        $experience = new Experience();
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $experience->setUser($security->getUser());
            $entityManager->persist($experience);
            $entityManager->flush();
            return $this->redirectToRoute('experiences');
        }
        return $this->render('professionnel/experiences.html.twig', [
            'experiences' => $experienceRepository->findAll(),
            'experience' => $experience,
            'form' => $form->createView(),
        ]);
    }
     /**
     * @Route("/modifierExperience/{id}", name="modifierExperience" , methods={"GET","POST"})
     */
    public function modifierExperience(Security $security,Experience $experience,Request $request,ExperienceRepository $experienceRepository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($request); 
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($experience);
            $entityManager->flush();

            return $this->redirectToRoute('experiences');
        }

        return $this->render('professionnel/experiences.html.twig', [
            'experiences' => $experienceRepository->findAll(),
            'experience' => $experience,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/supprimerExperience/{id}", name="supprimerExperience", methods={"GET","DELETE"})
     */
    public function supprimerExperience(Request $request, Experience $experience): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($experience);
            $entityManager->flush();


        return $this->redirectToRoute('experiences');
    }
    /**
     * @Route("/formations", name="formations", methods={"GET", "POST"})
     */
    public function formations(Security $security,Request $request,FormationRepository $formationRepository, EntityManagerInterface $entityManager): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formation->setUser($security->getUser());
            $entityManager->persist($formation);
            $entityManager->flush();
            return $this->redirectToRoute('formations');
        }
        return $this->render('professionnel/formations.html.twig', [
            'formations' => $formationRepository->findAll(),
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }
     /**
     * @Route("/modifierFormation/{id}", name="modifierFormation" , methods={"GET","POST"})
     */
    public function modifierFormation(Security $security,Formation $formation,Request $request,FormationRepository $formationRepository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($request); 
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('formations');
        }

        return $this->render('professionnel/formations.html.twig', [
            'formations' => $formationRepository->findAll(),
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/supprimerFormation/{id}", name="supprimerFormation", methods={"GET","DELETE"})
     */
    public function supprimerFormation(Request $request, Formation $formation): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($formation);
            $entityManager->flush();


        return $this->redirectToRoute('formations');
    }

    /**
     * @Route("/detailProduit/{id}", name="detailProduit")
     */
    public function detailProduit(Security $security,Ads $ads ): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        return $this->render('professionnel/detailProduit.html.twig', [
            'ad' => $ads,
        ]);
    }
     /**
     * @Route("/commandes", name="commandes")
     */
    public function commandes(ReservationRepository $reservationRepository, Security $security): Response
    {
        $user = $security->getUser();
        return $this->render('professionnel/commandes.html.twig', [
            'reservations' => $reservationRepository->findBy(["isPaid" => 1], ['createdAt' => 'desc']),
            'user' => $user,
        ]);
    }
     /**
     * @Route("/BenevolatPro", name="BenevolatPro")
     */
    public function BenevolatPro(ReservationRepository $reservationRepository, Security $security): Response
    {
        $user = $security->getUser();
        return $this->render('professionnel/BenevolatPro.html.twig', [
            'reservations' => $reservationRepository->findBy(["isBenevolat" => 1], ['createdAt' => 'desc']),
            'user' => $user,
        ]);
    }
     /**
     * @Route("/detailCommande", name="detailCommande")
     */
    public function detailCommande(): Response
    {
        return $this->render('professionnel/detailCommande.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
     /**
     * @Route("/{id}/motifRefusCommande", name="motifRefusCommande")
     */
    public function motifRefusCommande(Request $request,EntityManagerInterface $entityManager, Reservation $reservation, Security $security): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        $form = $this->createForm(MotifRefusType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setAvancement("Refuser");
            $entityManager->persist($reservation);
            $entityManager->flush();
            $pro = $security->getUser()->getDomination();
            $messagerefus = $reservation->getMotifRefus();
            // dd($reservation);
            $email = $reservation->getClient()->getEmail();
            $lastname= $reservation->getClient()->getLastname();
            $mail = new Mail();
            $mail->send($email, $lastname, "Réservation non-confirmée ", "Merci d'avoir comander sur notre plateforme PEACE.Le professionnel $pro n'a pas confirmé votre réservation.<h5>$messagerefus</h5 </br> .Vous allez être remboursé dans les meilleurs délais.  ");

            return $this->redirectToRoute('commandes', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('professionnel/motifRefusCommande.html.twig', [
            'controller_name' => 'HomeController',
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/gestionMagasin", name="gestionMagasin" , methods={"GET","POST"})
     */
    public function gestionMagasin(ReservationRepository $reservationRepository, Security $security, Request $request, CalendarRepository $calendarRepository): Response
    {
        $calendar = new Calendar();
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);
        $calendar->setUser($security->getUser());
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($request); 
            $entityManager = $this->getDoctrine()->getManager();
            $calendar->setTitle("Magasin");
            $entityManager->persist($calendar);
            $entityManager->flush();

            return $this->redirectToRoute('gestionMagasin');
        }

        return $this->render('professionnel/gestionMagasin.html.twig', [
            'form' => $form->createView(),
            'user' => $security->getUser(),
            'calendars' => $calendarRepository->findAll(),
        ]);
    }
    /**
     * @Route("/modifierCalendar/{id}", name="modifierCalendar" , methods={"GET","POST"})
     */
    public function modifierCalendar(ReservationRepository $reservationRepository, Calendar $calendar, Security $security, Request $request, CalendarRepository $calendarRepository): Response
    {
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);
        $calendar->setUser($security->getUser());
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($request); 
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($calendar);
            $entityManager->flush();

            return $this->redirectToRoute('gestionMagasin');
        }

        return $this->render('professionnel/gestionMagasin.html.twig', [
            'form' => $form->createView(),
            'user' => $security->getUser(),
            'calendars' => $calendarRepository->findAll(),
        ]);
    }
    /**
     * @Route("/{id}/supprimerCalendar", name="calendar_delete", methods={"GET","DELETE"})
     */
    public function delete(Request $request, Calendar $calendar): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($calendar);
            $entityManager->flush();


        return $this->redirectToRoute('gestionMagasin');
    }

    /**
     * @Route("/gestionBenevolat", name="gestionBenevolat" , methods={"GET","POST"})
     */
    public function gestionBenevolat(ReservationRepository $reservationRepository, Security $security, Request $request, CalendarRepository $calendarRepository): Response
    {
        $calendar = new Calendar();
        $form = $this->createForm(BenevolatType::class, $calendar);
        $form->handleRequest($request);
        $calendar->setUser($security->getUser());
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $calendar->setTitle("Benevolat");
            $entityManager->persist($calendar);
            $entityManager->flush();

            return $this->redirectToRoute('gestionBenevolat');
        }

        return $this->render('professionnel/gestionBenevolat.html.twig', [
            'form' => $form->createView(),
            'user' => $security->getUser(),
            'calendars' => $calendarRepository->findBy(['title' => 'Benevolat']),
        ]);
    }
    /**
     * @Route("/modifierCalendarbenevolat/{id}", name="modifierCalendarbenevolat" , methods={"GET","POST"})
     */
    public function modifierCalendarbenevolat(ReservationRepository $reservationRepository, Calendar $calendar, Security $security, Request $request, CalendarRepository $calendarRepository): Response
    {
        $form = $this->createForm(BenevolatType::class, $calendar);
        $form->handleRequest($request);
        $calendar->setUser($security->getUser());
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($request); 
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($calendar);
            $entityManager->flush();

            return $this->redirectToRoute('gestionBenevolat');
        }

        return $this->render('professionnel/gestionBenevolat.html.twig', [
            'form' => $form->createView(),
            'user' => $security->getUser(),
            'calendars' => $calendarRepository->findAll(),
        ]);
    }
    /**
     * @Route("/{id}/supprimerCalendarbenevolat", name="supprimerCalendarbenevolat", methods={"GET","DELETE"})
     */
    public function supprimerCalendarbenevolat(Request $request, Calendar $calendar): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($calendar);
            $entityManager->flush();


        return $this->redirectToRoute('gestionBenevolat');
    }
    
   

     /**
     * @Route("/formReductionOrganisme", name="formReductionOrganisme")
     */
    public function formReductionOrganisme(): Response
    {
        return $this->render('professionnel/formReductionOrganisme.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
     /**
     * @Route("/gestionProduit", name="gestionProduit")
     */
    public function gestionProduit(EntityManagerInterface $entityManager, Security $security): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        $user = $security->getUser();
        $produits = $entityManager->createQuery("SELECT Ads from App\Entity\Ads Ads, App\Entity\User User  where Ads.user = '$user'  ")
            ->getResult();
        // dd($produits);
        return $this->render('professionnel/gestionProduit.html.twig', [
            'produits' => $produits,
        ]);
    }
    /**
     * @Route("/{id}/visiblePourAssociation", name="visiblePourAssociation", methods={"GET","POST"})
     */
    public function visiblePourAssociation(Security $security,Request $request, User $user): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        $user->setIsvisiblepourassociation(1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('gestionMagasin');
    }
    /**
     * @Route("/{id}/masquerPourAssociation", name="masquerPourAssociation", methods={"GET","POST"})
     */
    public function masquerPourAssociation(Security $security,Request $request, User $user): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        $user->setIsvisiblepourassociation(0);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('gestionMagasin');
    }
    /**
     * @Route("/{id}/visiblePourClient", name="visiblePourClient", methods={"GET","POST"})
     */
    public function visiblePourClient(Security $security,Request $request, User $user): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        $user->setIsvisiblepourclient(1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('gestionMagasin');
    }
    /**
     * @Route("/{id}/masquerPourClient", name="masquerPourClient", methods={"GET","POST"})
     */
    public function masquerPourClient(Security $security,Request $request,User $user): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('indexPeace');
        }
        $user->setIsvisiblepourclient(0);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('gestionMagasin');
    }
    
   
}
