<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo) //on va type hinter dans la function index pour injecter les dependances 
    {
        //ce qui permettra de virer la ligne dessous
       // $repo = $this->getDoctrine()->getRepository(Ad::class);

        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }

    /**
     * Show only one ad
     * 
     * @Route("/ads/{slug}", name="ads_show")
     * 
     * @return Response
     */

     // now by using Paramconverter I can remove ($slug, AdRepository $repo)
    public function show (Ad $ad)
    {
        // and also remove that
        // I retrive the ad corresponding to the slug!
        // $ad = $repo->findOneBySlug($slug);

        return $this->render('ad/show.html.twig',[
           'ad' => $ad 
        ]);
    }
}
