<?php

namespace steellgold\combat;

use CortexPE\Commando\exception\HookAlreadyRegistered;
use CortexPE\Commando\PacketHooker;
use JsonException;
use pocketmine\plugin\PluginBase;
use steellgold\combat\commands\CombatAdminCommand;
use steellgold\combat\commands\CombatCommand;
use steellgold\combat\listeners\DuelListeners;
use steellgold\combat\utils\CombatManager;
use steellgold\combat\utils\Serialize;

class Combat extends PluginBase {

	public static Combat $instance;
	public CombatManager $manager;
	public Serialize $serializer;

	/**
	 * @throws HookAlreadyRegistered
	 */
	protected function onEnable(): void {
		self::$instance = $this;
		$this->serializer = new Serialize();

		$this->manager = new CombatManager();

		if(!PacketHooker::isRegistered()) {
			PacketHooker::register($this);
		}

		if (!file_exists($this->getDataFolder() . "arenas.json")) $this->saveResource("arenas.json");
		$this->getServer()->getPluginManager()->registerEvents(new DuelListeners(), $this);
		$this->getServer()->getCommandMap()->register("arenas", new CombatCommand($this,"arenas","Liste des arènes"));
		$this->getServer()->getCommandMap()->register("arena", new CombatAdminCommand($this,"arena","Gérer les arènes"));
	}

	/**
	 * @throws JsonException
	 */
	protected function onDisable(): void {
		foreach ($this->manager->getDuels() as $duel) $duel->save();
	}

	public function getSerializer(): Serialize {
		return $this->serializer;
	}

	public function getManager() : CombatManager {
		return $this->manager;
	}

	public static function getInstance() : Combat {
		return self::$instance;
	}
}