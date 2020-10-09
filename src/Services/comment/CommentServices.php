<?php


namespace App\Services\comment;


use App\Controller\MainController;
use App\Entity\Comment;

class CommentServices extends MainController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Verification()
    {
        if (!empty($this->request->getSession()->get('user'))) {
            if (!empty($this->request->getPost()->get('content'))) {
                $this->create();
            }
        }
        return $this->render('security/403.html.twig');
    }

    public function create()
    {
        $user = $this->request->getSession()->get('user');
        $em = $this->orm->entityManager();
        $post = $em->find(':Post', $this->request->getPost()->get('post'));
        $em->merge($post);
        $em->merge($user);
        $comment = New Comment();
        $comment->setContent(strip_tags($this->request->getPost()->get('content')));
        $comment->setCreatedAt(new \DateTime('now'));
        $comment->setIsValid('0');
        $comment->setPost($post);
        $comment->setUser($user);
        $em->merge($comment);
        $em->flush();
        header('Location:index.php?access=post!read&id=' . $this->request->getPost()->get('post'));
    }
}
