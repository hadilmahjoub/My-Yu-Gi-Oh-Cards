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
        
        $all_packs = $this->createPacks($manager);
        
        $this->loadsYGOCards($manager, $all_packs);

    }
    
    private function createPacks(ObjectManager $manager) : array {
        $pack_dark = new Pack();
        $pack_dark->setTitle("DARK");
        $manager->persist($pack_dark);
        
        $pack_divine = new Pack();
        $pack_divine->setTitle("DIVINE");
        $manager->persist($pack_divine);
        
        $pack_earth = new Pack();
        $pack_earth->setTitle("EARTH");
        $manager->persist($pack_earth);
        
        $pack_fire = new Pack();
        $pack_fire->setTitle("FIRE");
        $manager->persist($pack_fire);
        
        $pack_light = new Pack();
        $pack_light->setTitle("LIGHT");
        $manager->persist($pack_light);
        
        $pack_water = new Pack();
        $pack_water->setTitle("WATER");
        $manager->persist($pack_water);
        
        $pack_wind = new Pack();
        $pack_wind->setTitle("WIND");
        $manager->persist($pack_wind);
        
        $manager->flush();
        
        return [
            $pack_dark, 
            $pack_divine, 
            $pack_earth, 
            $pack_fire, 
            $pack_light, 
            $pack_water, 
            $pack_wind
        ];
    }
    
    private function getYGOCardsData()
    {
        yield 'DARK' => ['Dark Magician', 'Summoned Skull', 'Kuriboh'];
        yield 'DIVINE' => ['Obelisk the Tormentor', 'Slifer the Sky Dragon'];
        yield 'EARTH' => ['Gaia the Fierce Knight', 'Celtic Guardian'];
        yield 'FIRE' => ['Red-Eyes Black Dragon', 'Blazing Inpachi'];
        yield 'LIGHT' => ['Blue-Eyes White Dragon', 'Mystical Elf'];
        yield 'WATER' => ['Levia-Dragon - Daedalus', 'Abyss Soldier'];
        yield 'WIND' => ['Harpie Lady', 'Elemental Hero Avian'];
    }
    
    function loadsYGOCards(ObjectManager $manager, array $all_packs) {
  
        foreach ($all_packs as $pack) {
            $packTitle = $pack->getTitle();
            
            $cards = $this->getYGOCardsData();
            
            foreach ($cards as $title => $cardsNames) {
                if ($title === $packTitle) {
                    foreach ($cardsNames as $cardName) {
                        $card = new YGOCard();
                        $card->setName($cardName);
                        $card->setPack($pack); // Associate the card with the pack
                        $manager->persist($card);
                    }
                }
            }
        }
        
        $manager->flush();
    }
    
}
