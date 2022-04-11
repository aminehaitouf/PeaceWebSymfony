<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\Security\Core\Security;

class PublicExtension extends AbstractExtension
{
     /**
     * @var EntityManagerInterface
     */
    protected $em;
    private $userRepository;
    private $reservationRepository;
    private $security;

    /**
     * GetProvinceExtension constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em,UserRepository $userRepository,ReservationRepository $reservationRepository,Security $security) {
        $this->em = $em;
        $this->userRepository=$userRepository;
        $this->reservationRepository=$reservationRepository;
        $this->security=$security;

    }
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getReservation', [$this, 'getReservation']),
            new TwigFunction('getReservationAdmin', [$this, 'getReservationAdmin']),
            new TwigFunction('getbnrProfessionnelle', [$this, 'getbnrProfessionnelle']),
            new TwigFunction('getbnrClient', [$this, 'getbnrClient']),
            new TwigFunction('getReservationAdminBenevolat', [$this, 'getReservationAdminBenevolat']),
            new TwigFunction('getBenevolat', [$this, 'getBenevolat']),
            new TwigFunction('getNberreservationstotal', [$this, 'getNberreservationstotal']),
            new TwigFunction('getNbrdeproduit', [$this, 'getNbrdeproduit']),
            new TwigFunction('getNbrCategorie', [$this, 'getNbrCategorie']),
        ];
    }

    public function getReservation()
    {
        $user=$this->security->getUser();
         return count($this->em->createQuery("select reserv from App\Entity\Reservation reserv ,App\Entity\Ads Ads,App\Entity\User User where 
          reserv.isPaid=1 and reserv.avancement = 'En cours de traitement' and reserv.ads = Ads.id And Ads.user = $user ")->getResult());  
            
    }
    public function getReservationAdmin()
    {
        return count($this->em->createQuery("select reserv from App\Entity\Reservation reserv ,App\Entity\Ads Ads,App\Entity\User User where 
          reserv.isPaid=1 and reserv.ads = Ads.id ")->getResult());  
            
    }
    public function getbnrProfessionnelle()
    {
        return count($this->em->createQuery("SELECT User from App\Entity\User User where User.roles like '%ROLE_COM%'")->getResult());  
            
    }
    public function getbnrClient()
    {
        return count($this->em->createQuery("SELECT User from App\Entity\User User where User.roles like '%ROLE_USER%'")->getResult());  
            
    }
    public function getReservationAdminBenevolat()
    {
        return count($this->em->createQuery("select reserv from App\Entity\Reservation reserv ,App\Entity\Ads Ads,App\Entity\User User where 
          reserv.isBenevolat='1'")->getResult());  
            
    }
    public function getBenevolat()
    {
        $user=$this->security->getUser();
         return count($this->em->createQuery("select reserv from App\Entity\Reservation reserv ,App\Entity\Ads Ads,App\Entity\User User where 
          reserv.isBenevolat=1 and reserv.avancement = 'En cours de traitement' and reserv.ads = Ads.id And Ads.user = $user ")->getResult());  
            
    }
    public function getNberreservationstotal()
    {
        $user=$this->security->getUser();
         return count($this->em->createQuery("select reserv from App\Entity\Reservation reserv ,App\Entity\Ads Ads,App\Entity\User User where 
          reserv.isPaid=1 and reserv.ads = Ads.id And Ads.user = $user ")->getResult());  
            
    }
    public function getNbrdeproduit()
    {
        $user=$this->security->getUser();
         return count($this->em->createQuery("select Ads from App\Entity\Ads Ads,App\Entity\User User where
          Ads.user = $user ")->getResult());  
            
    }
    public function getNbrCategorie()
    {
        $user=$this->security->getUser();
         return count($this->em->createQuery("select Category from App\Entity\Category Category,App\Entity\User User where
         Category.user = $user ")->getResult());  
            
    }
}
