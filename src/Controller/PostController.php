<?php


namespace App\Controller;

use App\Services\post\PostServices;

class PostController extends MainController
{
    private $postServices;

    public function __construct()
    {
        parent::__construct();
        $this->postServices = new PostServices();

    }

    public function createMethod()
    {
        $message = null;
        $submit = $this->request->getPost()->get('submit');
        if (isset($submit))
        {
            $message = $this->postServices->createVerification();
        }
        return $this->render('post/createPost.html.twig', ['message' => $message]);
    }
    public function editMethod()
    {
        $em= $this->orm->entityManager();
        $post = $em->find(':Post', $this->request->getGet()->get('id'));
        $this->postServices->editVerification($post, $em);
        return $this->render('post/editPost.html.twig', ['post' => $post]);
    }

    public function readMethod()
    {
        $post = $this->orm->entityManager()->find(':Post', $this->request->getGet()->get('id'));
        if (!$post)
        {
            return $this->render('security/404.html.twig');
        }else {
            return $this->render('post/viewPost.html.twig', [
                    'post' => $post]
            );
        }
    }
    public function deleteMethod()
    {
        $id = $this->request->getGet()->get('id');
        $em = $this->orm->entityManager();
        $post = $em->find(':Post', $id);
        $em->remove($post);
        $em->flush();
        header('Location:index.php');
    }
}
