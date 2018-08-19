<?php

namespace NewZerosm\commands;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\item\Item;

use pocketmine\network\mcpe\protocol\types\CommandEnum;
use pocketmine\network\mcpe\protocol\types\CommandParameter;

use NewZerosm\StaffMenu;
use NewZerosm\ZerosmCommand;

use onebone\economyapi\EconomyAPI;

class StaffUICommand extends ZerosmCommand{
	
	public function __construct(StaffMenu $owner){
		parent::__construct(
			"관리자메뉴", 
			"§c스탭 명령어", 
			"/관리자메뉴", 
			[],
			[]
		
			);

		$this->owner = $owner;
	}

	public function _execute(CommandSender $sender, string $label, array $args) : bool{
		$player = $sender->getPlayer();
		
		if(!$sender instanceof Player){
			$sender->sendMessage("§c서버 내에서만 사용하실수 있는 명령어 입니다.");
			return true;
		}
		
		if($sender->isOP()){
			$api = $sender->getServer()->getPluginManager()->getPlugin("FormAPI");
			$form = $api->createSimpleForm(function (Player $sender, array $data){
				$result = $data[0];
						
				if($result === null){
					return true;
				}
				
				switch($result){
					case 0:
						$command = "3dtouch";
						$sender->getServer()->getCommandMap()->dispatch($sender, $command);
					break;
					
					case 1:
						$sender->sendMessage("§c아직 준비중인 기능입니다. 빠른 시일내로 준비하겠습니다.");
					break;
					
					case 2:
						$api = $sender->getServer()->getPluginManager()->getPlugin("FormAPI");
						if ($api === null || $api->isDisabled()) {
						}
						$form = $api->createCustomForm(function(Player $sender, array $data){
							$result = $data[0];
							
							$name = $result;
							
							$player = $sender->getServer()->getPlayer($name);
							$ip = $player->getAddress();
							$xuid = $player->getXuid();
							
							if($result != null){
								$sender->sendMessage("§7----------------");
								$sender->sendMessage("§f<§e조회§f> 닉네임 정보: §e".$name);
								$sender->sendMessage("§f<§e조회§f> IP 정보: §e".$ip);
								$sender->sendMessage("§f<§e조회§f> UUID 정보: §e".$player->getPlayer()->getUniqueId());
								$sender->sendMessage("§f<§e조회§f> Xuid 정보: §e".$xuid);
								$sender->sendMessage("§7----------------");
								return true;
							}
					
							if($result == null){
								$sender->addTitle("§l§c✖", "§7닉네임을 입력하지 않았습니다!");
								return true;
							}
						});
						$form->setTitle("§l§8<< §fUser Date Info §8>>");
						$form->addInput("닉네임 입력란", "유저 정보를 확인할 유저의 닉네임을 적어주세요!");
						$form->addLabel("해당 정보는 외부로 유출하시면 안됩니다.");
						$form->sendToPlayer($sender);
					break;	
					
					case 3:
						$sender->sendMessage("§c아직 준비중인 기능입니다. 빠른 시일내로 준비하겠습니다.");
					break;
					
					case 4:
						$api = $sender->getServer()->getPluginManager()->getPlugin("FormAPI");
						if ($api === null || $api->isDisabled()) {
						}
						$form = $api->createCustomForm(function(Player $sender, array $data){
							$result = $data[0];
							$result1 = $data[1];
							
							$name = $result;
							$reason = $result1;
							
							$player = $sender->getServer()->getPlayer($name);
							
							if($result != null){
								$sender->sendMessage("§7----------------");
								$sender->sendMessage("§f<§e차단§f> 플레이어 §e".$name."§f님을 §cKick§f 했습니다.");
								$sender->sendMessage("§f<§e차단§f> 사유: §e".$reason."");
								$sender->sendMessage("§7----------------");
								
								$player->kick("§eZEROSM Network§f 서버에서 Kick 처리 되었습니다.\n§f사유: §e".$reason."\n§f몇초뒤에 서버에 재접속 해보세요!", false);
								return true;
							}
					
							if($result == null){
								$sender->addTitle("§l§c✖", "§7닉네임을 입력하지 않았습니다!");
								return true;
							}
						});
						$form->setTitle("§l§8<< §fKick §8>>");
						$form->addInput("닉네임 입력란", "Kick을 진행할 플레이어의 닉네임을 입력해주세요!");
						$form->addInput("차단 사유 입력란", "차단 사유를 적어주세요!");
						$form->addLabel("닉네임 차단 목록에 추가할 유저의 닉네임을 입력해주세요!");
						$form->sendToPlayer($sender);
					break;
					
					case 5:
						$api = $sender->getServer()->getPluginManager()->getPlugin("FormAPI");
						if ($api === null || $api->isDisabled()) {
						}
						$form = $api->createCustomForm(function(Player $sender, array $data){
							$result = $data[0];
							$result1 = $data[1];
							
							$name = $result;
							$reason = $result1;
							
							if($result1 == null){
								$reason = "BanUI로 차단됨";
								return true;
							}
							
							if($result != null){
								$sender->sendMessage("§7----------------");
								$sender->sendMessage("§f<§e차단§f> 플레이어 §e".$name."§f님을 §c닉네임 차단§f 했습니다.");
								$sender->sendMessage("§f<§e차단§f> 사유: §e".$reason."");
								$sender->sendMessage("§f<§e차단§f> 닉네임 차단 목록이 정상적으로 저장되었습니다.");
								$sender->sendMessage("§7----------------");
								
								$sender->getServer()->getNameBans()->addBan($name, $reason, null, $sender->getName());
								return true;
							}
					
							if($result == null){
								$sender->addTitle("§l§c✖", "§7닉네임을 입력하지 않았습니다!");
								return true;
							}
						});
						$form->setTitle("§l§8<< §fName Ban §8>>");
						$form->addInput("닉네임 입력란", "차단 하실 플레이어의 닉네임을 입력해주세요!");
						$form->addInput("차단 사유 입력란", "차단 사유를 적어주세요!");
						$form->addLabel("닉네임 차단 목록에 추가할 유저의 닉네임을 입력해주세요!");
						$form->sendToPlayer($sender);
					break;
								
					case 6:
						$api = $sender->getServer()->getPluginManager()->getPlugin("FormAPI");
						if ($api === null || $api->isDisabled()) {
						}
						$form = $api->createCustomForm(function(Player $sender, array $data){
							$result = $data[0];
							
							$name = $result;
							
							if($result != null){
								$sender->sendMessage("§7----------------");
								$sender->sendMessage("§f<§e차단§f> 플레이어 §e".$name."§f님을 §c닉네임 차단 해제§f 했습니다.");
								$sender->sendMessage("§f<§e차단§f> 닉네임 차단 목록이 정상적으로 저장되었습니다.");
								$sender->sendMessage("§7----------------");
								
								$sender->getServer()->getNameBans()->remove($name);
								return true;
							}
					
							if($result == null){
								$sender->addTitle("§l§c✖", "§7닉네임을 입력하지 않았습니다!");
								return true;
							}
						});
						$form->setTitle("§l§8<< §fName UnBan §8>>");
						$form->addInput("닉네임 입력란", "차단을 해제할 플레이어의 닉네임을 입력해주세요!");
						$form->addLabel("닉네임 차단 목록에 삭제할 유저의 닉네임을 입력해주세요!");
						$form->sendToPlayer($sender);
					break;
				  
					case 7:
						$api = $sender->getServer()->getPluginManager()->getPlugin("FormAPI");
						if ($api === null || $api->isDisabled()) {
						}
						$form = $api->createCustomForm(function(Player $sender, array $data){
							$result = $data[0];
							$result1 = $data[1];
							
							$name = $result;
							$reason = $result1;
							
							$player = $sender->getServer()->getPlayer($name);
							$ip = $player->getAddress();
							
							if($result1 == null){
								$reason = "BanUI로 차단됨";
								return true;
							}
							
							if($result != null){
								$sender->sendMessage("§7----------------");
								$sender->sendMessage("§f<§e차단§f> 플레이어 §e".$name."§f님의 아이피 ".$ip."§f를 §c아이피 차단§f 했습니다.");
								$sender->sendMessage("§f<§e차단§f> 사유: §e".$reason."");
								$sender->sendMessage("§f<§e차단§f> 아이피 차단 목록이 정상적으로 저장되었습니다.");
								$sender->sendMessage("§7----------------");
								
								$sender->getServer()->getIPBans()->addBan($ip, $reason, null, $sender->getName());
								return true;
							}
					
							if($result == null){
								$sender->addTitle("§l§c✖", "§7닉네임을 입력하지 않았습니다!");
								return true;
							}
						});
						$form->setTitle("§l§8<< §fIP Ban §8>>");
						$form->addInput("닉네임 입력란", "차단 하실 플레이어의 닉네임을 입력해주세요!");
						$form->addInput("차단 사유 입력란", "차단 사유를 적어주세요!");
						$form->addLabel("아이피 차단 목록에 추가할 유저의 닉네임을 입력해주세요!");
						$form->sendToPlayer($sender);
					break;
								
					case 8:
						$api = $sender->getServer()->getPluginManager()->getPlugin("FormAPI");
						if ($api === null || $api->isDisabled()) {
						}
						$form = $api->createCustomForm(function(Player $sender, array $data){
							$result = $data[0];
							
							$name = $result;
							
							$player = $sender->getServer()->getPlayer($name);
							$ip = $player->getAddress();
							
							if($result != null){
								$sender->sendMessage("§7----------------");
								$sender->sendMessage("§f<§e차단§f> 플레이어 §e".$name."§f님의 아이피 ".$ip."§f를 §c아이피 차단 해제§f 했습니다.");
								$sender->sendMessage("§f<§e차단§f> 아이피 차단 목록이 정상적으로 저장되었습니다.");
								$sender->sendMessage("§7----------------");
								
								$sender->getServer()->getIPBans()->remove($ip);
								return true;
							}
					
							if($result == null){
								$sender->addTitle("§l§c✖", "§7닉네임을 입력하지 않았습니다!");
								return true;
							}
						});
						$form->setTitle("§l§8<< §fIP UnBan §8>>");
						$form->addInput("닉네임 입력란", "차단을 해제할 플레이어의 닉네임을 입력해주세요!");
						$form->addLabel("아이피 차단 목록에 삭제할 유저의 닉네임을 입력해주세요!");
						$form->sendToPlayer($sender);
					break;
				}				
			});
			$form->setTitle("§l§7<< §bStaff Menu §7>>");
			$form->setContent("차단 메뉴에 오신것을 환영합니다! 관리자님!");
			$form->addButton("§l§9<< §c메인으로 돌아가기 §9>>\n§r§fAssistiveTouch 메인화면으로 이동합니다.", 0);
			$form->addButton("§l§8<< §aServer Message §8>>\n§r§f공지 보내기", 1, "https://i.imgur.com/3llO5u6.png");
			$form->addButton("§l§8<< §aUser Date Info §8>>\n§r§f유저 정보 조회", 2, "https://i.imgur.com/3llO5u6.png");
			$form->addButton("§l§8<< §aUser Inventory Clear §8>>\n§r§f인벤토리 초기화", 3, "https://i.imgur.com/3llO5u6.png");
			$form->addButton("§l§8<< §aKick §8>>\n§r§f플레이어 킥", 4, "https://i.imgur.com/3llO5u6.png");
			$form->addButton("§l§8<< §aName Ban §8>>\n§r§f닉네임 차단", 5, "https://i.imgur.com/3llO5u6.png");
			$form->addButton("§l§8<< §aName UnBan §8>>\n§r§f닉네임 차단 해제", 6, "https://i.imgur.com/3llO5u6.png");
			$form->addButton("§l§8<< §aIP Ban §8>>\n§r§f아이피 차단", 7, "https://i.imgur.com/3llO5u6.png");
			$form->addButton("§l§8<< §aIP UnBan §8>>\n§r§f아이피 차단 해제", 8, "https://i.imgur.com/3llO5u6.png");
			$form->sendToPlayer($sender);
			return true;
			
		}if(!$sender->isOP()){
			$sender->sendMessage("§c해당 명령어는 관리자 전용입니다! 사용하실수 없습니다.");
			return true;
		}
		return true;
	}
}
