<?php

namespace App\Controller;

use App\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/afrekenen", name="afrekenen")
     */
    public function index()
    {
        $form = $this->createForm(ContactType::class);
        return $this->render('afrekenen.html.twig',[
                  'form' => $form,
                  'form' => $form->createView(),
            ]);
    }
}
