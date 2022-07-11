<?php

namespace steellgold\combat;

use CortexPE\Commando\PacketHooker;
use pocketmine\plugin\PluginBase;
use steellgold\combat\commands\CombatAdminCommand;
use steellgold\combat\commands\CombatCommand;
use steellgold\combat\utils\CombatManager;

class Combat extends PluginBase {

	public static Combat $instance;
	public CombatManager $manager;

	protected function onEnable(): void {
		self::$instance = $this;
		$this->manager = new CombatManager();

		if(!PacketHooker::isRegistered()) {
			PacketHooker::register($this);
		}

		if (!file_exists($this->getDataFolder() . "arenas.json")) $this->saveResource("arenas.json");
		$this->getServer()->getCommandMap()->register("duels", new CombatCommand($this,"arenas","Liste des arènes"));
		$this->getServer()->getCommandMap()->register("duels", new CombatAdminCommand($this,"arena","Gérer les arènes"));
	}

	public function getManager() : CombatManager {
		return $this->manager;
	}

	public static function getInstance() : Combat {
		return self::$instance;
	}
}