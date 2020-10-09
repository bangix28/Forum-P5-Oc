<?php


namespace App\Services\captcha;


use App\Controller\MainController;

class Captcha extends MainController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function captcha()
    {
        $response = $this->request->getPost()->get('recaptcha_response');
        if (isset($response)) {
            $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
            $recaptcha_secret = 'Votre clef serveur';
            $recaptcha_response = $this->request->getPost()->get('recaptcha_response');

            // Make and decode POST request:
            $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
            $recaptcha = json_decode($recaptcha);

            // Take action based on the score returned:
            if ($recaptcha->score >= 0.5) {
                return true;
            } else {
                return false;
            }
        }
    }
}
