<?php
  namespace App\Controller;

  use App\Entity\Address;

  use App\Entity\Contact;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\Routing\Annotation\Route;
  use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
  use Symfony\Bundle\FrameworkBundle\Controller\Controller;
  use Symfony\Component\Form\Extension\Core\Type\TextType;
  use Symfony\Component\Form\Extension\Core\Type\TextareaType;
  use Symfony\Component\Form\Extension\Core\Type\SubmitType;
  use Symfony\Component\Validator\Validator\ValidatorInterface;


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
       * @Route("/address/new/{id}", name="address_new")
       * Method({"GET", "POST"})
       */
      public function new(Request $request, ValidatorInterface $validator, $id) {

          if($request->isMethod('post')) {
              $address = new Address;
              $arrPost = $request->get('form');

              $address->setContact($this->getDoctrine()->getRepository(Contact::class)->find($id));
              $address->setQuadra($arrPost['quadra']);
              $address->setNumero($arrPost['numero']);
              $address->setObservacao($arrPost['observacao']);

              $errors = $validator->validate($address);

              if (count($errors) > 0){

                  echo <pre>var_dump($errors);

                  $this->addFlash(
                      'error',
                      'Your changes were saved!'
                  );

                  return $this->render('addresses/new.html.twig',['address'=> $address]);
              }

              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($address);
              $entityManager->flush();

              return $this->redirectToRoute('contact_show',['id'=>$id]);
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

              return $this->redirectToRoute('contact_show',['id'=>$id]);
          }

          return $this->render('addresses/edit.html.twig',['address' => $address]);
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

          return $this->redirectToRoute('contact_show',['id'=>$id]);
      }
  }
