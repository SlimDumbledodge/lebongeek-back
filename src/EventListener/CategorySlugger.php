<?php

namespace App\EventListener;

use App\Entity\Category;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategorySlugger
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Category $category, LifecycleEventArgs $event)
    {
        $this->slugifyName($category);
    }

    public function preUpdate(Category $category, LifecycleEventArgs $event)
    {
        $this->slugifyName($category);
    }

    private function slugifyName(Category $category)
    {
        // je set le slug en sluggifiant le titre via le service slugger
        $category->setSlug($this->slugger->slug($category->getName()));
    }
}
