<?php

namespace steellgold\combat\listeners;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Server;
use steellgold\combat\commands\CombatCommand;

class DuelListeners implements Listener {

	public function onJoin(PlayerJoinEvent $event) {
		$player = $event->getPlayer();
		$player->sendForm(CombatCommand::getArenasForm());
	}
}