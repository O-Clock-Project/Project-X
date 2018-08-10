<?php 

namespace App\DataFixtures\Faker;

class PromotionProvider extends \Faker\Provider\Base
{
    protected static $promotions = [
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

    public static function promotionName(){
        return static::randomElement(self::$promotions);
    }
}