<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    private $session;


    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/", name="product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/cart", name="product_cart", methods={"GET"})
     */
    public function cart(): Response
    {
        $cart = $this->session->get('cart');
        $cartArray = [];
        $totaal = 0;
        foreach($cart as $id => $product){
            $res = $this->getDoctrine()
                ->getRepository(Product::class)
                ->find($id);

            array_push($cartArray, [$id, $product['aantal'],$res]);
            $totaal = $totaal + ($product['aantal'] * $res->getPrijs());
        }


        return $this->render('product/cart.html.twig', ['cart' =>  $cartArray, 'totaal' => $totaal]);
    }

//    /**
//     * @Route("/afrekenen", name="product_afrekenen", methods={"GET"})
//     */
//    public function afrekenen(Request $request, \Swift_Mailer $mailer): Response
//    {
////        $form = $this->createForm(ContactType::class);
////
////        $form->handleRequest($request);
////
////        if ($form->isSubmitted() && $form->isValid()){
////            $contactFormData = $form->getData();
////            dump($contactFormData);
////        }
////
////        $message = (new \Swift_Message('Afrekenen'))
////            ->setFrom($contactFormData['email'])
////            ->setTo('a2998a2da9-42e5ae@inbox.mailtrap.io')
////            ->setBody(
////                $contactFormData['message'],
////                'text/plain'
////            );
////        $mailer->send($message);
//        return $this->render('product/afrekenen.html.twig');
////            'our_form'=> $form->createView()]);
//    }


    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }



    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }

    /**
     * @Route("/{id}/addtocart", name="product_addtocart", methods={"get"})
     */
    public function addtocart(Product $product, $id): Response
    {
        $cart = $this->session->get('cart');
        if(isset($cart[$id])) {
            $cart[$id]['aantal']++;
        }
        else{
            $cart[$id] = array('aantal' => 1);
        }

        $this->session->set('cart', $cart);

        return $this->redirectToRoute('product_cart');
    }
}

