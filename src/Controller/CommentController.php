<?php


namespace App\Controller;


use App\Services\comment\CommentServices;

class CommentController extends MainController
{
    private $commentServices;
    public function __construct()
    {
        parent::__construct();
        $this->commentServices = new CommentServices();

    }

    public function createMethod()
    {
        $this->commentServices->Verification();
    }
    public function editMethod()
    {
        $em = $this->orm->entityManager();
        $comment = $em->find(':Comment', $this->request->getGet()->get('id'));
        if (!empty($this->request->getPost()->get('content')))
        {
            $comment->setContent();
            header('Location:'. $_SERVER['HTTP_REFERER']);
        }else{
            return $message = 'Ne laissez pas de champs vide';
        }
    }

    public function deleteMethod()
    {
        $em = $this->orm->entityManager();
        $comment = $em->find(':Comment', $this->request->getGet()->get('id'));
        $em->remove($comment);
        $em->flush();
        header('Location:'. $_SERVER['HTTP_REFERER']);
    }

    public function ViewMethod()
    {
        $em = $this->orm->entityManager();
        $post = $em->getRepository(':Post')->findBy(['user' => $this->request->getSession()->get('user')]);
        $comment = $em->getRepository(':Comment')->findBy(['post' => $post ]);
        return $this->render('user/validateComment.html.twig',[
            'comment' => $comment,
            'post' => $post
        ]);

    }

    public function validateMethod()
    {
        $em = $this->orm->entityManager();
        $comment = $em->find(':Comment',$this->request->getGet()->get('id'));
        $comment->setIsValid(1);
        $em->persist($comment);
        $em->flush();
        header('Location:'. $_SERVER['HTTP_REFERER']);
    }
}
