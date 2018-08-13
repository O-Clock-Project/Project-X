<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Promotion;
use App\Entity\Affectation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

// J'importe le class de mes données fictive
use App\DataFixtures\Faker\DataProvider;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
// Import de Faker
use Faker;

class AppFixtures extends Fixture
{
    private $encoder;


    // Fonction permettant l'encodage des mot de passe dans la bdd
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // On crée une instance de Faker en français
        $generator = Faker\Factory::create('fr_FR');

        // Je crée en "dur" des instances de User et de Role
        // pour l'exportation, si il y a une regénération de fixtures
        // elles seront toujours disponible
        $roleAdmin = New Role();
        $roleAdmin->setCode('ROLE_ADMINISTRATOR');
        $roleAdmin->setname('Administrateur');
        $roleAdmin->setIsActive('1');
        
        $roleUser = New Role();
        $roleUser->setCode('ROLE_USER');
        $roleUser->setname('Etudiant');
        $roleUser->setIsActive('1');
        
        $roleModerator = New Role();
        $roleModerator->setCode('ROLE_MODERATOR');
        $roleModerator->setname('Professeur');
        $roleModerator->setIsActive('1');
        
        $manager->persist($roleAdmin);
        $manager->persist($roleUser);
        $manager->persist($roleModerator);

        
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        //$userAdmin->setPassword($this->encoder->encodePassword($userAdmin, 'admin'));
        $userAdmin->setPassword('admin');
        $userAdmin->setEmail('admin@admin.fr');
        $userAdmin->setBirthday(new DateTime("10-8-1980"));
        $userAdmin->setusername('Charly');
        $userAdmin->setfirstName('Joly');
        $userAdmin->setLastName('Charles');
        $userAdmin->setPseudoGithub('Charly');
        $userAdmin->setZip('95522');
        $userAdmin->setIsActive('1');

        $manager->persist($userAdmin);

        $userModerator = new User();
        $userModerator->setUsername('professeur');
        //$userModerator->setPassword($this->encoder->encodePassword($userModerator, 'prof'));
        $userModerator->setPassword('prof');
        $userModerator->setEmail('prof@prof.fr');
        $userModerator->setBirthday(new DateTime("28-12-1985"));//ddMMyyyy
        $userModerator->setusername('Soso85');
        $userModerator->setfirstName('Martin');
        $userModerator->setLastName('Sophie');
        $userModerator->setPseudoGithub('Soso85');
        $userModerator->setZip('18522');
        $userModerator->setIsActive('1');

        $manager->persist($userModerator);
        
        // Affectation pour un admin
        $AffectationUserAdmin = new Affectation();
        $AffectationUserAdmin->setRole($roleAdmin);
        $AffectationUserAdmin->setUser($userAdmin);
        $AffectationUserAdmin->setIsActive('1');
        
        $manager->persist($AffectationUserAdmin);

        // Affectation pour un Professeur
        $AffectationUserProf = new Affectation();
        $AffectationUserProf->setRole($roleModerator);
        $AffectationUserProf->setUser($userModerator);
        $AffectationUserProf->setIsActive('1');
        
        $manager->persist($AffectationUserProf);

        // Affectation pour un utilisateur
        $AffectationUserSimple = new Affectation();
        $AffectationUserSimple->setRole($roleUser);
        //$AffectationUserSimple->setUser($userSimple);
        $AffectationUserSimple->setIsActive('1');
        
        $manager->persist($AffectationUserSimple);

        // J'instacie "DataProvider" où ce trouvent mes données fictive
        $generator->addProvider(new DataProvider($generator));
        
        // On passe le Manager de Doctrine à Faker 
        $populator = new Faker\ORM\Doctrine\Populator($generator, $manager);
        
        $populator->addEntity('App\Entity\Promotion', 10,[
             'name' => function() use ($generator) { return $generator->unique()->promotionName(); },
             'is_active' => 1,
        ]);
        $populator->addEntity('App\Entity\Speciality', 3,[
            'name' => function() use ($generator) { return $generator->unique()->specialityName(); },
            'is_active' => 1,
        ]);
        $populator->addEntity('App\Entity\Support', 3,[
            'name' => function() use ($generator) { return $generator->unique()->supportName(); },
            'is_active' => 1,
        ]);
        $populator->addEntity('App\Entity\Locale',6 ,[
            'name' => function() use ($generator) { return $generator->unique()->localeName(); },
            'is_active' => 1,
        ]);
        $populator->addEntity('App\Entity\Difficulty',3 ,[
            'name' => function() use ($generator) { return $generator->unique()->difficultyName(); },
            'is_active' => 1,
            'level' => function() use ($generator) { 
                return $generator->unique()->numberBetween($min = 1, $max = 3); 
            },

        ]);

