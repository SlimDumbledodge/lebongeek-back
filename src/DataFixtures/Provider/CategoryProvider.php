<?php

namespace App\DataFixtures\Provider;

class CategoryProvider
{
    private $categories = [
        'Jeux-video',
        'Figurines',
        'Comics',
        'Mangas',
        'Bandes-dessinées',
        'Films/séries',
        'Gadget',
        'Cosplay',
        'Cartes',
        'Livres',
        'Equipement-Gaming',
        'Collection-Rare/Vintage',
        'Accessoires-et-Décoration',
        'Maquettes-et-modélisme',
        'Éditions-Spéciales/Limitées',
        'Art/Illustrations',
        'Musique',
        'Objets-connectés',
        'Jouets',
        'Evènements-Geek'
    ];

    public function allCategories()
    {
        $categories = $this->categories;

        return $categories;
    }

}