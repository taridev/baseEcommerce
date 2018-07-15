<?php

namespace Grocery\ShowcaseBundle\Repository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends \Doctrine\ORM\EntityRepository
{
    public function getCategories()
    {
        //On crée une requête du type SELECT DISTINCT category FROM article
        $categoriesObjects = $this->createQueryBuilder('a')
            ->select('a.category') // SELECT category FROM article
            ->distinct(true)  // DISTINCT
            ->getQuery()
            ->getResult();

        $categories = array();

        // On boucle sur le résultat de la requête pour en extraire un tableau de string
        // contenant l'intitulé des catégories
        foreach ($categoriesObjects as $categoryObject) {
            $categories [] = $categoryObject['category'];
        }

        return $categories;
    }
}