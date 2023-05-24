<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\Cloner\Data;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(PostRepository $pr): Response
    {
        $posts = $pr->getAllPosts();
        return $this->render('home/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/home/show/{id}", name="home_show_post",methods={"GET","POST"})
     */
    public function getPostById($id, PostRepository $pr)
    {
        $post = $pr->getPostById($id);
        // dd($post);

        return $this->render('home/show.html.twig', [
            'post' => $post
        ]);
    }


    /**
     * @Route("/home/comment/add", name="home_add_comment", methods={"GET", "POST"})
     */
    public function addComment(CommentRepository $cr , PostRepository $pr )
    { //dd($_POST);
        $comment=new Comment();
        $post = $pr->find($_POST['postId']);

        $comment->setPost(  $post );
        $comment->setNickname($_POST['pseudo']);
        $comment->setContents($_POST['comment']);
        $cr->add($comment, true);

        return $this->redirectToRoute("home_show_post", ["id"=>$post->getId()]);
    }
}
