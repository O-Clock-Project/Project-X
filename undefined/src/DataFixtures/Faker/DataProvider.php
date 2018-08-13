<?php 

namespace App\DataFixtures\Faker;

class DataProvider extends \Faker\Provider\Base
{
    protected static $promotions = [
        '0',
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

    protected static $specialies = [
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
        'Débutant',
        'Confirmé',
        'Expert',
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
        return static::randomElement(self::$specialies);
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