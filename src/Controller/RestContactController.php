<?php
  namespace App\Controller;

  use App\Entity\Contact;

  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\HttpFoundation\JsonResponse;
  use Symfony\Component\Serializer\Encoder\JsonEncode;
  use Symfony\Component\Serializer\Serializer;
  use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\Routing\Annotation\Route;
  use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


  class RestContactController extends AbstractController {

      protected $serializer;

      public function __construct(){
          $this->serializer = new Serializer([new ObjectNormalizer()],[new JsonEncode()]);
      }

      /**
       * @Route("/rest/list", name="rest_list")
       * @Method({"GET"})
       */
       public function list() {
          $contacts= $this->getDoctrine()->getRepository(Contact::class)->findAll();
          return JsonResponse::fromJsonString($this->serializer->serialize($contacts,'json'));
       }

      /**
       * @Route("/rest/get/{id}", name="rest_get")
       * @Method({"GET"})
       */
      public function get($id) {
          $contact = $this->getDoctrine()->getRepository(Contact::class)->find($id);
          return JsonResponse::fromJsonString($this->serializer->serialize($contact,'json'));
      }

      /**
       * @Route("/rest/insert", name="rest_insert")
       * Method({"POST"})
       */
      public function insert(Request $request) {

          $contact = new Contact();

          $post = json_decode($request->getContent());

          $contact->setNome($post->nome);
          $contact->setEmail($post->email);
          $contact->setTelefone($post->telefone);

          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($contact);
          $entityManager->flush();

          return JsonResponse::fromJsonString($this->serializer->serialize($contact,'json'));
      }

      /**
       * @Route("/rest/update/{id}", name="rest_update")
       * Method({"PUT"})
       */
      public function update(Request $request, $id) {

          $contact = $this->getDoctrine()->getRepository(Contact::class)->find($id) ;

          $post = json_decode($request->getContent());

          $contact->setNome($post->nome);
          $contact->setEmail($post->email);
          $contact->setTelefone($post->telefone);

          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->flush();

          return JsonResponse::fromJsonString($this->serializer->serialize($contact,'json'));
      }

      /**
       * @Route("/rest/delete/{id}" , name="rest_delete")
       * @Method({"DELETE"})
       */
      public function delete(Request $request, $id) {
          $contact = $this->getDoctrine()->getRepository(Contact::class)->find($id);

          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->remove($contact);
          $entityManager->flush();

          return JsonResponse::fromJsonString($this->serializer->serialize($contact,'json'));
      }
  }
