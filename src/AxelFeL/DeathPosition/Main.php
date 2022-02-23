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
use pocketmine\scheduler\ClosureTask;

class Main extends PluginBase implements Listener {

  /** @var array $deathloc */
  private $deathloc = [];
  
  public function onEnable() : void {
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    $this->saveResource("config.yml");
  }
  
  /**
	 * @param CommandSender $sender
	 * @param Command $command
	 * @param String $label
	 * @param Array $args
	 * @return bool
	 */
	public function onCommand(CommandSender $sender, Command $command, String $label, Array $args): bool {
		if($command->getName() == "deathback"){
			if(!$sender instanceof Player){
				$sender->sendMessage("§cPlease use commands in Game!");
				return false;
			}
			if($this->backLocation($sender)){
				$sender->sendMessage("§ayou have returned from the place of death!");
			} else {
				$sender->sendMessage("§cYou can't go back!, Because you forgot where to die!");
			}
		}
		return true;
	}
  
  public function backLocation(Player $player): bool{
  	$name = $player->getName();
  	if(!isset($this->deathloc[$name])){
  		return false;
  	}
  	$player->teleport($this->deathloc[$name]);
  	unset($this->deathloc[$name]);
  	return true;
  }
  
  public function onPlayerDeath(PlayerDeathEvent $event) {
    $player = $event->getPlayer();
    $pos = $player->getPosition();
    $x = $pos->getFloorX();
    $y = $pos->getFloorY();
    $z = $pos->getFloorZ();
    $player->sendMessage(str_replace(["{x}", "{y}", "{z}"], [$x, $y, $z], $this->getConfig()->get("death-position-message")));
    $this->deathloc[$player->getName()] = $player->getLocation();
    $this->getScheduler()->scheduleDelayedTask(new ClosureTask(
    	function() use($player){
        	$name = $player->getName();
        	if(isset($this->deathloc[$name])){
        		unset($this->deathloc[$name]);
        	}
        }
    ), 20 * 10);
    if($player->hasPermission("deathposition.back")){
    	$player->sendMessage("§aYou have 10 seconds to return, to the place where you died, Type /deathback");
    }
  }
}
