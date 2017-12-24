<?php

namespace Benjiboi6578\Bosses\task;

use Benjiboi6578\Bosses\Main;
use pocketmine\item\Item;
use pocketmine\plugin\Plugin;
use pocketmine\tile\Tile;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\scheduler\PluginTask;
use pocketmine\tile\Chest;

class GiveHealthTask extends PluginTask {

    public $owner;

    public $entity;

    public function __construct(Main $owner, Entity $entity){
        $this->owner = $owner;
        $this->entity = $entity;
    }

    public function onRun($currentTick) {
        $this->entity->setMaxHealth(1000);
        $this->entity->setHealth(1000);
    }
}