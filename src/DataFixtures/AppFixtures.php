<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\KernelInterface;
use App\Entity\Showcase;
use App\Entity\YGOCard;
use App\Entity\Pack;
use App\Entity\User;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\File\File;


class AppFixtures extends Fixture implements DependentFixtureInterface
{
    private KernelInterface $kernel;
    
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
     
    public function load(ObjectManager $manager): void
    {        
        // Create a Pack for each user and fill it with random YGO cards
        /*
        foreach ($users as $user) {
            $pack = $this->createPackForUser($manager, $user);
            $this->fillPackWithRandomYGOCard($manager, $pack);
            
            // Create two showcases for the user and fill them with cards from the user's pack
            $this->loadShowcasesForUser($manager, $user, $pack);
        }
        */
        
        // Récupère les utilisateurs depuis la base de données pour associer les Packs et Showcases
        $userEmails = ['olivier@localhost', 'slash@localhost', 'admin@localhost'];
        
        $allCardsData = $this->ygoCardsData();
        
        $offset = 0;
        
        foreach ($userEmails as $email) {
            $user = $manager->getRepository(User::class)->findOneBy(['email' => $email]);
            
            dump($user);
            
            
            if ($user) {
                
                $cardsToAddToPack = array_slice($allCardsData, $offset, 10);
                $offset += 10;
                
                $pack = $this->loadPackForUser($manager, $user, $cardsToAddToPack);
                $this->loadShowcasesForUser($manager, $user, $pack);
                
            }
        }
        
        $manager->flush();
    }
    
    
    
    /**
     * Crée un objet YGOCard à partir des données et l'ajoute à un Pack.
     */
    private function createYGOCard(array $cardData, Pack $pack): YGOCard
    {
        $card = new YGOCard();
        $card->setName($cardData['name']);
        $card->setDescription($cardData['description']);
        $card->setAttribute($cardData['attribute']);
        $card->setType($cardData['type']);
        $card->setRace($cardData['race']);
        $card->setLevel($cardData['level']);
        $card->setPack($pack);
        
        
        // Télécharger l'image et la sauvegarder localement
        $client = HttpClient::create();
        $response = $client->request('GET', $cardData['img']);
        $imagePath = __DIR__ . '/../../public/uploads/images/ygocards/' . basename($cardData['img']);
        file_put_contents($imagePath, $response->getContent());
        
        $card->setImageFile(new File($imagePath)); // Associer le fichier image
        $card->setImageName(basename($cardData['img']));
        
        return $card;
    }
    
    
    /**
     * Créer et Remplit un Pack avec des cartes YGO pour un User.
     */
    private function loadPackForUser(ObjectManager $manager, User $user, Array $cardsToAdd): Pack
    {
        // Crée un Pack pour l'utilisateur avec un titre spécifique
        $pack = new Pack();
        $pack->setTitle("YGO Pack of " . $user->getEmail());
        $pack->setUser($user); // Associe le Pack à l'utilisateur
        
        foreach ($cardsToAdd as $cardData) {
            $card = $this->createYGOCard($cardData, $pack);
            $manager->persist($card);
        }
        
        $manager->persist($pack);
        $manager->flush();
        
        return $pack;
    }
    
    
    /**
     * Crée et Remplir deux Showcases pour un utilisateur donné et y ajoute des cartes depuis son Pack.
     */
    private function loadShowcasesForUser(ObjectManager $manager, User $user, Pack $pack): void
    {
        
        $allCards = $manager->getRepository(YGOCard::class)->findBy(['pack' => $pack]);
        
        /*
        if (empty($allCards)) {
            throw new \Exception("Le pack de l'utilisateur {$user->getEmail()} ne contient aucune carte.");
        }*/
        
        for ($i = 1; $i <= 2; $i++) {
            $showcase = new Showcase();
            $showcase->setDescription("Showcase $i for user {$user->getEmail()}");
            $showcase->setCreator($user);
            $showcase->setPublished(true);
            
            shuffle($allCards);
            $cardsToAdd = array_slice($allCards, 0, 3);
            
            foreach ($cardsToAdd as $card) {
                $showcase->addYgoCard($card);
            }
            
            $manager->persist($showcase);
            
            dump($showcase);
        }
    }
    
