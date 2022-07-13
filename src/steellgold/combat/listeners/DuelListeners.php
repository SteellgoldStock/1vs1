<?php

namespace steellgold\combat\listeners;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Server;
use steellgold\combat\Combat;
use steellgold\combat\commands\CombatCommand;
use steellgold\combat\utils\CombatManager;
use steellgold\combat\utils\instances\Duel;

class DuelListeners implements Listener {

	public function onJoin(PlayerJoinEvent $event) {
		$player = $event->getPlayer();
		$player->sendForm(CombatCommand::getArenasForm());
	}

	public function onDeath(PlayerDeathEvent$event) {
		$player = $event->getPlayer();
		if (key_exists($player->getName(), CombatManager::$players)) {
			$duel = Combat::getInstance()->getManager()->getDuel($player);
			if ($duel instanceof Duel) {
				$duel->end();
			}
		}
	}
}