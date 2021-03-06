<?php
  namespace App\Controller;

  use App\Entity\Contact;
  use App\Entity\Address;

  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
       * @return Response
       */
       public function index() {
          $contacts= $this->getDoctrine()->getRepository(Contact::class)->findAll();
          return $this->render('contacts/index.html.twig', ['contacts' => $contacts]);
       }

      /**
       * @Route("/contact/show/{id}", name="contact_show")
       * @Method({"GET"})
       * @param $id
       * @return Response
       */
      public function show($id) {
          $contact = $this->getDoctrine()->getRepository(Contact::class)->find($id);
          $addresses = count($contact->getAddresses()) == 0 ? false : $contact->getAddresses();
          return $this->render('contacts/show.html.twig', ['contact' => $contact, 'addresses' => $addresses]);
      }

      /**
       * @Route("/contact/new", name="contact_new")
       * Method({"GET", "POST"})
       * @param Request $request
       * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
       */
      public function new(Request $request) {
          $contact = new Contact();

          $form = $this->createFormBuilder($contact)
              ->add('nome', TextType::class, ['attr' => ['class' => 'form-control']])
              ->add('email', TextType::class, ['attr' => ['class' => 'form-control email' ]])
              ->add('telefone', TextType::class, ['attr' => ['class' => 'form-control']])
              ->add('save', SubmitType::class, [
                  'label' => 'salvar',
                  'attr' => ['class' => 'btn btn-primary mt-3 btn-sm']
              ])
              ->getForm();

          $form->handleRequest($request);

          if($form->isSubmitted() && $form->isValid()) {
              $contact = $form->getData();

              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($contact);
              $entityManager->flush();

              return $this->redirectToRoute('contact_show',['id'=>$contact->getId()]);
          }

          return $this->render('contacts/new.html.twig', array(
              'form' => $form->createView()
          ));
      }

      /**
       * @Route("/contact/edit/{id}", name="contact_edit")
       * Method({"GET","POST"})
       * @param Request $request
       * @param $id
       * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
       */
      public function edit(Request $request, $id) {

          $contact = $this->getDoctrine()->getRepository(Contact::class)->find($id) ;
          $addresses = count($contact->getAddresses()) == 0 ? false : $contact->getAddresses();
          $form = $this->createFormBuilder($contact)
              ->add('nome', TextType::class, ['attr' => ['class' => 'form-control']])
              ->add('email', EmailType::class, ['attr' => ['class' => 'form-control email']])
              ->add('telefone', TextType::class, ['attr' => ['class' => 'form-control']])
              ->add('save', SubmitType::class, [
                  'label' => 'salvar',
                  'attr' => ['class' => 'btn btn-primary mt-3 btn-sm']
              ])
              ->getForm();

          $form->handleRequest($request);

          if($form->isSubmitted() && $form->isValid()) {

              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->flush();

              return $this->redirectToRoute('contact_show',['id'=>$contact->getId()]);
          }

          return $this->render('contacts/edit.html.twig', [
              'form' => $form->createView(), 'addresses' => $addresses, 'contact' => $contact
          ]);
      }

      /**
       * @Route("/contact/delete/{id}")
       * @Method({"DELETE"})
       * @param Request $request
       * @param $id
       * @return \Symfony\Component\HttpFoundation\RedirectResponse
       */
      public function delete(Request $request, $id) {
          $contact = $this->getDoctrine()->getRepository(Contact::class)->find($id);

          $this->getDoctrine()->getRepository(Address::class)->deleteByIdContact($id);

          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->remove($contact);
          $entityManager->flush();

          return $this->redirectToRoute('contact_list');
      }
  }
