<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Entity\Invitation;
use App\Entity\Promotion;
use App\Form\UserType;
use App\Form\UserSecurityType;
use App\Form\InvitationType;
use App\Services\ApiUtilsTools;
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
    * @Route("/registration", name="registration", methods="GET|POST")
    */
    public function emailRegistration(Request $request,\Swift_Mailer $mailer)
    {    
        // J'instancie promotion pour récuperer toute les promotion et afficher 
        // dans la view le nom de celle-ci
        $promotion = new Promotion;
        $promotionRepo = $this->getDoctrine()->getRepository(Promotion::class);
        $promotions = $promotionRepo->findAll();

        $invitation = new Invitation;
        // Si mon formulaire n'est pas vide je recupère les données du champs email
        if (!empty($_POST)) {
            $emails = isset($_POST['email']) ? $_POST['email'] : '';
            
            // Tableau contenant tout les emails (en enlevant les ";")
            $arrayEmail = explode(";", $emails);
            
            // On enlève chaque espace devant/derrière les mails
            $arrayEmail = array_map('trim', $arrayEmail);
            
            $arrayMailCode = [];

            // On parcout le tableau, pour créer un tableau associatif, les mails (les keys) vont recevoir un code chacun
            foreach($arrayEmail as $email) {
                // On génère les codes aléatoires
                $code = crypt($email, 'itsatrap');
                $arrayMailCode[$email] = $code;
            }
            /*
            * noemielej@yahoo.fr; noemielej@gmail.com
                6/ Sur le tableau associatif, on fait à nouveau un foreach et on crée pour chaque tour de boucle un enregistrement dans la table Invitation avec les champs : email / code / promotion_id / author (prof qui crée l'invit) / user (le compte créé quand l'invit est "consommée")
                7/ Sur le tableau associatif, on fait une dernière fois un foreach et cette fois on envoie les emails avec.
                8/ Dans chaque email se trouve un lien d'inscription formatté comme suit; www.thehub.com/register?code=lecodesecret&email=emaildeletudiant@gmail.com
                9/ Quand il clique sur le lien, ça l'envoie sur la page /register mais dans l'url, on a bien le code et l'email
                9a/ Grace à l'email, on préremplie le champ email du formulaire d'inscription (User Friendly et évite les erreurs d'email)
            */
            $message = (new \Swift_Message('Mail de validation d\'inscription'))
                ->setFrom('hub.oclock@gmail.com')
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'security/emailType.html.twig',[
                        ]
                    ),
                    'text/html'
                    );
            $mailer->send($message);
            return $this->redirectToRoute('app');
        }

        return $this->render('security/registration.html.twig', [
            //'code' => $code_aleatoire,
            'promotions' => $promotions
        ]);
    }


    /**
    * @Route("/registration/sendEmail", name="sendEmailRegistration", methods="GET|POST")
    */
    public function sendEmailRegistration(Request $request)
    {  

        $characts = 'abcdefghijklmnopqrstuvwxyz'; 
        $characts .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';	
        $characts .= '1234567890'; 
        $code_aleatoire = ''; 

        for($i=0;$i < 8;$i++) 
        { 
            $code_aleatoire .= $characts[ rand() % strlen($characts) ]; 
        } 

        // if ($form->isSubmitted() && $form->isValid()) {
            // $em = $this->getDoctrine()->getManager();
            // $em->persist($invitation);
            // $em->flush();
           
            
        //}

    }
}