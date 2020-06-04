<?php

require 'vendor/autoload.php';
use mofodojodino\ProfanityFilter\Check;

class ProfanityCheck{

    public static function hasProfanity(string $text) : ?bool{
        $checker = new Check();
        return $checker->hasProfanity($text);
    }

}