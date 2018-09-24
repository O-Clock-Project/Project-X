<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Invitation;
use App\Entity\Affectation;
use App\Form\UserSecurityType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Repository\PromotionRepository;
use App\Repository\InvitationRepository;
use Doctrine\ORM\EntityManagerInterface;
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
            'error'         => $error,
        ));
    }

    /**
     * @Route("/register", name="register", methods="GET|POST")
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder, PromotionRepository $repoPromo, RoleRepository $repoRole, InvitationRepository $repoInvit): Response
    {


        $user = new User();
        $form = $this->createForm(UserSecurityType::class, $user);
        $errors = [];
        $message = "";
        $email= "";

        if($request->get('email') !== null && $request->get('code') !== null){
            $received   = $request->get('code');
            $correct  = crypt($request->get('email'), 'itsatrap');
            // dump($correct);die;
            if($received == $correct){
                $email = $request->get('email');
                $message = "Bienvenue dans ta nouvelle demeure, " . $email ." !";
                $form->get('email')->setData($email);
            }
            else{
                $message= "Bien tenté mais il va falloir faire mieux que ça...";
            }
        }
        else{
            $message = "Tu as besoin d'utiliser le lien d'inscription reçu par email pour pouvoir t'inscrire!";
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $invit = $repoInvit->findOneBy(array('email' => $user->getEmail())); //je vais chercher l'invit correspondant à l'email rentré
            
            // if ($invit === null){
            //     $errors[] = "Pas d'invitation en attente pour le compte email " . $user->getEmail() ;
            // }
            // if(!($invit->getSecretCode() === $request->get('code'))){//je vérifie que le code secret est le bon
            //         $errors[] = "Merci de bien utiliser le lien fourni dans le mail sinon tu ne pourras pas t'inscrire!";
            // }
 
            if (empty($errors)){
            $em = $this->getDoctrine()->getManager();

            $promotion = $invit->getPromotion();
            $role = $repoRole->findOneBy(array('code' => 'ROLE_STUDENT'));
            $affectation = new Affectation;
            $affectation->setPromotion($promotion);
            $affectation->setRole($role);
            $affectation->setUser($user);
            $invit->setCreatedUser($user);
            
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
            $em->persist($affectation);
            $em->flush();
            return $this->redirectToRoute('login');
            }
        }

        return $this->render('security/signup.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'errors' => $errors,
            'message' => $message
        ]);
    }

    /**
    * @Route("/registration", name="registration", methods="GET|POST")
    * Methode permettant la pré-inscription par mail des élèves 
    */
    public function emailRegistration(Request $request,\Swift_Mailer $mailer, EntityManagerInterface $em, PromotionRepository $repoPromo, UserRepository $repoUser, InvitationRepository $repoInvit)
    {    
        // On instancie promotion pour récupérer toutes les promotions et afficher 
        // dans la view le nom de celle-ci
        $promotions = $repoPromo->findAll();
        // Si mon formulaire n'est pas vide je recupère les données du champs email
        if (!empty($_POST['email'])) {
            
            $emails = isset($_POST['email']) ? $_POST['email'] : '';
            

            // Tableau contenant tout les emails (en enlevant les ";")
            $arrayEmail = explode(";", $emails);
            
            // On enlève chaque espace devant/derrière les mails en fessant une boucle
            $arrayEmail = array_map('trim', $arrayEmail);
            foreach($arrayEmail as $email){
                $existingUser = $repoUser->findOneBy(array('email' => $email));
                $existingInvit = $repoInvit->findOneBy(array('email' => $email));
                if(empty($existingUser) && empty($existingInvit)){
                    $arrayValidEmail[] = $email;
                }
                elseif (!empty($existingUser)){
                    $errors[] = $email;
                    $this->addFlash(
                        'notice',
                        'L\'email ' . $email . ' est déjà associé à un membre inscrit :' . $existingUser->getUsername()
                    ); 
                }
                elseif (!empty($existingInvit)){
                    $errors[] = $email;
                    $this->addFlash(
                        'notice',
                        'L\'email ' . $email . ' est déjà associé à une invitation : id ' . $existingInvit->getId() 
                        . ' crée le ' . date_format($existingInvit->getCreatedAt(), 'd/m/y') . ' par ' . $existingInvit->getSender()->getUsername()
                    ); 
                }

            
            }

            if(!isset($errors)){

                $arrayMailCode = [];
                // On parcourt le tableau, pour créer un tableau associatif, les mails (= keys) vont recevoir un code chacun
                foreach($arrayValidEmail as $email) {
                    // On génère les codes aléatoires
                    $code = crypt($email, 'itsatrap');
                    $arrayMailCode[$email] = $code;
                }
                $promotionSelect = $_POST['promotion'];
                $promotion = $repoPromo->findOneById($promotionSelect);
                
                // On parcourt le tableau et on enregistre dans la table Invitation
                foreach($arrayMailCode as $email=>$code) {
                    // On récupère le select du form
                    // On récupère la promo correspondante avec l'id
                    
                    // On instancie Invitation puis on insert les données en BDD
                    $invitation = new Invitation();
                    $invitation->setEmail($email);
                    $invitation->setSecretCode($code);
                    $invitation->setPromotion($promotion);
                    $invitation->setSender($this->getUser());
                
                    //on persiste l'objet en BDD
                    $em->persist($invitation);
                    $em->flush();
                }
                
                
                
                // On parcourt le tableau, pour envoyer une invitation à chaque mail saisie 
                foreach($arrayMailCode as $email=>$code) 
                {
                    $message = (new \Swift_Message('Mail de validation d\'inscription'));
                    $logo_src = $message->embed(\Swift_Image::fromPath('images/logo_bold_jaune.png'));
                    $message->setFrom('hub.oclock@gmail.com')
                        ->setTo($email)
                        ->setBody(
                            $this->renderView(
                                'security/emailType.html.twig',[
                                    'email' => $email,
                                    'code' => $code,
                                    'logo_src' => $logo_src,
                                    'sender' => $this->getUser()->getUsername(),
                                    'promotion' => $promotion->getName(),
                                ]
                            ),
                            'text/html'
                            );
                        $mailer->send($message);
                        // Flash Message si l'invitaion'c'est bien envoyé
                        $this->addFlash(
                            'notice',
                            'Votre invitation à ' . $email.' a bien été envoyée.'
                        );   
                    }
            }
        }
        else{
                if(isset($_POST['promotion'])){
                // Flash Message si l'invitaion'c'est bien envoyé
                $this->addFlash(
                    'notice',
                    'Vous n\'avez pas rentré d\'emails.'
                );  
            }
        }
        
        return $this->render('security/registration.html.twig', [
            'promotions' => $promotions
        ]);
    }

}