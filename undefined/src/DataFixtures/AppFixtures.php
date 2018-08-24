<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Promotion;
use App\Entity\Affectation;
use App\Entity\PromotionLink;
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
        $faker = Faker\Factory::create('fr_FR');
        
        // J'instancie "DataProvider" où ce trouvent mes données fictive
        $faker->addProvider(new DataProvider($faker));
        
        // On passe le Manager de Doctrine à Faker 
        $populator = new Faker\ORM\Doctrine\Populator($faker, $manager);
        
        
        // Je crée en "dur" des instances de User et de Role
        // pour l'exportation, si il y a une regénération de fixtures
        // elles seront toujours disponible
        $roleAdmin = New Role();
        $roleAdmin->setCode('ROLE_ADMINISTRATOR');
        $roleAdmin->setname('Administrateur');
        $roleAdmin->setIsActive('1');
        
        $roleUser = New Role();
        $roleUser->setCode('ROLE_STUDENT');
        $roleUser->setname('Etudiant');
        $roleUser->setIsActive('1');
        
        $roleModerator = New Role();
        $roleModerator->setCode('ROLE_TEACHER');
        $roleModerator->setname('Professeur');
        $roleModerator->setIsActive('1');
        
        $manager->persist($roleAdmin);
        $manager->persist($roleUser);
        $manager->persist($roleModerator);

        
        $userAdmin = new User();
        $userAdmin->setPassword($this->encoder->encodePassword($userAdmin, 'admin'));
        //$userAdmin->setPassword('admin');
        $userAdmin->setEmail('admin@admin.fr');
        $userAdmin->setBirthday(new DateTime("10-8-1980"));//ddMMyyyy
        $userAdmin->setUsername('Charly');
        $userAdmin->setFirstName('Joly');
        $userAdmin->setLastName('Charles');
        $userAdmin->setPseudoGithub('Charly');
        $userAdmin->setZip('95522');
        $userAdmin->setIsActive('1');
        
        $manager->persist($userAdmin);
        
        $userModerator = new User();
        $userModerator->setPassword($this->encoder->encodePassword($userModerator, 'prof'));
        //$userModerator->setPassword('prof');
        $userModerator->setEmail('prof@prof.fr');
        $userModerator->setBirthday(new DateTime("28-12-1985"));//ddMMyyyy
        $userModerator->setUsername('Soso85');
        $userModerator->setFirstName('Martin');
        $userModerator->setLastName('Sophie');
        $userModerator->setPseudoGithub('Soso85');
        $userModerator->setZip('18522');
        $userModerator->setIsActive('1');
        
        $manager->persist($userModerator);

        $userSimple = new User();
        $userSimple->setPassword($this->encoder->encodePassword($userModerator, 'user'));
        //$userModerator->setPassword('user');
        $userSimple->setEmail('user@user.fr');
        $userSimple->setBirthday(new DateTime("15-02-1990"));//ddMMyyyy
        $userSimple->setUsername('Clem');
        $userSimple->setFirstName('Gillois');
        $userSimple->setLastName('Clément');
        $userSimple->setPseudoGithub('Clem');
        $userSimple->setZip('26730');
        $userSimple->setIsActive('1');
        
        $manager->persist($userSimple);
        
        // Affectation pour un admin
        $affectationUserAdmin = new Affectation();
        $affectationUserAdmin->setRole($roleAdmin);
        $affectationUserAdmin->setUser($userAdmin);
        $affectationUserAdmin->setIsActive('1');
        
        
        // Affectation pour un Professeur
        $affectationUserProf = new Affectation();
        $affectationUserProf->setRole($roleModerator);
        $affectationUserProf->setUser($userModerator);
        $affectationUserProf->setIsActive('1');
        

        
        $populator->addEntity('App\Entity\Promotion', 10,[
             'name' => function() use ($faker) { return $faker->unique()->promotionName(); },
             'is_active' => 1,
        ]);
        $populator->addEntity('App\Entity\Speciality', 3,[
            'name' => function() use ($faker) { return $faker->unique()->specialityName(); },
            'is_active' => 1,
        ]);
        $populator->addEntity('App\Entity\Support', 3,[
            'name' => function() use ($faker) { return $faker->unique()->supportName(); },
            'icon' => function() use ($faker) { return $faker->unique()->supportIcon(); },
            'is_active' => 1,
        ]);
        $populator->addEntity('App\Entity\Locale', 6 ,[
            'name' => function() use ($faker) { return $faker->unique()->localeName(); },
            'is_active' => 1,
            ]);
        $populator->addEntity('App\Entity\Difficulty',3 ,[
            'name' => function() use ($faker) { return $faker->unique()->difficultyName(); },
            'is_active' => 1,
            'level' => function() use ($faker) { return $faker->unique()->numberBetween($min = 1, $max = 3); },
        ]);

        // On peut passer en 3ème paramètre le générateur de notre choix, ici un "userName" cohérent pour Person
        $populator->addEntity('App\Entity\User', 20, array(
            'is_active' => 1,
            'username' => function() use ($faker) { 
                return $faker->userName(); 
            },
            'first_name' => function() use ($faker) { 
                return $faker->firstName(); 
            },
            'last_name' => function() use ($faker) { 
                return $faker->lastName(); 
            },
            'email' => function() use ($faker) { 
                return $faker->email(); 
            },
            'password' => function() use ($faker) { 
                return $faker->password(); 
            },
            'pseudo_github' => function() use ($faker) { 
                return $faker->userName(); 
             },
            'birthday' => function() use ($faker) { 
                return $faker->dateTime(); 
            },
            'zip' => function() use ($faker) { 
                return $faker->numberBetween($min = 1000, $max = 9000); 
            },
        ));

        $populator->addEntity('App\Entity\Tag',10 ,[
            'is_active' => 1,
            'label' => function() use ($faker) { return $faker->unique()->tagName(); },
        ]);

        $populator->addEntity('App\Entity\Bookmark', 50, array(
            'is_active' => 1,
            'banned' => 0,
            'created_at' => function() use ($faker) { 
                return $faker->dateTime(); 
            },
            'title' => function() use ($faker) { 
                return $faker->sentence($nbWords = 6, $variableNbWords = true); 
            },
            'resume' => function() use ($faker) { 
                return $faker->paragraph($nbSentences = 10, $variableNbSentences = true); 
            },
            'url' => function() use ($faker) { 
                return $faker->url(); 
            },
            'image' => function() use ($faker) { 
                return $faker->imageUrl($width = 640, $height = 480); 
            },
            'published_at' => function() use ($faker) { 
                return $faker->dateTimeThisDecade(); 
            },
            'author' => function() use ($faker) { 
                return $faker->Name(); 
            },
        )); 
        

        $populator->addEntity('App\Entity\AnnouncementType',4 ,[
            'is_active' => 1,
            'name' => function() use ($faker) { return $faker->unique()->announcementType(); },
        ]);

        $populator->addEntity('App\Entity\Announcement',40 ,[
            'is_active' => 1,
            'frozen' => 0,
            'closing_at' => function() use ($faker) { 
                return $faker->dateTimeBetween($startDate = 'now', $endDate = '+1 years'); 
            },
            'title' => function() use ($faker) { 
                return $faker->sentence($nbWords = 6, $variableNbWords = true); 
            },
            'body' => function() use ($faker) { 
                return $faker->paragraph($nbSentences = 3, $variableNbSentences = true); 
            },

        ]);

        $populator->addEntity('App\Entity\Comment',50 ,[
            'is_active' => 1,
            'banned' => 0,
            'body' => function() use ($faker) { 
                return $faker->sentence($nbWords = 25, $variableNbWords = true);
            },
        ]);

        $populator->addEntity('App\Entity\WarningBookmark',10 ,[
            'is_active' => 1,
            'message' => function() use ($faker) { 
                return $faker->sentence($nbWords = 25, $variableNbWords = true);
            },
        ]);

        $populator->addEntity('App\Entity\Vote',150 ,[
            'is_active' => 1,
            'value' => function() use ($faker) { 
                return $faker->randomElement($array = array (-1,1));
            },
        ]);


        // On demande à Faker d'éxécuter les ajouts en BDD
        $inserted = $populator->execute();

        /*$promotions = $inserted['App\Entity\Promotion'];    
        $specialities = $inserted['App\Entity\Speciality']; 
        $supports = $inserted['App\Entity\Support']; 
        $languages = $inserted['App\Entity\Locale'];  */
        
        $promotions = $inserted['App\Entity\Promotion'];
        $affectationUserAdmin->setPromotion($promotions[0]);
        $affectationUserProf->setPromotion($promotions[0]);
        $manager->persist($affectationUserAdmin);
        $manager->persist($affectationUserProf);
        
        $users = $inserted['App\Entity\User'];
        foreach($users as $user)
        {
            $affectation = new Affectation();
            $affectation->setRole($roleUser);
            $affectation->setUser($user);
            shuffle($promotions);
            $affectation->setPromotion($promotions[0]);
            $manager->persist($affectation);
        };

        $tags = $inserted['App\Entity\Tag'];
        foreach($inserted['App\Entity\Bookmark'] as $bookmark)
        {
            //Pour chaque bookmark on ajoute de 1 à 6 tags au hasard
            //et on l'ajoute en favori et en certifié par des utilisateur
            shuffle($tags);
            shuffle($users);
            $bookmark->__construct();
            for ( $i=0 ; $i<$faker->numberBetween(1,3) ; $i++){
                $bookmark->addTag($tags[$i]);
            }
            for ( $i=0 ; $i<$faker->numberBetween(1,10) ; $i++){
                
                $bookmark->addFavedBy($users[$i]);
            }
            for ( $i=0 ; $i<$faker->numberBetween(0,3) ; $i++){
                
                $bookmark->addCertifiedBy($users[$i + $faker->numberBetween(5,10)]);
            }
        }

        $announcements = $inserted['App\Entity\Announcement'];
        foreach($inserted['App\Entity\Promotion'] as $promotion)
        {
            //Pour chaque promotion on ajoute de 1 à 3 annonces au hasard
            shuffle($announcements);
            $promotion->__construct();
            for ( $i=0 ; $i<$faker->numberBetween(2,4)  ; $i++){
                $promotion->addAnnounce($announcements[$i]);
            }
            for ( $i=0; $i<3; $i++){
                $link = new PromotionLink();
                $link->setName($faker->unique()->promotionLink());
                $link->setUrl($faker->url());
                $link->setIcon($faker->unique()->linkIcon());
                $faker->unique($reset = true);
                $manager->persist($link);
                $promotion->addLink($link);
                $manager->persist($promotion);
            }
        }



        
        $manager->flush();

    }
}

