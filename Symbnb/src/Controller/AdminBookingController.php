<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings", name="admin_booking_index")
     */
    public function index(BookingRepository $repo)
    {
        $booking = $repo->findAll();

        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $booking,
        ]);
    }

    /**
     * Admin Edit Booking
     * 
     * @Route("/admin/bookings/{id}/edit", name="admin_booking_edit")
     * 
     * @param Booking $booking
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function Edit(Booking $booking, Request $request, EntityManagerInterface $manager)
    {  
        $form = $this->createForm(AdminBookingType::class, $booking );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $booking->setAmount(0);

            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'success',
                " Booking n# {$booking->getId()} have been modified ! "
            );

            return $this->redirectToRoute("admin_booking_index");
        }

        return $this->render('admin/booking/edit.html.twig', [
            'form'      => $form->createView(),
            'booking'   => $booking
        ]);
    }    

        /**
     * Admin Delete Booking
     * 
     * @Route("/admin/bookings/{id}/delete", name="admin_booking_delete") 
     * 
     * @param Booking  $booking
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Booking $booking, EntityManagerInterface $manager)
    {
            $manager->remove($booking);
            $manager->flush();
    
            $this->addFlash(
                'success',
                "Booking  of <bold>{$booking->getBooker()->getFullname()}</bold>  from Ad XXXX have succefully been removed !"
            );

            return $this->redirectToRoute("admin_booking_index");
    }
}
