<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_user_index")
     */
    public function index(UserRepository $repo)
    {   
        $users = $repo->findAll();

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }
}
