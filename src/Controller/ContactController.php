<?php
  namespace App\Controller;

  use App\Entity\Contact;

  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\Routing\Annotation\Route;
  use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
  use Symfony\Component\Form\Extension\Core\Type\TextType;
  use Symfony\Component\Form\Extension\Core\Type\SubmitType;


  class ContactController extends AbstractController {

      /**
       * @Route("/", name="contact_list")
       * @Method({"GET"})
       */
       public function index() {
          $contacts= $this->getDoctrine()->getRepository(Contact::class)->findAll();
          return $this->render('contacts/index.html.twig', array('contacts' => $contacts));
       }

      /**
       * @Route("/contact/show/{id}", name="contact_show")
       * @Method({"GET"})
       */
      public function show($id) {
          $contact = $this->getDoctrine()->getRepository(Contact::class)->find($id);
          return $this->render('contacts/show.html.twig', array('contact' => $contact));
      }

      /**
       * @Route("/contact/new", name="contact_new")
       * Method({"GET", "POST"})
       */
      public function new(Request $request) {
          $contact = new Contact();

          $form = $this->createFormBuilder($contact)
              ->add('nome', TextType::class, array('attr' => array('class' => 'form-control')))
              ->add('email', TextType::class, array('attr' => array('class' => 'form-control')))
              ->add('telefone', TextType::class, array('attr' => array('class' => 'form-control')))
              ->add('save', SubmitType::class, array(
                  'label' => 'salvar',
                  'attr' => array('class' => 'btn btn-primary mt-3')
              ))
              ->getForm();

          $form->handleRequest($request);

          if($form->isSubmitted() && $form->isValid()) {
              $contact = $form->getData();

              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($contact);
              $entityManager->flush();

              return $this->redirectToRoute('contact_list');
          }

          return $this->render('contacts/new.html.twig', array(
              'form' => $form->createView()
          ));
      }

      /**
       * @Route("/contact/edit/{id}", name="contact_edit")
       * Method({"GET","POST"})
       */
      public function edit(Request $request, $id) {

          $contact = $this->getDoctrine()->getRepository(Contact::class)->find($id) ;
          $form = $this->createFormBuilder($contact)
              ->add('nome', TextType::class, array('attr' => array('class' => 'form-control')))
              ->add('email', TextType::class, array('attr' => array('class' => 'form-control')))
              ->add('telefone', TextType::class, array('attr' => array('class' => 'form-control')))
              ->add('save', SubmitType::class, array(
                  'label' => 'salvar',
                  'attr' => array('class' => 'btn btn-primary mt-3')
              ))
              ->getForm();

          $form->handleRequest($request);

          if($form->isSubmitted() && $form->isValid()) {

              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->flush();

              return $this->redirectToRoute('contact_list');
          }

          return $this->render('contacts/edit.html.twig', array(
              'form' => $form->createView()
          ));
      }

      /**
       * @Route("/contact/delete/{id}")
       * @Method({"DELETE"})
       */
      public function delete(Request $request, $id) {
          $contact = $this->getDoctrine()->getRepository(Contact::class)->find($id);

          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->remove($contact);
          $entityManager->flush();

          $response = new Response();
          $response->send();
      }
  }
