<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passEncoder): Response
    {
        $form = $this->createFormBuilder()
            ->add('username', TextType::class, ['label' => 'Nom d\'utilisateur : '])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'Mot de passe : '],
                'second_options' => ['label' => 'Confirmation Mot de passe : '],
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'ROLE_USER' => 'ROLE_USER',
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN',
                ],
                'multiple' => true,
                'label' => 'autorisation : '
            ])
            ->add('register', SubmitType::class, ['attr' => ['class' => 'btn']])
            ->getForm();

            $form->handleRequest($request);
            if($request->isMethod('post') && $form->isValid()) {
                $data = $form->getData();
                $user = new User;
                $user->setUsername($data['username']);
                $user->setPassword($passEncoder->encodePassword($user, $data['password']));
                $user->setRoles($data['roles']);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                return $this->redirect($this->generateUrl('app_login'));
            }

        return $this->render('register/index.html.twig', [
            'form_register' => $form->createView(),
        ]);
    }
}
