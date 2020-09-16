<?php


namespace App\Services;


use App\Controller\MainController;
use App\Entity\User;

class Security extends MainController
{

    public function register()
    {
        if (!empty($this->request->getPost()->get('email')) && !empty($this->request->getPost()->get('password') && $this->request->getPost()->get('firstName') && $this->request->getPost()->get('lastName'))) {
            if (preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/', $this->request->getPost()->get('password'))) {
                $email = $this->verifiedEmail($this->request->getPost()->get('email'));
                if (empty($email)) {
                    $user = new User();
                    $user->setEmail($this->request->getPost()->get('email'));
                    $user->setPassword(password_hash($this->request->getPost()->get('password'), PASSWORD_DEFAULT));
                    $user->setFirstName($this->request->getPost()->get('firstName'));
                    $user->setLastName($this->request->getPost()->get('lastName'));
                    $user->setRoles(['ROLES_USER']);
                    $user->setLogo('1');
                    $randomKey = $this->generateKey();
                    $user->setRandomKey($randomKey);
                    $em = $this->orm->entityManager();
                    $em->persist($user);
                    $em->flush();
                    header('Location:index.php');

                } else
                    return $message = "l'email rentré est déja utilisé";
            }
            return $message = ['au moins un caractère minuscule', 'au moins un caractère majuscule', 'au moins un chiffre', 'au moins un signe spécial  @ # -_ $% ^ & + = § !?', 'et doit etre compris 8 et 20 caractères'];
        }
        return $message = 'Vous devez remplir tout les champs';
    }

    public function generateKey($length = 20)
    {
        $key = '';

        for ($i = 0; $i < $length; $i++) {
            $key .= mt_rand(0, 9);
        }
        return $key;
    }

    public function login()
    {
        if (!empty($this->request->getPost()->get('email')) && !empty($this->request->getPost()->get('password'))) {
            $user = $this->verifiedEmail($this->request->getPost()->get('email'));
            if (!empty($user)) {
                if (preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/', $this->request->getPost()->get('password'))){
                    if (password_verify($this->request->getPost()->get('password'), $user->getPassword())) {
                        $this->sessionLogin($user);
                        header('Location:index.php');
                    }else {
                        $this->request->getSession()->set('message', "Mot de passe incorrecte");
                        header('Location:index.php');
                    }
                }else {
                    $this->request->getSession()->set('message', ['au moins un caractère minuscule', 'au moins un caractère majuscule', 'au moins un chiffre', 'au moins un signe spécial  @ # -_ $% ^ & + = § !?', 'et doit etre compris entre 8 et 20 caractères']);
                    header('Location:index.php');

                }
            }else{
            $this->request->getSession()->set('message', "Cette email n'existe pas !");
            header('Location:index.php');
            }
        }else {
            $this->request->getSession()->set('message', 'Rempliser tout les champs');
            header('Location:index.php');
        }
    }

    public function  verifiedEmail($email)
    {
        $user = $this->orm->entityManager()->getRepository(':User');
        $verifiedEmail = $user->findOneBy(['email' => $email]);
        return $verifiedEmail;
    }

    public function sessionLogin($user)
    {

        $this->request->getSession()->set('id', $user->getId());
        $this->request->getSession()->set('roles', $user->getRoles());
        $this->request->getSession()->set('email', $user->getEmail());
        $this->request->getSession()->set('firstName', $user->getFirstName());
        $this->request->getSession()->set('lastName', $user->getLastName());
        $this->request->getSession()->set('image', $user->getImage());
    }

}
