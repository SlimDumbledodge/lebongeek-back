<?php

namespace App\DataFixtures\Provider;

class CategoryProvider
{
    private $categories = [
        'Jeux video',
        'Figurines',
        'Comics',
        'Mangas',
        'Bandes dessinées',
        'Films/séries',
        'Gadget',
        'Cosplay',
        'Cartes',
        'Livres',
        'Equipement de Gaming',
        'Collection Rare et Vintage',
        'Accessoires et Décoration',
        'Maquettes et modélisme',
        'Éditions Spéciales et Limitées',
        'Art et Illustrations',
        'Musique',
        'Objets connectés',
        'Jouets',
        'Evènements Geek'
    ];

    public function allCategories()
    {
        $categories = $this->categories;

        return $categories;
    }

}