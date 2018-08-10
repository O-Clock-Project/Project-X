<?php

namespace App\DataFixtures;

use App\Entity\Promotion;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Affectation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\DataFixtures\Faker\PromotionProvider;
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

        
        /*$userAdmin = new User();
        $userAdmin->setUsername('admin');
        //$userAdmin->setPassword($this->encoder->encodePassword($userAdmin, 'admin'));
        $userAdmin->setPassword('admin');
        $userAdmin->setEmail('admin@admin.fr');
        //$userAdmin->setBirthday('30/05/1980');
        $userAdmin->setusername('Charly');
        $userAdmin->setfirstName('Joly');
        $userAdmin->setLastName('Charles');
        $userAdmin->setIsActive('1');

        $manager->persist($userAdmin);

        $userModerator = new User();
        $userModerator->setUsername('professeur');
        //$userModerator->setPassword($this->encoder->encodePassword($userModerator, 'prof'));
        $userModerator->setPassword('prof');
        $userModerator->setEmail('prof@prof.fr');
        //$userModerator->setRole($roleModerator);
        //$userModerator->setBirthday('13/05/1985');
        $userModerator->setusername('Soso85');
        $userModerator->setfirstName('Martin');
        $userModerator->setLastName('Sophie');
        $userModerator->setIsActive('1');

        $manager->persist($userModerator);

        $userSimple = new User();
        $userSimple->setUsername('etudiant');
        //$userSimple->setPassword($this->encoder->encodePassword($userSimple, 'etudiant'));
        $userSimple->setPassword('user');
        $userSimple->setEmail('user@user.fr');
        //$userSimple->setRole($roleUser);
        //$userSimple->setBirthday('30/10/1990');
        $userSimple->setusername('Juju');
        $userSimple->setfirstName('Dupont');
        $userSimple->setLastName('Jules');
        $userSimple->setIsActive('1');

        $manager->persist($userSimple);*/

        $AffectationUserAdmin = new Affectation();
        $AffectationUserAdmin->setRole($roleAdmin);
        //$AffectationUserAdmin->setUser($userAdmin);
        $AffectationUserAdmin->setIsActive('1');
        
        $manager->persist($AffectationUserAdmin);

        $AffectationUserProf = new Affectation();
        $AffectationUserProf->setRole($roleModerator);
        //$AffectationUserProf->setUser($userModerator);
        $AffectationUserProf->setIsActive('1');
        
        $manager->persist($AffectationUserProf);

        $AffectationUserSimple = new Affectation();
        $AffectationUserSimple->setRole($roleUser);
        //$AffectationUserSimple->setUser($userSimple);
        $AffectationUserSimple->setIsActive('1');
        
        $manager->persist($AffectationUserSimple);

        //$manager->flush();

        $generator->addProvider(new PromotionProvider($generator));
        
        // On passe le Manager de Doctrine à Faker 
        $populator = new Faker\ORM\Doctrine\Populator($generator, $manager);
        
        $populator->addEntity('App\Entity\Promotion', 10,[
             'name' => function() use ($generator) { return $generator->unique()->promotionName(); },
             'is_active' => 1,
        ]);

        // On peut passer en 3ème paramètre le générateur de notre choix, ici un "name" cohérent pour Person
        $populator->addEntity('App\Entity\user', 20, array(
            'username' => function() use ($generator) { 
                return $generator->name(); 
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
            'avatar' => function() use ($generator) { 
                return $generator->imageUrl(); 
            },
            'birthday' => function() use ($generator) { 
                return $generator->date(); 
            },
            'zip' => function() use ($generator) { 
                return $generator->postcode(); 
            },
        ));
        // On demande à Faker d'éxécuter les ajouts
        $insertedPKs = $populator->execute();
        
        // Faker ajoute les données générées en BDD
        // en retour il fournit les elements inséré + leur id car créé
        $inserted = $populator->execute();

        $promotions = $inserted['App\Entity\Promotion'];    
        // $genres = $inserted['App\Entity\Genre']; 

        //$manager->flush();
        
    }
}