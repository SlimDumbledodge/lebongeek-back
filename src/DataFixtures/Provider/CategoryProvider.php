<?php

namespace App\DataFixtures\Provider;

class CategoryProvider
{
    private $categories = [
        'jeux video',
        'figurines',
        'comics',
        'mangas',
        'bandes dessinées',
        'films et séries',
        'gadget',
        'cosplay',
        'cartes',
        'livres',
        'équipement Gaming',
        'collection Rare et Vintage',
        'accessoires et Décoration',
        'maquettes et modélisme',
        'éditions Spéciales et Limitées',
        'art et Illustrations',
        'musique',
        'objets connectés',
        'jouets',
        'évènements Geek'
    ];

    public function allCategories()
    {
        $categories = $this->categories;

        return $categories;
    }

}