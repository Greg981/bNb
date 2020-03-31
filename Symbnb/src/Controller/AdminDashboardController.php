<?php

namespace App\Controller;

use App\Service\Stats;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(EntityManagerInterface $manager, Stats $statsService)
    {
        $stats      =   $statsService->getStats();

        // request to show Best ads rating
        $bestAds    =   $statsService->getAdsStats('DESC');

        // request to show Worst ads rating
        $worstAds    =   $statsService->getAdsStats('ASC');

        return $this->render('admin/dashboard/index.html.twig', [
            'stats'     =>  $stats,
            'bestAds'   =>  $bestAds,
            'worstAds'  =>  $worstAds,

            
        ]);
    }
}
