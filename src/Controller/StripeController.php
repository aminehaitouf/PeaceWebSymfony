<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    /**
     * @Route("/reservation/create-session/{id}", name="stripe_create_session")
     * @param EntityManagerInterface $entityManager
     * @param ReservationRepository $reservationRepository
     * @param $id
     * @return Response
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function index(EntityManagerInterface $entityManager, ReservationRepository $reservationRepository, $id): Response
    {
        $reservation = $reservationRepository->findOneBy(['id' => $id]);
        // $ad=$reservation->getProduit();
        // if ($ad->getUser()->getTypes()&& isset($this->getUser()->getFidelite()[''.$ad->getUser()]))
        // {
        //     if($this->getUser()->getFidelite()[''.$ad->getUser()]>$ad->getUser()->getSeuil()){
        //         if( $ad->getUser()->getTypes()=="Pourcentage")
        //             $reservation->setPrice($ad->getPrice()*(1-$ad->getUser()->getReduction()/100));
        //         else
        //             $reservation->setPrice($ad->getPrice()-$ad->getUser()->getReduction()>=0? $ad->getPrice()-$ad->getUser()->getReduction():0);
        //     }

        // }

        $ads_stripe = [];
        $YOUR_DOMAIN = 'http://localhost:8000';
        //  $YOUR_DOMAIN = 'https://peace.sc';
       $prix=((($reservation->getAds()->getPrix()*0.15+1)*100));

       if( $reservation->getAds()->getUser()->getDomaine() == "Restaurant" && $reservation->getAds()->getTitre() == "Reservation"){
        $ads_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $prix,
                'product_data' => [
                    'name' => $reservation->getAds()->getTitre(),
                    'images' => ["https://i.imgur.com/EHyR2nP.png"],
                ],
            ],
            'quantity' => 1,
        ];
       }
       else{

       
        
        $ads_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $reservation->getAds()->getPrix()*100,
                'product_data' => [
                    'name' => $reservation->getAds()->getTitre(),
                    'images' => ["https://i.imgur.com/EHyR2nP.png"],
                ],
            ],
            'quantity' => 1,
        ];}

        Stripe::setApiKey('sk_test_51IL3v0FXuCAWxwDZlfITIsyLqREyrb0s9q2hdDfqwvx2j5HLdKNsdpGFZQ41uViEXHZBGybuLQg2BDlUKYiT1PUi00rnegK7Oj');
        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [
                $ads_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/'. $reservation->getId(),
        ]);

        $reservation->setStripeSessionId($checkout_session->id);
        $entityManager->flush();
        

        return $this->redirect($checkout_session->url, 303);
    }










    /**
     * @Route("/service/create-session/{id}", name="stripe_create")
     * @param EntityManagerInterface $entityManager
     * @param ServiceRepository $serviceRepository
     * @param $id
     * @return Response
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function service(EntityManagerInterface $entityManager, ServiceRepository $serviceRepository, $id): Response
    {
        $service = $serviceRepository->findOneBy(['id' => $id]);

        $ads_stripe = [];
        $YOUR_DOMAIN = 'http://localhost:8000';
        $ads_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $service->getPrice(),
                'product_data' => [
                    'name' => $service->getTitre(),
                    'images' => ["https://i.imgur.com/EHyR2nP.png"],
                ],
            ],
            'quantity' => 1,
        ];

        Stripe::setApiKey('sk_test_51IIuxKCVQmv1TIbBH781Isq7QTo1KUwmjy2xhWcLbSstVgi7qNT5SC4NEuO1Jw1lGppY7em83GSlk7VqmmeVB23z00AiYIHUKq');
        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [
                $ads_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/service/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $service->setStripeSessionId($checkout_session->id);
        $entityManager->flush();

        return new JsonResponse(['id'=>$checkout_session->id]);
    }
}
