<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationFormController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('Password')->getData()
                )
            );
            $user->setRoles($user->getRoles());
            $user->setEmail($form->get('email')->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // TODO: implement email sending

            return $this->redirectToRoute('success');
        }

        return $this->render('registrationForm/registration_form.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/check_email", name="check_email")
     * @param Request $request
     * @return JsonResponse
     */
    public function checkEmail(Request $request): JsonResponse
    {
        $email = json_decode($request->getContent(), true);
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $email = $propertyAccessor->getValue($email, '[email]');

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneByEmail($email);

        if (!$user) {
            return new JsonResponse(
                ['is_email_exist' => false],
                200,
                ['Content-Type' => 'application/json; charset=utf-8']
            );
        } else {
            return new JsonResponse(
                ['is_email_exist' => true],
                200,
                ['Content-Type' => 'application/json; charset=utf-8']
            );
        }
    }
}
