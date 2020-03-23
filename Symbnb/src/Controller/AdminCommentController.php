<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="admin_comment_index")
     */
    public function index(CommentRepository $repo)
    {
        // $repo = $this->getDoctrine()->getRepository(Comment::class);

        $comments = $repo->findAll();


        return $this->render('admin/comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }

    /**
     * Show Admin Comment edit form
     * 
     * @Route("/admin/comments/{id}/edit", name="admin_comment_edit")
     *
     * @param Comment $comment
     * @return Response
     */
    public function edit(Comment $comment, Request $request, EntityManagerInterface $manager)
    {
       $form = $this->createForm(AdminCommentType::class, $comment);

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()) {
           $manager->persist($comment);
           $manager->flush();

           $this->addFlash(
               'success',
               "Comment : <bold>{$comment->getId()}</bold> have been changed !"

           );
       }

       return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' =>$form->createView()
       ]);

    }

    /**
     * Admin Delete comment
     * 
     * @Route("/admin/comments/{id}/delete", name="admin_comment_delete") 
     * 
     * @param Comment $comment
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Comment $comment, EntityManagerInterface $manager)
    {
            $manager->remove($comment);
            $manager->flush();
    
            $this->addFlash(
                'success',
                "Comment <bold>{$comment->getAuthor()->getFullname()}</bold> have succefully been removed !"
            );
        

       return $this->redirectToRoute('admin_comment_index');
    }
}
