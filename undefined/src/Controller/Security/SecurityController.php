<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Affectation;
use App\Form\UserSecurityType;
use App\Repository\RoleRepository;
use App\Repository\PromotionRepository;
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
    public function new(Request $request, UserPasswordEncoderInterface $encoder, PromotionRepository $repoPromo, RoleRepository $repoRole): Response
    //A rajouter pour les invitations en injection de dépendances InvitationRepository $repoInvit
    {
        $user = new User();
        $form = $this->createForm(UserSecurityType::class, $user);
        $errors = [];
        $email = "";
        if($request->get('email') !== null){
            $email = $request->get('email');
            $user->setEmail($email);
            $form->get('email')->setData($email);
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // $invit = $repoInvit->findOneBy(array('email' => $user->getEmail())); //je vais chercher l'invit correspondant l'email rentré
            // if ($invit === null){
            //     $errors[] = "Pas d'invitation en attente pour ce compte email";
            // }
            // else{
            //     if (!$invit->getSecretCode() === $request->get('secret_code')){//je vérifie que le code secret est le bon
            //         $errors[] = "Merci de bien utiliser le lien fourni dans le mail: il contient un code secret!";
            //     } 
            // }
            if (empty($error)){
            $em = $this->getDoctrine()->getManager();

            // $promotionId = $invit->getPromotionId();
            // $repoPromo->findOneById($promotionId);
            // $role = $repoRole->findOneBy(array('code' => 'ROLE_STUDENT'));
            // $affectation = new Affectation;
            // $affectation->setPromotion($promotion);
            // $affectation->setRole($role);
            // $affectation->setUser($user);
            // $invit->setUser($user);
            
            /* 
                L'encoder permet d'encoder le nouveau MDP de l'utilisateur
                le type d'encodage est pasé sur la section encoders + entité definie dans security.yml
                L'encodage sert à verifier l'egalité d'un mot de passe stocké en base sur un encryptage precis avec celui saisie l'ors du login
                mais aussi pour creer un nouveau mdp
             */
            // je recupère le mot de passe encodé de mon nouvel utilisateur
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            // et je le set à mon objet user 
            $user->setPassword($encoded);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('login');
            }
        }

        return $this->render('security/signup.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'errors' => $errors,
            'email' => $email
        ]);
    }
}