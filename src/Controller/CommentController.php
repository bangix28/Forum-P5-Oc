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

    }

    public function readMethod()
    {

    }
    public function deleteMethod()
    {
        $em = $this->orm->entityManager();
        $comment = $em->find(':Comment', $this->request->getGet()->get('id'));
        $id = $comment->getPost();
        $em->remove($comment);
        $em->flush();
        header('Location:index.php?access=post!read&id='. $id);
    }
}
