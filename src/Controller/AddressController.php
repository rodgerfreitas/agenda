<?php
  namespace App\Controller;

  use App\Entity\Address;

  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\Routing\Annotation\Route;
  use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
  use Symfony\Bundle\FrameworkBundle\Controller\Controller;
  use Symfony\Component\Form\Extension\Core\Type\TextType;
  use Symfony\Component\Form\Extension\Core\Type\TextareaType;
  use Symfony\Component\Form\Extension\Core\Type\SubmitType;


  class AddressController extends Controller {

      /**
       * @Route("/address", name="address_list")
       * @Method({"GET"})
       */
       public function index() {
          $addresses= $this->getDoctrine()->getRepository(Address::class)->findAll();
          return $this->render('addresses/index.html.twig', ['addresses' => $addresses]);
       }

      /**
       * @Route("/address/show/{id}", name="address_show")
       * @Method({"GET"})
       */
      public function show($id) {
          $address = $this->getDoctrine()->getRepository(Address::class)->find($id);
          return $this->render('addresses/show.html.twig',['address' => $address]);
      }

      /**
       * @Route("/address/new", name="address_new")
       * Method({"GET", "POST"})
       */
      public function new(Request $request) {

          if($request->isMethod('post')) {
              $address = new Address;
              $arrPost = $request->get('form');

              $address->setQuadra($arrPost['quadra']);
              $address->setNumero($arrPost['numero']);
              $address->setObservacao($arrPost['observacao']);

              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($address);
              $entityManager->flush();

              return $this->redirectToRoute('address_list');
          }

          return $this->render('addresses/new.html.twig');
      }

      /**
       * @Route("/address/edit/{id}", name="address_edit")
       * Method({"GET","POST"})
       */
      public function edit(Request $request, $id) {

          $address = $this->getDoctrine()->getRepository(Address::class)->find($id) ;

          if($request->isMethod('post')) {
              $arrPost = $request->get('form');

              $address->setQuadra($arrPost['quadra']);
              $address->setNumero($arrPost['numero']);
              $address->setObservacao($arrPost['observacao']);

              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($address);
              $entityManager->flush();

              return $this->redirectToRoute('address_list');
          }

          return $this->render('addresses/edit.html.twig',['address' => $address]);

//          $address = $this->getDoctrine()->getRepository(Address::class)->find($id) ;
//          $form = $this->createFormBuilder($address)
//              ->add('quadra', TextType::class, array('attr' => array('class' => 'form-control')))
//              ->add('numero', TextType::class, array('attr' => array('class' => 'form-control')))
//              ->add('observacao', TextareaType::class, array('attr' => array('class' => 'form-control')))
//              ->add('save', SubmitType::class, array(
//                  'label' => 'salvar',
//                  'attr' => array('class' => 'btn btn-primary mt-3')
//              ))
//              ->getForm();
//
//          $form->handleRequest($request);
//
//          if($form->isSubmitted() && $form->isValid()) {
//
//              $entityManager = $this->getDoctrine()->getManager();
//              $entityManager->flush();
//
//              return $this->redirectToRoute('address_list');
//          }
//
//          return $this->render('addresses/edit.html.twig', array(
//              'form' => $form->createView()
//          ));
      }

      /**
       * @Route("/address/delete/{id}")
       * @Method({"DELETE"})
       */
      public function delete(Request $request, $id) {
          $address = $this->getDoctrine()->getRepository(Address::class)->find($id);

          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->remove($address);
          $entityManager->flush();

          $response = new Response();
          $response->send();
      }
  }
