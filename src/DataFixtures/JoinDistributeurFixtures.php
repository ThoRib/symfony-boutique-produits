<?php

namespace App\DataFixtures;

use App\Entity\Distributeur;
use App\Entity\Produit;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class JoinDistributeurFixtures extends Fixture implements FixtureInterface, ContainerAwareInterface
{
    private $container;
    public function setContainer(?ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    public function load(ObjectManager $manager): void
    {
        $em = $this->container->get(('doctrine.orm.entity_manager'));
        $repProduit = $em->getRepository(Produit::class);
        
        // Création des distributeurs

        $fnac = new Distributeur;
        $fnac->setNom('Fnac');

        $grosBill = new Distributeur;
        $grosBill->setNom('GrosBill');

        $darty = new Distributeur;
        $darty->setNom('Darty');

        $boulanger = new Distributeur;
        $boulanger->setNom('Boulanger');

        $cDiscount = new Distributeur;
        $cDiscount->setNom('CDiscount');

        $amazon = new Distributeur;
        $amazon->setNom('Amazon');

        // Jointures avec les produits

        $souris = $repProduit->findOneBy(["nom"=>"souris"]);
        $souris->addDistributeur($fnac);
        $souris->addDistributeur($grosBill);
        $manager->persist($souris);

        $imprimantes = $repProduit->findOneBy(["nom"=>"imprimantes"]);
        $imprimantes->addDistributeur($darty);
        $imprimantes->addDistributeur($boulanger);
        $manager->persist($imprimantes);

        $cartouches = $repProduit->findOneBy(["nom"=>"cartouches encre"]);
        $cartouches->addDistributeur($cDiscount);
        $cartouches->addDistributeur($amazon);
        $manager->persist($cartouches);

        $ordinateurs = $repProduit->findOneBy(["nom"=>"ordinateurs"]);
        $ordinateurs->addDistributeur($cDiscount);
        $ordinateurs->addDistributeur($boulanger);
        $ordinateurs->addDistributeur($grosBill);
        $manager->persist($ordinateurs);

        $claviers = $repProduit->findOneBy(["nom"=>"claviers"]);
        $claviers->addDistributeur($cDiscount);
        $claviers->addDistributeur($darty);
        $claviers->addDistributeur($fnac);
        $manager->persist($claviers);

        $ecrans = $repProduit->findOneBy(["nom"=>"ecrans"]);
        $ecrans->addDistributeur($cDiscount);
        $ecrans->addDistributeur($amazon);
        $manager->persist($ecrans);

        // Pour $distributeur comme cascade est à persist dans Produit sur $distributeur c'est automatiquement fait.
       
        $manager->flush();
    }
}
