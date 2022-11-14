<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use App\Entity\Reference;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class JoinReferenceFixtures extends Fixture implements FixtureInterface, ContainerAwareInterface
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

            $reference = new Reference;

            $reference->setNumero(rand());

            $oneProduit->setReference($reference);

            $manager->persist($oneProduit);
            // Pour $reference comme cascade est à persist dans Produit sur $reference c'est automatiquement fait.
        }

        $manager->flush();
    }
}
