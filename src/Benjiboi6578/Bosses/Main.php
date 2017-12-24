<?php

namespace Bosses;

use Benjiboi6578\Bosses\command\BossCommand;
use Benjiboi6578\Bosses\entity\BaseEntity;
use Benjiboi6578\Bosses\entity\Spider;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDespawnEvent;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener
{
    public $bossClone;
    public static $spawned = false;
    public static $healing = false;

    public $path;

    public static $data;
    public static $drops;
    public static $spawn;

    private static $entities = [];
    private static $knownEntities = [];

    public $config;

    public $bossRewards = [];
    public $bossCommands = [];

    public function onLoad(){
        $this->getLogger()->info("Loading...");
        @mkdir($this->getDateFolder());
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML); [
            "bossItemDropFormat: " => "Nothing to add here",
            "Format: ItemID: " => "Id of item",
            "Format: ItemDamage: " => "Item durability",
            "Format: Count: " => "How much",
            "spiderBossItems: " => [
                [Item::DIAMOND, 0, 64],
            ],
            "spiderBossCommands" => [
                "tell %PLAYER% You Defeated The Boss",
            ],
            "winTitle" => TextFormat::RED . "MotherBrood",
            "winSubTitle" => TextFormat::RED . "Defeated",
        ];

        $this->bossRewards = $this->config->get("spiderBossItems", []);
        $this->bossCommands = $this->config->get("spiderBossCommands", []);
    }

    public function onEnable(){
        self::registerEntity(Spider::class);

        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new UpdateEntityTask($this), 1);
    }

    public static function registerEntity($name){
        $class = new \ReflectionClass($name);
        if(is_a($name, BaseEntity::class, true) and !$class->isAbstract()){
            Entity::registerEntity($name, true);
            if($name::NETWORK_ID !== -1){
                self::$knownEntities[$name::NETWORK_ID] = $name;
            }
            self::$knownEntities[$class->getShortName()] = $name;
        }
    }

    public function EntitySpawnEvent(EntitySpawnEvent $ev){
        $entity = $ev->getEntity();
        if(is_a($entity, BaseEntity::class, true) && !$entity->isClosed()) self::$entities[$entity->getId()] = $entity;
    }

    public function EntityDespawnEvent(EntityDespawnEvent $ev){
        $entity = $ev->getEntity();
        if($entity instanceof BaseEntity) unset(self::$entities[$entity->getId()]);
    }
}