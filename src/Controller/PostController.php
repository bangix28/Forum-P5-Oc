<?php


namespace App\Controller;

use App\Entity\Post;
use App\Services\post\PostServices;
use Doctrine\ORM\Mapping\ClassMetadata;
use Repository\PostRepository;

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
    public function editMethod()
    {
        $em= $this->orm->entityManager();
        $post = $em->find(':Post', $this->request->getGet()->get('id'));
        $this->postServices->editVerification($post, $em);
        return $this->render('post/editPost.html.twig', [
            'post' => $post
        ]);
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
        $id = $this->request->getGet()->get('id');
        $em = $this->orm->entityManager();
        $post = $em->find(':Post', $id);
        $em->remove($post);
        $em->flush();
        header('Location:index.php');
    }

    public function listPostMethod()
    {

       return $this->render('post/listPost.html.twig',[
           'posts' =>$this->em->getRepository(':Post')->findAll(array('createdAt' => 'desc'),15)
       ]);
    }

    public function handleSearchMethod()
    {
        return $this->render('post/search.html.twig', [
            'post' => $this->postServices->search()
        ]);
    }
}
