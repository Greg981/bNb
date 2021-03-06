<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     * Create new Ad 
     * 
     * @Route("/ads/new", name="ads_create")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $ad = new Ad();

        $form = $this->createForm(AnnonceType::class, $ad);

        $form->handleRequest($request);
            
            
        if ($form->isSubmitted() && $form->isValid()) 
        {
            foreach ($ad->getPics() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
        }
        $ad->setAuthor($this->getUser());

        $manager->persist($ad);
        $manager->flush();
            
        $this->addFlash(
            'success'," The ad <strong>{$ad->getTitle()}</strong> have been created !!"
        );

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/new.html.twig',[
            'form' => $form->createView()

        ]);
    }

     /**
     * Show edit formular
     * 
     * @route("/ads/{slug}/edit" , name="ads_edit")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="This ad is not yours ; You cannot modify it!!!")
     *
     * @return Response
     */

    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AnnonceType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            foreach ($ad->getPics() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
        }

        $manager->persist($ad);
        $manager->flush();
            
        $this->addFlash(
            'success'," The ad <strong>{$ad->getTitle()}</strong> have been modifyed !!"
        );

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
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


    /**
     * Delete Ad function
     * 
     *@Route("/ads/{slug}/delete", name="ads_delete")
     *@Security("is_granted('ROLE_USER') and user === ad.getAuthor()", 
     message="You don't have permission to access this function!!")
     * 
     * @param Ad $ad
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Ad $ad, EntityManagerInterface $manager)
    {
        $manager->remove($ad);
        $manager->flush();

        $this->addFlash(
            'success',
            "The Ad <strong>{$ad->getTitle()}</strong> have correctly been deleted !"
        );

        return $this->redirectToRoute("ads_index");
    }
}
