<?php


namespace App\Mailer;


class Mailer
{
    /***
     * @var \Swift_Mailer
     */
    private $mailer;
    /***
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendConfirmationEmail(){

    }
}