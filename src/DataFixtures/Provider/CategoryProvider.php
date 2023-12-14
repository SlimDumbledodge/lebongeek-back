<?php

namespace App\DataFixtures\Provider;

class CategoryProvider
{
    private $categories = [
        ['name' => 'jeux videos', 'image' => 'https://i.postimg.cc/PJ1BkZMt/Jeux-videos.png'],
        ['name' => 'jeu de rôle', 'image' => 'https://i.postimg.cc/xTSw-3x0q/Jeu-de-role.png'],
        ['name' => 'cartes à jouer et à collectionner', 'image' => 'https://i.postimg.cc/7PT4VJ0t/Cartes-jouer-et-collectionner.png'],
        ['name' => 'figurines', 'image' => 'https://i.postimg.cc/cJHy8vdm/Figurines-et-statuettes.png'],
        ['name' => 'comics', 'image' => 'https://i.postimg.cc/1tkLTMwC/Comics.png'],
        ['name' => 'mangas', 'image' => 'https://i.postimg.cc/KYVVkQqG/Mangas.png'],
        ['name' => 'bandes dessinées', 'image' => 'https://i.postimg.cc/C5DVL9wp/Bande-dessin-e.png'],
        ['name' => 'cosplay', 'image' => 'https://i.postimg.cc/hG5F8Kjq/Cosplay.png'],
        ['name' => 'goodies (mugs, objets publicitaires…)', 'image' => 'https://i.postimg.cc/RV7BrrPc/Goodies.png'],
        ['name' => 'dvd/blu-ray', 'image' => 'https://i.postimg.cc/tTZK28Zn/DVD-Bluray.png'],
        ['name' => 'cd/vinyles', 'image' => 'https://i.postimg.cc/t49Hprf8/CD-Vinyles.png'],
        ['name' => 'jeu de rôle gn (larping)', 'image' => 'https://i.postimg.cc/kM8r83Pk/Jeu-de-role-GN-LARPING.png'],
        ['name' => 'jeux de plateau', 'image' => 'https://i.postimg.cc/Z5wXZJ6y/Jeux-de-plateau.png'],
        ['name' => 'jouets', 'image' => 'https://i.postimg.cc/mrhJgKSp/Jouets.png'],
        ['name' => 'livres et manuels', 'image' => 'https://i.postimg.cc/Hs4fZwxz/Livres-et-manuels.png'],
        ['name' => 'maquettes et modélisme', 'image' => 'https://i.postimg.cc/Hn0PNmpn/Maquettes-et-mod-lisme.png'],
        ['name' => 'matériel de streaming et gaming', 'image' => 'https://i.postimg.cc/HxyNQ5RQ/Materiel-de-streaming-et-gaming.png'],
        ['name' => 'objets connectés', 'image' => 'https://i.postimg.cc/x8V4g8VB/Objets-connect-s.png'],
        ['name' => 'gadgets et technologie', 'image' => 'https://i.postimg.cc/L6RW1JT1/Technologie.png'],
        ['name' => 'maison mobilier et déco', 'image' => 'https://i.postimg.cc/bwy4KZSh/Maison-mobilier-et-d-co.png'],
        ['name' => 'retrogaming', 'image' => 'https://i.postimg.cc/02fBd9WH/Retrogaming.png'],
        ['name' => 'mode et accessoires', 'image' => 'https://i.postimg.cc/pVK7rfDv/Mode-et-accessoires.png'],
        ['name' => 'evènements geek', 'image' => 'https://i.postimg.cc/2STN6QNy/Evenements-Geek.png'],
        ['name' => 'créations originales', 'image' => 'https://i.postimg.cc/ncbJwV1F/Cr-ations-originales.png'],
    ];
    
        
    

    public function allCategories()
    {
        $categories = $this->categories;

        return $categories;
    }

}