    private function ygoCardsData(): array
    {
        $ygoCardsData = [
            [
                'name' => 'Mist Valley Soldier',
                'description' => 'While you control this face-up card, any opponent\'s monster that battles this card, but is not destroyed by the battle, returns to its owner\'s hand at the end of the Damage Step.',
                'attribute' => 'WIND',
                'type' => 'Tuner Monster',
                'race' => 'Winged Beast',
                'level' => 4,
                'img' => 'https://images.ygoprodeck.com/images/cards/22837504.jpg',
            ],
            [
                'name' => 'Constellar Virgo',
                'description' => 'When this card is Normal Summoned: You can Special Summon 1 Level 5 "Constellar" monster from your hand in face-up Defense Position.',
                'attribute' => 'LIGHT',
                'type' => 'Effect Monster',
                'race' => 'Fairy',
                'level' => 5,
                'img' => 'https://images.ygoprodeck.com/images/cards/40143123.jpg',
            ],
            [
                'name' => 'Yosenju Izna',
                'description' => 'Once per turn, during the End Phase, if this card was Normal Summoned this turn: Return it to the hand. You can only use each of the following effects of "Yosenju Izna" once per turn.\n● You can discard this card; this turn, your opponent cannot activate cards or effects when a "Yosenju" monster(s) is Normal or Special Summoned.\n● If you control another "Yosenju" monster: You can draw 1 card.',
                'attribute' => 'WIND',
                'type' => 'Effect Monster',
                'race' => 'Beast-Warrior',
                'level' => 4,
                'img' => 'https://images.ygoprodeck.com/images/cards/85970321.jpg',
            ],
            [
                'name' => 'X-Saber Palomuro',
                'description' => 'When another "Saber" monster you control is destroyed by battle and sent to the Graveyard, you can pay 500 Life Points to Special Summon this card from the Graveyard.',
                'attribute' => 'EARTH',
                'type' => 'Tuner Monster',
                'race' => 'Reptile',
                'level' => 1,
                'img' => 'https://images.ygoprodeck.com/images/cards/96099959.jpg',
            ],
            [
                'name' => 'Performapal Rain Goat',
                'description' => 'When a card or effect is activated that would inflict damage to you (Quick Effect): You can discard this card; make that effect damage to you 0. During the Main Phase (Quick Effect): You can discard this card, then target 1 "Performapal" or "Odd-Eyes" card you control; this turn, it cannot be destroyed by battle or card effects.',
                'attribute' => 'WATER',
                'type' => 'Effect Monster',
                'race' => 'Beast',
                'level' => 1,
                'img' => 'https://images.ygoprodeck.com/images/cards/16617334.jpg',
            ],
            [
                'name' => 'Gouki Moonsault',
                'description' => 'You can reveal this card in your hand, then target 1 "Gouki" monster you control, except "Gouki Moonsault"; Special Summon this card from your hand, and if you do, return that monster to the hand. You can target 1 "Gouki" Link Monster in your GY; return it to the Extra Deck, then you can add 1 "Gouki" monster from your GY to your hand. You can only use each effect of "Gouki Moonsault" once per turn.',
                'attribute' => 'EARTH',
                'type' => 'Effect Monster',
                'race' => 'Warrior',
                'level' => 6,
                'img' => 'https://images.ygoprodeck.com/images/cards/20191720.jpg',
            ],
            [
                'name' => 'Star Seraph Sword',
                'description' => 'Once per turn: You can send 1 "Star Seraph" monster from your hand to the Graveyard; this card gains ATK equal to the original ATK of the sent monster, until the End Phase.',
                'attribute' => 'LIGHT',
                'type' => 'Effect Monster',
                'race' => 'Fairy',
                'level' => 4,
                'img' => 'https://images.ygoprodeck.com/images/cards/70668285.jpg',
            ],
            [
                'name' => 'Lyrilusc - Promenade Thrush',
                'description' => '2+ Level 1 monsters\nGains 500 ATK for each material attached to it. Once per turn: You can detach 1 material from this card, then target 1 Spell/Trap your opponent controls; shuffle it into the Deck. At the start of the Damage Step, if another monster you control battles: You can detach any number of materials from this card; that monster you control gains 300 ATK for each material detached, until the end of this turn.',
                'attribute' => 'WIND',
                'type' => 'XYZ Monster',
                'race' => 'Winged Beast',
                'level' => 1,
                'img' => 'https://images.ygoprodeck.com/images/cards/19369609.jpg',
            ],
            [
                'name' => 'Prompthorn',
                'description' => 'You can Tribute 1 Level 4 or lower Cyberse monster; Special Summon any number of Cyberse Normal Monsters from your Deck and/or GY whose total Levels equal the Tributed monster\'s Level on the field, but banish them during the End Phase. You can only use this effect of "Prompthorn" once per turn.',
                'attribute' => 'DARK',
                'type' => 'Effect Monster',
                'race' => 'Cyberse',
                'level' => 1,
                'img' => 'https://images.ygoprodeck.com/images/cards/50548657.jpg',
            ],
            [
                'name' => 'Gladiator Beast Tamer Editor',
                'description' => '2 Level 5 or higher "Gladiator Beast" monsters\nMust first be Special Summoned (from your Extra Deck) by shuffling the above cards you control into the Deck. (You do not use "Polymerization".) Cannot be used as Fusion Material. Once per turn: You can Special Summon 1 "Gladiator Beast" Fusion Monster from your Extra Deck, except "Gladiator Beast Tamer Editor", ignoring its Summoning conditions. At the end of the Battle Phase, if your "Gladiator Beast" monster battled: You can shuffle that monster into the Deck or Extra Deck; Special Summon 1 "Gladiator Beast" monster from your Deck.',
                'attribute' => 'DARK',
                'type' => 'Fusion Monster',
                'race' => 'Beast-Warrior',
                'level' => 8,
                'img' => 'https://images.ygoprodeck.com/images/cards/30864377.jpg',
            ],
            [
                'name' => 'SPYRAL Tough',
                'description' => 'This card\'s name becomes "SPYRAL Super Agent" while it is on the field or in the GY. Once per turn: You can declare 1 type of card (Monster, Spell, or Trap) and target 1 card your opponent controls; reveal the top card of your opponent\'s Deck, and if you do, destroy the targeted card if the revealed card is the declared type.',
                'attribute' => 'WIND',
                'type' => 'Effect Monster',
                'race' => 'Warrior',
                'level' => 4,
                'img' => 'https://images.ygoprodeck.com/images/cards/20584712.jpg',
            ],
            [
                'name' => 'Elemental Grace Doriado',
                'description' => 'Cannot be Normal Summoned/Set. Must be Special Summoned (from your hand) by having 6 or more monsters with different Attributes in the GYs. Gains ATK/DEF equal to the number of different Attributes in the GYs x 500. When your opponent would Special Summon a monster(s) (Quick Effect): You can banish 3 monsters from your GY; negate the Summon, and if you do, destroy that monster(s).',
                'attribute' => 'LIGHT',
                'type' => 'Effect Monster',
                'race' => 'Spellcaster',
                'level' => 9,
                'img' => 'https://images.ygoprodeck.com/images/cards/32965616.jpg',
            ],
            [
                'name' => 'Multiple Piece Golem',
                'description' => '"Big Piece Golem" + "Medium Piece Golem"\nAt the end of the Battle Phase, if this card attacked or was attacked, you can return it to the Extra Deck. Then, if all of the Fusion Material Monsters that were used for the Fusion Summon of this card are in your Graveyard, you can Special Summon them.',
                'attribute' => 'EARTH',
                'type' => 'Fusion Monster',
                'race' => 'Rock',
                'level' => 7,
                'img' => 'https://images.ygoprodeck.com/images/cards/71628381.jpg',
            ],
            [
                'name' => 'Luster Dragon #2',
                'description' => 'This dragon feeds on emerald. Enchanted by this monster even when attacked, few people live to tell of its beauty.',
                'attribute' => 'WIND',
                'type' => 'Normal Monster',
                'race' => 'Dragon',
                'level' => 6,
                'img' => 'https://images.ygoprodeck.com/images/cards/17658803.jpg',
            ],
            [
                'name' => 'Gellenduo',
                'description' => 'Cannot be destroyed by battle. If you take any damage: Destroy this face-up card. This card can be treated as 2 Tributes for the Tribute Summon of a LIGHT Fairy monster.',
                'attribute' => 'LIGHT',
                'type' => 'Effect Monster',
                'race' => 'Fairy',
                'level' => 4,
                'img' => 'https://images.ygoprodeck.com/images/cards/11662742.jpg',
            ],
            [
                'name' => 'Hammer Shark',
                'description' => 'Once per turn: You can reduce the Level of this card by 1, and if you do, Special Summon 1 Level 3 or lower WATER monster from your hand.',
                'attribute' => 'WATER',
                'type' => 'Effect Monster',
                'race' => 'Fish',
                'level' => 4,
                'img' => 'https://images.ygoprodeck.com/images/cards/17201174.jpg',
            ],
            [
                'name' => 'Relinquished Anima',
                'description' => '1 Level 1 monster, except a Token. You can target 1 face-up monster this card points to; equip that face-up monster to this card (max. 1). This card gains ATK equal to that equipped monster\'s.',
                'attribute' => 'DARK',
                'type' => 'Link Monster',
                'race' => 'Spellcaster',
                'level' => 0,
                'img' => 'https://images.ygoprodeck.com/images/cards/94259633.jpg',
            ],
            [
                'name' => 'Inzektor Giga-Cricket',
                'description' => 'If this card is in your Graveyard: You can banish 1 Insect-Type monster from your Graveyard to target 1 face-up "Inzektor" monster you control; equip this card from the Graveyard to that target. While equipped, that monster\'s original ATK becomes 2000, and if it attacks a Defense Position monster, inflict piercing Battle Damage to your opponent.',
                'attribute' => 'DARK',
                'type' => 'Effect Monster',
                'race' => 'Insect',
                'level' => 5,
                'img' => 'https://images.ygoprodeck.com/images/cards/65844845.jpg',
            ],
            [
                'name' => 'Dual Avatar - Empowered Mitsu-Jaku',
                'description' => '"Dual Avatar Feet - Kokoku" + 2 "Dual Avatar" monsters. The first time each "Dual Avatar" Fusion Monster you control would be destroyed by battle each turn, it is not destroyed. Once per turn, during your Main Phase: You can return all Spells and Traps your opponent controls to the hand. When a monster effect is activated on your opponent\'s field while you control 2 or more Fusion Monsters (Quick Effect): You can destroy that monster.',
                'attribute' => 'LIGHT',
                'type' => 'Fusion Monster',
                'race' => 'Warrior',
                'level' => 7,
                'img' => 'https://images.ygoprodeck.com/images/cards/284224.jpg',
            ],
            [
                'name' => 'Gladiator Beast Octavius',
                'description' => 'When this card is Special Summoned by the effect of a "Gladiator Beast" monster: Target 1 face-down Spell or Trap Card in the Spell & Trap Card Zone; destroy that target. At the end of your Battle Phase, if this card attacked or was attacked: Shuffle this card into the Deck or discard 1 card.',
                'attribute' => 'LIGHT',
                'type' => 'Effect Monster',
                'race' => 'Winged Beast',
                'level' => 7,
                'img' => 'https://images.ygoprodeck.com/images/cards/29590752.jpg',
            ],
            [
                'name' => 'Overlay Sentinel',
                'description' => 'Cannot be Special Summoned. When this card is Normal Summoned: Change this card to Defense Position. If you control a face-up Xyz Monster that has Xyz Material: You can banish this card from your Graveyard, then target 1 monster your opponent controls; it loses 500 ATK for each Xyz Material attached to a monster you control.',
                'attribute' => 'LIGHT',
                'type' => 'Effect Monster',
                'race' => 'Warrior',
                'level' => 4,
                'img' => 'https://images.ygoprodeck.com/images/cards/39229392.jpg',
            ],
            [
                'name' => 'Chemicritter Carbo Crab',
                'description' => 'This card is treated as a Normal Monster while face-up on the field or in the Graveyard. While this card is a Normal Monster on the field, you can Normal Summon it to have it become an Effect Monster with this effect: During your Main Phase: You can send 1 Gemini monster from your Deck to the Graveyard, then add 1 Gemini monster from your Deck to your hand. You can only use this effect of "Chemicritter Carbo Crab" once per turn.',
                'attribute' => 'FIRE',
                'type' => 'Gemini Monster',
                'race' => 'Aqua',
                'level' => 2,
                'img' => 'https://images.ygoprodeck.com/images/cards/81599449.jpg',
            ],
            [
                'name' => 'Battlewasp - Ballista the Armageddon',
                'description' => '1 Tuner + 1+ non-Tuner monsters. If this card is Special Summoned: You can banish all Insect monsters from your GY; all monsters your opponent currently controls lose 500 ATK/DEF for each of your banished Insect monsters. If this card attacks a Defense Position monster, inflict piercing battle damage. If this Synchro Summoned card in its owner\'s control is destroyed by an opponent\'s card: You can Special Summon 3 of your banished Level 11 or lower Insect monsters.',
                'attribute' => 'WIND',
                'type' => 'Synchro Monster',
                'race' => 'Insect',
                'level' => 12,
                'img' => 'https://images.ygoprodeck.com/images/cards/26443791.jpg',
            ],
            [
                'name' => 'Final Psychic Ogre',
                'description' => 'If this card destroys an opponent\'s monster by battle, you can pay 800 Life Points to select 1 Psychic-Type monster in your Graveyard, and add it to your hand.',
                'attribute' => 'EARTH',
                'type' => 'Effect Monster',
                'race' => 'Psychic',
                'level' => 5,
                'img' => 'https://images.ygoprodeck.com/images/cards/87622767.jpg',
            ],
            [
                'name' => 'Prominence, Molten Swordsman',
                'description' => 'Once per turn, during either player\'s turn: You can banish 1 "Laval" monster from your Graveyard; this card gains 300 ATK until the End Phase.',
                'attribute' => 'FIRE',
                'type' => 'Effect Monster',
                'race' => 'Beast-Warrior',
                'level' => 4,
                'img' => 'https://images.ygoprodeck.com/images/cards/89770167.jpg',
            ],
            [
                'name' => 'Chronomaly Mayan Machine',
                'description' => 'This card can be treated as 2 Tributes for the Tribute Summon of a Machine-Type monster.',
                'attribute' => 'EARTH',
                'type' => 'Effect Monster',
                'race' => 'Machine',
                'level' => 3,
                'img' => 'https://images.ygoprodeck.com/images/cards/25163248.jpg',
            ],
            [
                'name' => 'The Ascended of Thunder',
                'description' => 'You can Special Summon this card (from your hand) by paying 3000 LP. If this card Summoned this way in its owner\'s control is destroyed by an opponent\'s card (by battle or card effect): Gain 5000 LP.',
                'attribute' => 'LIGHT',
                'type' => 'Effect Monster',
                'race' => 'Thunder',
                'level' => 7,
                'img' => 'https://images.ygoprodeck.com/images/cards/70493141.jpg',
            ],
            [
                'name' => 'Ghostrick Fairy',
                'description' => 'Cannot be Normal Summoned, unless you control a "Ghostrick" monster. Once per turn: You can change this card to face-down Defense Position. When this card is flipped face-up: You can target 1 "Ghostrick" card in your GY; Set that card (but banish it when it leaves the field), then, you can change face-up monsters your opponent controls to face-down Defense Position, up to the number of Set cards you control.',
                'attribute' => 'DARK',
                'type' => 'Effect Monster',
                'race' => 'Spellcaster',
                'level' => 2,
                'img' => 'https://images.ygoprodeck.com/images/cards/36239585.jpg',
            ],
            [
                'name' => 'Inaba White Rabbit',
                'description' => 'This card cannot be Special Summoned. This card returns to the owner\'s hand during the End Phase of the turn that this card is Normal Summoned or flipped face-up. This monster attacks your opponent\'s Life Points directly.',
                'attribute' => 'EARTH',
                'type' => 'Spirit Monster',
                'race' => 'Beast',
                'level' => 3,
                'img' => 'https://images.ygoprodeck.com/images/cards/77084837.jpg',
            ],
            [
                'name' => 'Cardcar D',
                'description' => 'Cannot be Special Summoned. During your Main Phase 1, if this card was Normal Summoned this turn: You can Tribute this card; draw 2 cards, then it becomes the End Phase. You cannot Special Summon during the turn you activate this effect.',
                'attribute' => 'EARTH',
                'type' => 'Effect Monster',
                'race' => 'Machine',
                'level' => 2,
                'img' => 'https://images.ygoprodeck.com/images/cards/45812361.jpg',
            ],
            
        ];
        
        return $ygoCardsData;
    }
    
    
    
    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
