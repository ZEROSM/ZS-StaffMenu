<?php

namespace NewZerosm;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\player\PlayerInteractEvent;

class StaffMenu extends PluginBase{
	private static $instance = null;

	public static function getInstance(){
		return self::$instance;
	}

	public function onEnable(){
		if(($this->getServer()->getPluginManager()->getPlugin("FormAPI")) === null){
			$this->getServer()->getLogger()->critical("현재 작동되는 플러그인중 FormAPI 플러그인이 플러그인 폴더내에 없습니다.");
			$this->getServer()->getLogger()->critical("FormAPI 플러그인을 plugins 폴더에 넣어주세요!");
			$this->getServer()->getLogger()->critical("플러그인 로딩이 중지됩니다. 감사합니다.");
			$this->getServer()->getPluginManager()->disablePlugin($this);
			return;
		}
		
		foreach([
			"StaffUICommand"
		] as $class){
			$class = "\\NewZerosm\\commands\\" . $class;
			$this->getServer()->getCommandMap()->register("Staff", new $class($this));
		}
		
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		if($api === null){
			$this->getServer()->getPluginManager()->disablePlugin($this);			
		}
		
		$this->getLogger()->critical("ZS-StaffMenu v1.0.0-Beta | §e오르카 제작"); 
		$this->getLogger()->notice("해당 플러그인은 ZEROSM Network 서버 전용 플러그인으로 외부로 유출하실수 없습니다."); 
		$this->getLogger()->notice("해당 플러그인은 §eZEROSM Network Inc.§b 라이센스로 보호받고 있습니다.");
	}
}
