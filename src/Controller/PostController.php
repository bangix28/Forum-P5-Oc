<?php


namespace App\Controller;

use App\Services\post\PostServices;

class PostController extends MainController
{
    private $postServices;

    private $em;

    public function __construct()
    {
        parent::__construct();
        $this->postServices = new PostServices();
        $this->em = $this->orm->entityManager();
    }

    public function createMethod()
    {
        $user = $this->request->getSession()->get('user');
        if ($user && $user->getVerified() === 1)
        {
        $message = null;
        $submit = $this->request->getPost()->get('submit');
        if (isset($submit))
        {
            $message = $this->postServices->createVerification();
        }
        return $this->render('post/createPost.html.twig', [
            'message' => $message
            ]);
        }
        return $this->render('security/403.html.twig');
    }
    public function editMethod()
    {
        if (!empty($this->request->getSession()->get('user'))) {
            $post = $this->em->find(':Post', $this->request->getGet()->get('id'));
            if ($this->request->getSession()->get('user')->getId() === $post->getUser()->getId()) {
                $this->postServices->editVerification($post);
                return $this->render('post/editPost.html.twig', [
                    'post' => $post
                ]);
            }
            return $this->render('security/403.html.twig');
        }
        return $this->render('security/403.html.twig');
    }

    public function readMethod()
    {
        $post = $this->orm->entityManager()->find(':Post', $this->request->getGet()->get('id'));
        if (!$post)
        {
            return $this->render('security/404.html.twig');
        }else {
            return $this->render('post/viewPost.html.twig', [
                    'post' => $post
                ]);
        }
    }
    public function deleteMethod()
    {
        if (!empty($this->request->getSession()->get('user'))) {
            $post = $this->em->find(':Post', $this->request->getGet()->get('id'));
            if ($this->request->getSession()->get('token') === $this->request->getGet()->get('token') || $this->request->getSession()->get('user')->getId() === $post->getUser()->getId()) {
                $this->em->remove($post);
                $this->em->flush();
            $this->request->getSession()->set('msuccess', 'Votre article a bien été supprimer');
            header('Location:'. $_SERVER['HTTP_REFERER']);
        }else{
                return $this->render('security/403.html.twig');
        }
        }
        return $this->render('security/403.html.twig');
    }

    public function listPostMethod()
    {
       return $this->render('post/listPost.html.twig',[
           'posts' =>$this->em->getRepository(':Post')->findAll(array('createdAt' => 'desc'),15)
       ]);
    }

    public function handleSearchMethod()
    {
        return $this->render('post/listPost.html.twig', [
            'posts' => $this->postServices->search()
        ]);
    }
}
