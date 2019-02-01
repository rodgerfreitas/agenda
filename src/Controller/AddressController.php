<?php
  namespace App\Controller;

  use App\Entity\Address;

  use App\Entity\Contact;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\Routing\Annotation\Route;
  use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
  use Symfony\Bundle\FrameworkBundle\Controller\Controller;
  use Symfony\Component\Form\Extension\Core\Type\TextType;
  use Symfony\Component\Form\Extension\Core\Type\TextareaType;
  use Symfony\Component\Form\Extension\Core\Type\SubmitType;
  use Symfony\Component\Validator\Validator\ValidatorInterface;


  class AddressController extends AbstractController {

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
       * @param $id
       * @return Response
       */
      public function show($id) {
          $address = $this->getDoctrine()->getRepository(Address::class)->find($id);
          return $this->render('addresses/show.html.twig',['address' => $address]);
      }

      /**
       * @Route("/address/new/{id}", name="address_new")
       * Method({"GET", "POST"})
       * @param Request $request
       * @param ValidatorInterface $validator
       * @param $id
       * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
       */
      public function new(Request $request, ValidatorInterface $validator, $id) {

          $address = new Address;
          if($request->isMethod('post')) {

              $arrPost = $request->get('form');

              $address->setContact($this->getDoctrine()->getRepository(Contact::class)->find($id));
              $address->setQuadra($arrPost['quadra']);
              $address->setNumero($arrPost['numero']);
              $address->setObservacao($arrPost['observacao']);

              $errors = $validator->validate($address);

              if (count($errors) > 0){
                  return $this->render('addresses/new.html.twig',['address'=> $address,'errors'=>$errors]);
              }

              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($address);
              $entityManager->flush();

              return $this->redirectToRoute('contact_show',['id'=>$id]);
          }

          return $this->render('addresses/new.html.twig',['address'=> $address,'errors' => '']);
      }

      /**
       * @Route("/address/edit/{id}", name="address_edit")
       * Method({"GET","POST"})
       * @param Request $request
       * @param ValidatorInterface $validator
       * @param $id
       * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
       */
      public function edit(Request $request, ValidatorInterface $validator, $id) {

          $address = $this->getDoctrine()->getRepository(Address::class)->find($id) ;

          if($request->isMethod('post')) {
              $arrPost = $request->get('form');

              $address->setQuadra($arrPost['quadra']);
              $address->setNumero($arrPost['numero']);
              $address->setObservacao($arrPost['observacao']);

              $errors = $validator->validate($address);

              if (count($errors) > 0){
                  return $this->render('addresses/edit.html.twig',['address'=> $address,'errors'=>$errors]);
              }

              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($address);
              $entityManager->flush();

              return $this->redirectToRoute('contact_show',['id'=> $address->getIdcontact() ]);
          }

          return $this->render('addresses/edit.html.twig',['address'=> $address,'errors' => '']);
      }

      /**
       * @Route("/address/delete/{id}")
       * @Method({"DELETE"})
       * @param Request $request
       * @param $id
       * @return \Symfony\Component\HttpFoundation\RedirectResponse
       */
      public function delete(Request $request, $id) {
          $address = $this->getDoctrine()->getRepository(Address::class)->find($id);

          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->remove($address);
          $entityManager->flush();

          return $this->redirectToRoute('contact_show',['id'=>$id]);
      }
  }
