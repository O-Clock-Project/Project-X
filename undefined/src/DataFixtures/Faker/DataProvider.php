<?php 

namespace App\DataFixtures\Faker;

class DataProvider extends \Faker\Provider\Base
{
    protected static $promotions = [
        'OClock',
        'BigBang',
        'Cosmo',
        'Discovery',
        'Explorer',
        'Fusion',
        'Galaxie',
        'Hyperspace',
        'Invaders',
        'Journey',
        'Krypton',
    ];

    protected static $announcementTypes = [
        'Annonce',
        'Sondage',
        'Kiem Tao',
        'Blague',
    ];

    protected static $promotionLinks = [
        'Planning',
        'Cockpit',
        'Github',
    ];

    protected static $specialities = [
        'Symfony',
        'React',
        'WordPress',
    ];

    protected static $supports = [
        'Audio',
        'Video',
        'Écrite',
    ];

    protected static $languages = [
        'Français',
        'Anglais',
        'Espagnole',
        'Allemand',
        'Italien',
        'Portugais',
    ];

    protected static $difficulties = [
        'Apprendre',
        'S\améliorer',
        'Se perfectionner',
    ];

    protected static $tags = [
        'Symfony',
        'JavaScript',
        'Php',
        'React',
        'Boostrap',
        'Bulma',
        'WordPress',
        'HTML',
        'CSS',
        'JQuery',
    ];

    public static function promotionName(){
        return static::randomElement(self::$promotions);
    }

    public static function specialityName(){
        return static::randomElement(self::$specialities);
    }

    public static function announcementType(){
        return static::randomElement(self::$announcementTypes);
    }

    public static function promotionLink(){
        return static::randomElement(self::$promotionLinks);
    }

    public static function supportName(){
        return static::randomElement(self::$supports);
    }

    public static function localeName(){
        return static::randomElement(self::$languages);
    }

    public static function difficultyName(){
        return static::randomElement(self::$difficulties);
    }

    public static function tagName() {
        return static::randomElement(self::$tags);
    }
}