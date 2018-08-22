<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserSecurityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        
        // last email entered by the user
        // $lastEmail = $authenticationUtils->getLastEmail();
        
        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            // 'last_email' => $lastEmail,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/register", name="register", methods="GET|POST")
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserSecurityType::class, $user);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /* 
                L'encoder permet d'encoder le nouveau MDP de l'utilisateur
                le type d'encodage est pasé sur la section encoders + entité definie dans security.yml
                L'encodage sert à verifier l'egalité d'un mot de passe stocké en base sur un encryptage pprecis avec celui saisie l'ors du login
                mais aussi pour creer un nouveau mdp
             */
            // je recupère le mot de passe encodé de mon nouvel utilisateur
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            // et je le set à mon objet user 
            $user->setPassword($encoded);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app');
        }
        return $this->render('security/signup.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
    * @Route("/email/register", name="email_register", methods="GET|POST")
    */
    public function sendEmailRegister(Request $request, \Swift_Mailer $mailer)
    {   
        $user = New User;
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
            ->setTo('recipient@example.com')
            ->setBody(
                $this->renderView(
                    // templates/emails/registration.html.twig
                    'security/registration.html.twig',
                    array('name' => $user)
                ),
                'text/html'
            )
           
            /* If you also want to include a plaintext version of the message*/
            /*->addPart(
                $this->renderView(
                    'emails/registration.txt.twig',
                    array('firstname' => $user)
                ),
                'text/plain'
            )*/
            
        ;

        $mailer->send($message);

        return $this->render('security/registration.html.twig', [
            'name' => $user,
            // 'form' => $form->createView(),
        ]);
    }
}