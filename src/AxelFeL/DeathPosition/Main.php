<?php

namespace AxelFeL\DeathPosition;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

class Main extends PluginBase implements Listener {
  
  public function onEnable() : void {
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    $this->saveResource("config.yml");
  }
  
  public function onPlayerDeath(PlayerDeathEvent $event) {
    $player = $event->getPlayer();
    $x = $player->getPosition()->getFloorX();
    $y = $player->getPosition()->getFloorY();
    $z = $player->getPosition()->getFloorZ();
    $player->sendMessage(str_replace(["{x}", "{y}", "{z}"], [$x, $y, $z], $this->getConfig()->get("death-position-message")));
  }
}
