<?php

namespace AxelFeL\DeathPosition;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

class Main extends PluginBase implements Listener {
  
  public function onEnable() : void {
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
  }
  
  public function onPlayerDeath(PlayerDeathEvent $event) {
    $player = $event->getPlayer();
    $x = $player->getPosition()->getFloorX();
    $y = $player->getPosition()->getFloorY();
    $z = $player->getPosition()->getFloorZ();
    $player->sendMessage("§eYour death position on coordinates: §a".$x." ".$y." ".$z);
  }
}
