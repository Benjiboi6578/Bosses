<?php

namespace Benjiboi6578\Bosses\entity;

use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;

class Spider extends Monster{
    const NETWORK_ID = 35;

    public $width = 0.72;
    public $height = 1.8;

    protected $speed = 1.1;

    public function initEntity(){
        if(isset($this->nametag->Health)){
            $this->setHealth((int) $this->namedtag["Health"]);
        } else {
            $this->setHealth($this->getMaxHealth());
        }
        $this->setMinDamage([0, 3, 4, 6]);
        $this->setMaxDamage([0, 3, 4, 6]);
        parent::initEntity();
        $this->created = true;
    }

    public function getName() : string {
        return "Spider";
    }

    public function attackEntity(Entity $player){
        if($this->attackDelay > 10 && $this->distanceSquared($player) < 2){
            $this->attackDelay = 0;
            $ev = new EntityDamagebyEntityEvent($this, $player, EntityDamageEvent::CAUSE_ENTITY_ATTACK, $this->getDamage());
            $player->attack($ev);
        }
    }

    public function getDrops() : array {
        $drops = [];
        if($this->lastDamageCause instanceof EntityDamageByEntityEvent){
            switch (mt_rand(0, 2)){
                case 0:
                    $drops[] = Item::get(Item::FEATHER, 0, 1);
                    break;
            }
        }
        return $drops;
    }
}
