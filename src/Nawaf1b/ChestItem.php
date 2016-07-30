<?php

namespace Nawaf1b;
use pocketmine\item\Item;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\level\sound\PopSound;
class ChestItem extends PluginBase implements Listener { 
    
    /** @var int $chestTouchers */
    public $chestTouchers = 0;
    /** @var ChestItem */
    private static $get = null;

    public function onTouchChest(PlayerInteractEvent $ev){
        if($ev->getBlock()->getId() == 54){
            $tile = $ev->getPlayer()->getLevel()->getTile(new Vector3($ev->getBlock()->x,$ev->getBlock()->y,$ev->getBlock()->z));
            $player = $ev->getPlayer();
            foreach ($tile->getInventory()->getContents() as $chest){
                $enchants = $chest->getEnchantment();
                $chest->addEnchantment($enchants);
                $player->getInventory()->addItem(Item::get($chest->getId()));
            }
            $player->getLevel()->setBlock(new Vector3($ev->getBlock()->x,$ev->getBlock()->y,$ev->getBlock()->z), \pocketmine\block\Block::get(0));
            $player->getLevel()->addSound(new PopSound(new Vector3($ev->getBlock()->x,$ev->getBlock()->y,$ev->getBlock()->z)));
            $player->sendPopup("Players Touch Chest : ".$this->getTouchersChest());
            ++$this->chestTouchers;
        }
    }
    /**
     * @return void
     */
    public function resetTouchers(){
        $this->chestTouchers <= 0 : $this->chestTouchers -= $this->chestTouchers;
    }
    /**
     * @return bool
     */
    public function setChestTouchers($int){
        if(!is_int($int)) return false;
        $this->chestTouchers = $int;
        return true;
    }
    /**
     * @return int
     */ 
    public function getChestTouchers(){
        return $this->chestTouchers;
    }
    public function onLoad(){
        while(!self::$get instanceof $this){
           self::get = $this; 
        }
    }
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("Author ChestItem Plugin : Nawaf_Craft1b");
    }
    /**
     * @return ChestItem
     */ 
    public static function getInstance(){
       return self::$get;
    }
}
