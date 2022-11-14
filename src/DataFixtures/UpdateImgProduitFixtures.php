<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use App\Data\ListeProduits;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class UpdateImgProduitFixtures extends Fixture implements FixtureInterface, ContainerAwareInterface
{
    private $container;
    public function setContainer(?ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    public function load(ObjectManager $manager): void
    {
        $em = $this->container->get(('doctrine.orm.entity_manager'));
        $repProduits = $em->getRepository(Produit::class);
        $listeProduits=$repProduits->findAll();

        foreach($listeProduits as $oneProduit) {
            switch ($oneProduit->getNom()) {
                case 'imprimantes':
                    $oneProduit->setLienImage("imprimante.jpg");
                    break;
                case 'cartouches encre':
                    $oneProduit->setLienImage("cartouches.jpg");
                    break;
                case 'ordinateurs':
                    $oneProduit->setLienImage("ordinateur.jpg");
                    break;
                case 'ecrans':
                    $oneProduit->setLienImage("ecran.jpg");
                    break;
                case 'claviers':
                    $oneProduit->setLienImage('clavier.jpg');
                    break;
                case 'souris':
                    $oneProduit->setLienImage('souris.jpg');
                    break;
            }
            $manager->persist($oneProduit);
        }

        $manager->flush();
    }
}
