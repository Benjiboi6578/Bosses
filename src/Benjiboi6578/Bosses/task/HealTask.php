<?php

namespace Benjiboi6578\Bosses\task;

use Benjiboi6578\Bosses\Main;
use pocketmine\plugin\Plugin;
use pocketmine\Server;
use pocketmine\item\Item;
use pocketmine\tile\Tile;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\scheduler\PluginTask;
use pocketmine\tile\chest;
use pocketmine\utils\TextFormat;

class HealTask extends PluginTask {

    public $owner;

    public $entity;

    public function  __construct(Main $owner, Entity $entity){
        $this->owner = $owner;
        $this->entity = $entity;
    }

    public function onRun($currentTick){
        if(!$this->entity->isClosed() && $this->entity->isAlive() && $this->entity->getHealth() < $this->entity->getMaxHealth()){
            $this->entity->setHealth($this->entity->getHealth() + mt_rand(5,15));
            $this->entity->setNameTag("MotherBrood\n" . TextFormat::RED . "❤ " . TextFormat::YELLOW . $this->entity->getHealth());
        } else {
            Main::$healing = false;
            Server::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }

    }
}