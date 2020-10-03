<?php


namespace App\Controller;


use App\Services\comment\CommentServices;

class CommentController extends MainController
{
    private $commentServices;

    private $em;

    public function __construct()
    {
        parent::__construct();
        $this->commentServices = new CommentServices();
        $this->em = $this->orm->entityManager();
    }

    public function createMethod()
    {
        $this->commentServices->Verification();
        $this->request->getSession()->set('msuccess', "commentaires créés avec succès !");
    }
    public function editMethod()
    {
        $comment = $this->em->find(':Comment', $this->request->getGet()->get('id'));
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
        $comment = $this->em->find(':Comment', $this->request->getGet()->get('id'));
        $this->em->remove($comment);
        $this->em->flush();
        $this->request->getSession()->set('msuccess', "commentaires supprimés !");
        header('Location:'. $_SERVER['HTTP_REFERER']);
    }

    public function ViewMethod()
    {
        $post = $this->em->getRepository(':Post')->findBy(['user' => $this->request->getSession()->get('user')]);
        $comment = $this->em->getRepository(':Comment')->findBy(['post' => $post]);
        return $this->render('user/validateComment.html.twig',[
            'comment' => $comment,
            'post' => $post
        ]);

    }

    public function validateMethod()
    {
        $comment = $this->em->find(':Comment', $this->request->getGet()->get('id'));
        $comment->setIsValid(1);
        $this->em->persist($comment);
        $this->em->flush();
        $this->request->getSession()->set('msuccess', "commentaires validés !");
        header('Location:'. $_SERVER['HTTP_REFERER']);
    }
}
