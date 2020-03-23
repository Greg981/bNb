<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads", name="admin_ads_index")
     */
    public function index(AdRepository $repo)
    {
        return $this->render('admin/ad/index.html.twig', [
            'ads' => $repo->findAll()
        ]);
    }

    /**
     * Show Admin AD edit form
     * 
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     *
     * @param Ad $ad
     * @return Response
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager)
    {
       $form = $this->createForm(AnnonceType::class, $ad);

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()) {
           $manager->persist($ad);
           $manager->flush();

           $this->addFlash(
               'success',
               "The Ad : <bold>{$ad->getTitle()}</bold> have been saved !"

           );
       }

       return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' =>$form->createView()
       ]);
    }


    /**
     * Admin Delete Ad
     * 
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete") 
     * 
     * @param Ad $ad
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Ad $ad, EntityManagerInterface $manager)
    {
        if(count($ad->getBookings()) > 0){
            $this->addFlash(
                'warning',
                " You cannot delete Ad : <bold>{$ad->getTitle()}</bold> because some booking have been made !"
            );
        } else {
            $manager->remove($ad);
            $manager->flush();
    
            $this->addFlash(
                'success',
                " Ad <bold>{$ad->getTitle()}</bold> have succefully been removed !"
            );
        }

       return $this->redirectToRoute('admin_ads_index');
    }
}
