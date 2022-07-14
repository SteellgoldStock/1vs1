<?php

namespace steellgold\combat\listeners;

use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;
use steellgold\combat\Combat;
use steellgold\combat\commands\CombatCommand;
use steellgold\combat\utils\CombatManager;
use steellgold\combat\utils\instances\Duel;

class DuelListeners implements Listener {

	public function onJoin(PlayerJoinEvent $event) {
		$player = $event->getPlayer();
		$player->sendForm(CombatCommand::getArenasForm());
	}

	public function onFight(EntityDamageByEntityEvent $ev) {
		$player = $ev->getEntity();
		if ($player instanceof Player) {
			if ($player->getHealth() - $ev->getFinalDamage() <= 0) {
				if ($ev->getCause() == EntityDamageEvent::CAUSE_ENTITY_ATTACK) {
					$killer = $player->getLastDamageCause()->getDamager();
					if ($killer instanceof Player) {
						if (key_exists($player->getName(), CombatManager::$players)) {
							$duel = Combat::getInstance()->getManager()->getDuel(CombatManager::$players[$player->getName()]);
							if ($duel instanceof Duel) {
								$duel->end($killer, $player);
							}
						}
					}
				}
			}
		}
	}

	public function onBlockPlaced(BlockPlaceEvent $event) {
		$player = $event->getPlayer();
		if (key_exists($player->getName(), CombatManager::$players)) {
			$duel = Combat::getInstance()->getManager()->getDuel(CombatManager::$players[$player->getName()]);
			if ($duel instanceof Duel) {
				$duel->addBlock($event->getBlock()->getPosition());
			}
		}
	}
}