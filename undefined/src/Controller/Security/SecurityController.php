<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Promotion;
use App\Entity\Invitation;
use App\Form\InvitationType;
use App\Form\UserSecurityType;
use App\Services\ApiUtilsTools;
use App\Repository\PromotionRepository;
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
     * Methode permettant la connection de l'user
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
     * Methode permettant l'ajout d'un utilisateur (après reception du mail)
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
    * @Route("/registration", name="registration", methods="GET|POST")
    * Methode permettant la pré-inscription par mail des élèves 
    */
    public function emailRegistration(Request $request,\Swift_Mailer $mailer, EntityManagerInterface $em, PromotionRepository $repoPromo)
    {    
        // On instancie promotion pour récupérer toutes les promotions et afficher 
        // dans la view le nom de celle-ci
        $promotion = new Promotion;
        $promotionRepo = $this->getDoctrine()->getRepository(Promotion::class);
        $promotions = $promotionRepo->findAll();

        // Si mon formulaire n'est pas vide je recupère les données du champs email
        if (!empty($_POST)) {
    
            $emails = isset($_POST['email']) ? $_POST['email'] : '';
            
            // Tableau contenant tout les emails (en enlevant les ";")
            $arrayEmail = explode(";", $emails);
            
            // On enlève chaque espace devant/derrière les mails en fessant une boucle
            $arrayEmail = array_map('trim', $arrayEmail);
            
            $arrayMailCode = [];

            // On parcourt le tableau, pour créer un tableau associatif, les mails (= keys) vont recevoir un code chacun
            foreach($arrayEmail as $email) {
                // On génère les codes aléatoires
                $code = crypt($email, 'itsatrap');
                $arrayMailCode[$email] = $code;
            }

            // On parcourt le tableau et on enregistre dans la table Invitation
            foreach($arrayMailCode as $email=>$code) {
                // On récupère le select du form
                $promotionSelect = $_POST['Promotion'];
                // On récupère la promo correspondante avec l'id
                $promotion = $repoPromo->findOneById($promotionSelect);
                
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
            foreach($arrayMailCode as $email=>$code) {
                $message = (new \Swift_Message('Mail de validation d\'inscription'))
                    ->setFrom('hub.oclock@gmail.com')
                    ->setTo($email)
                    ->setBody(
                         $this->renderView(
                            'security/emailType.html.twig',[
                                'email' => $email,
                                'code' => $code
                            ]
                        ),
                        'text/html'
                        );
                    $mailer->send($message);
                }
            // Flash Message si l'invitaion'c'est bien envoyé
            $this->addFlash(
                'notice',
                'Votre invitation a bien été envoyé.'
            );   
        }
          
        return $this->render('security/registration.html.twig', [
            'promotions' => $promotions
        ]);
    }

    /**
    * @Route("/registration/sendEmail", name="sendEmailRegistration", methods="GET|POST")
    * Methode permettant de récolter les informations après soumission du form 
    */
    public function sendEmailRegistration(Request $request)
    {  
  
    }
}