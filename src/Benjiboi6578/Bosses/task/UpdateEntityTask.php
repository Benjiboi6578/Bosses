<?php

namespace Benjiboi6578\Bosses\task;

use Benjiboi6578\Bosses\Main;
use pocketmine\scheduler\PluginTask;

class UpdateEntityTask extends PluginTask{

    public function onRun($currentTick){
      foreach (Main::getEntities() as $entity){
          if($entity->isCreated()) $entity->updateTick();
      }
    }
}