<?php
  namespace App\Controller;

  use App\Entity\Contact;
  use App\Entity\Address;

  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\HttpFoundation\JsonResponse;
  use Symfony\Component\Serializer\Encoder\JsonEncode;
  use Symfony\Component\Serializer\Serializer;
  use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\Routing\Annotation\Route;
  use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
  use Symfony\Component\Validator\Validator\ValidatorInterface;


  class RestContactController extends AbstractController {

      protected $serializer;

      public function __construct()
      {
          $this->serializer = new Serializer([new ObjectNormalizer()],[new JsonEncode()]);
      }

      /**
       * @Route("/rest/list", name="rest_list")
       * @Method({"GET"})
       * @return JsonResponse
       */
       public function list()
       {
          $contacts= $this->getDoctrine()->getRepository(Contact::class)->findAll();
          return JsonResponse::fromJsonString($this->serializer->serialize($contacts,'json'));
       }

      /**
       * @Route("/rest/get/{id}", name="rest_get")
       * @Method({"GET"})
       * @param \Symfony\Bundle\FrameworkBundle\Controller\string $id
       * @return object|JsonResponse
       */
      public function get($id)
      {
          $contact = $this->getDoctrine()->getRepository(Contact::class)->find($id);
          return JsonResponse::fromJsonString($this->serializer->serialize($contact,'json'));
      }

      /**
       * @Route("/rest/insert", name="rest_insert")
       * Method({"POST"})
       * @param Request $request
       * @param ValidatorInterface $validator
       * @return JsonResponse
       */
      public function insert(Request $request, ValidatorInterface $validator)
      {
          $contact = new Contact();
          $response = ['status' => 'OK', 'errors'=> [], 'data' => null];

          $post = json_decode($request->getContent());

          $contact->setNome($post->nome);
          $contact->setEmail($post->email);
          $contact->setTelefone($post->telefone);

          $response['data'] = $contact;
          $errors = $validator->validate($contact);

          if (count($errors) > 0){
              $response['status'] = 'NOK';
              foreach($errors as $error){
                  $response['errors'][] = $error->getMessage();
              }
              return JsonResponse::fromJsonString($this->serializer->serialize($response,'json'));
          }

          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($contact);
          $entityManager->flush();

          return JsonResponse::fromJsonString($this->serializer->serialize($response,'json'));
      }

      /**
       * @Route("/rest/update/{id}", name="rest_update")
       * Method({"PUT"})
       * @param Request $request
       * @param $id
       * @param ValidatorInterface $validator
       * @return JsonResponse
       */
      public function update(Request $request, $id, ValidatorInterface $validator)
      {
          $contact = $this->getDoctrine()->getRepository(Contact::class)->find($id);
          $response = ['status' => 'OK', 'errors'=> [], 'data' => null];

          $post = json_decode($request->getContent());

          $contact->setNome($post->nome);
          $contact->setEmail($post->email);
          $contact->setTelefone($post->telefone);

          $response['data'] = $contact;
          $errors = $validator->validate($contact);

          if (count($errors) > 0){
              $response['status'] = 'NOK';
              foreach($errors as $error){
                  $response['errors'][] = $error->getMessage();
              }
              return JsonResponse::fromJsonString($this->serializer->serialize($response,'json'));
          }

          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->flush();

          return JsonResponse::fromJsonString($this->serializer->serialize($response,'json'));
      }

      /**
       * @Route("/rest/delete/{id}" , name="rest_delete")
       * @Method({"DELETE"})
       * @param Request $request
       * @param $id
       * @return JsonResponse
       */
      public function delete(Request $request, $id)
      {
          $contact = $this->getDoctrine()->getRepository(Contact::class)->find($id);

          $this->getDoctrine()->getRepository(Address::class)->deleteByIdContact($id);

          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->remove($contact);
          $entityManager->flush();

          return JsonResponse::fromJsonString($this->serializer->serialize($contact,'json'));
      }
  }
