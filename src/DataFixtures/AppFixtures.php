<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\YGOCard;
use App\Entity\Pack;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        
        $this->loadsYGOCards($manager);

    }
    
    function loadsYGOCards(ObjectManager $manager) {
        $pack1 = new Pack();
        $manager->persist($pack1);
        
        $pack2 = new Pack();
        $manager->persist($pack2);
        
        $pack3 = new Pack();
        $manager->persist($pack3);
        
        $all_pack = [
          $pack1, $pack2, $pack3 
        ];
        
        foreach ($this->getYGOCardsData() as [$name]) {
            $ygo_card = new YGOCard();
            
            $ygo_card->setName($name);
            $ygo_card->setPack($all_pack[rand(min: 0, max: 2)]);
            
            $manager->persist($ygo_card);
        }
        $manager->flush();
    }
    
    private function getYGOCardsData()
    {
        // todo = [title, completed];
        for ($i = 1; $i < 11; $i++) {
            yield ["YGOCard $i"];
        }
        
    }
}
