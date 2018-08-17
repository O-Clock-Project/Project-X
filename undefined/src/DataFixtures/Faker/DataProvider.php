<?php 

namespace App\DataFixtures\Faker;

class DataProvider extends \Faker\Provider\Base
{
    protected static $promotions = [
        'OClock',
        'BigBang',
        'Cosmos',
        'Discovery',
        'Explorer',
        'Fusion',
        'Galaxy',
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
        'Replays',
        'Drive',
        'Fiches Récap',
        'Slack',
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
        'Écrit',
    ];

    protected static $linkIcons = [
        'FaSchool',
        'FaGithub',
        'FaGooglePlay',
        'FaGoogleDrive',
        'FaArchive',
        'FaSlackHash',
    ];

    protected static $supportIcons = [
        'FaFilm',
        'FaHeadphones',
        'FaFileAlt',
    ];

    protected static $languages = [
        'Français',
        'Anglais',
        'Espagnol',
        'Allemand',
        'Italien',
        'Portugais',
    ];

    protected static $difficulties = [
        'Commencer',
        'Progresser',
        'Se dépasser',
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

    public static function linkIcon(){
        return static::randomElement(self::$linkIcons);
    }

    public static function supportIcon(){
        return static::randomElement(self::$supportIcons);
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