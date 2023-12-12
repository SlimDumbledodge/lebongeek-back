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
        'équipement gaming',
        'collection rare et vintage',
        'accessoires et décoration',
        'maquettes et modélisme',
        'éditions spéciales et limitées',
        'art et illustrations',
        'musique',
        'objets connectés',
        'jouets',
        'évènements geek'
    ];

    public function allCategories()
    {
        $categories = $this->categories;

        return $categories;
    }

}