        // On peut passer en 3ème paramètre le générateur de notre choix, ici un "userName" cohérent pour Person
        $populator->addEntity('App\Entity\User', 20, array(
            'is_active' => 1,
            // 'affectations' => 92,
            'username' => function() use ($generator) { 
                return $generator->userName(); 
            },
            'first_name' => function() use ($generator) { 
                return $generator->firstName(); 
            },
            'last_name' => function() use ($generator) { 
                return $generator->lastName(); 
            },
            'email' => function() use ($generator) { 
                return $generator->email(); 
            },
            'password' => function() use ($generator) { 
                return $generator->word(); 
            },
            'pseudo_github' => function() use ($generator) { 
                return $generator->userName(); 
             },
            'birthday' => function() use ($generator) { 
                return $generator->dateTime(); 
            },
            'zip' => function() use ($generator) { 
                return $generator->numberBetween($min = 1000, $max = 9000); 
            },
        ));

        $populator->addEntity('App\Entity\Tag',10 ,[
            'is_active' => 1,
            'label' => function() use ($generator) { return $generator->unique()->tagName(); },
        ]);

        $populator->addEntity('App\Entity\Bookmark', 20, array(
            'is_active' => 1,
            'banned' => 0,
            'title' => function() use ($generator) { 
                return $generator->sentence($nbWords = 6, $variableNbWords = true); 
            },
            'resume' => function() use ($generator) { 
                return $generator->paragraph($nbSentences = 3, $variableNbSentences = true); 
            },
            'url' => function() use ($generator) { 
                return $generator->url(); 
            },
            'image' => function() use ($generator) { 
                return $generator->imageUrl($width = 640, $height = 480); 
            },
            'published_at' => function() use ($generator) { 
                return $generator->dateTime(); 
            },
            'author' => function() use ($generator) { 
                return $generator->Name(); 
            },
        )); 

        $populator->addEntity('App\Entity\Announcement',10 ,[
            'is_active' => 1,
            'frozen' => 0,
            'closing_at' => function() use ($generator) { 
                return $generator->dateTimeBetween($startDate = 'now', $endDate = '+1 years'); 
            },
            'title' => function() use ($generator) { 
                return $generator->sentence($nbWords = 6, $variableNbWords = true); 
            },
            'body' => function() use ($generator) { 
                return $generator->paragraph($nbSentences = 3, $variableNbSentences = true); 
            },

        ]);

        $populator->addEntity('App\Entity\Comment',20 ,[
            'is_active' => 1,
            'banned' => 0,
            'body' => function() use ($generator) { 
                return $generator->sentence($nbWords = 25, $variableNbWords = true);
            },
        ]);

        $populator->addEntity('App\Entity\WarningBookmark',10 ,[
            'is_active' => 1,
            'message' => function() use ($generator) { 
                return $generator->sentence($nbWords = 25, $variableNbWords = true);
            },
        ]);

        $populator->addEntity('App\Entity\Vote',20 ,[
            'is_active' => 1,
            'value' => function() use ($generator) { 
                return $generator->numberBetween($min = 0, $max = 1);
            },
        ]);

        $populator->addEntity('App\Entity\PromotionLink',10 ,[
            'is_active' => 1,
            'name' => function() use ($generator) { 
                return $generator->sentence($nbWords = 6);
            },
            'url' => function() use ($generator) { 
                return $generator->url();
            },
            'icon' => function() use ($generator) { 
                return $generator->imageUrl($width = 640, $height = 480) ;
            },
        ]);

        $populator->addEntity('App\Entity\AnnouncementType',10 ,[
            'is_active' => 1,
            'name' => function() use ($generator) { 
                return $generator->text($maxNbChars = 6)  ; 
            },
        ]);

        // On demande à Faker d'éxécuter les ajouts en BDD
        $inserted = $populator->execute();

        /*$promotions = $inserted['App\Entity\Promotion'];    
        $specialities = $inserted['App\Entity\Speciality']; 
        $supports = $inserted['App\Entity\Support']; 
        $languages = $inserted['App\Entity\Locale'];  */
        
        $promotions = $inserted['App\Entity\Promotion'];
        foreach($inserted['App\Entity\User'] as $user)
        {
            $affectation = new Affectation();
            $affectation->setRole($roleUser);
            $affectation->setUser($user);
            shuffle($promotions);
            $affectation->setPromotion($promotions[0]);
        }
        
        $manager->flush();
        
    }
}

