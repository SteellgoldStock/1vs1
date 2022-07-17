<?php

namespace steellgold\combat\commands;

use CortexPE\Commando\BaseCommand;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\command\CommandSender;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\world\Position;
use steellgold\combat\Combat;
use steellgold\combat\utils\CombatManager;

class CombatCommand extends BaseCommand {

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
		if (!$sender instanceof Player){
			$sender->sendMessage("§cThis command can only be used in-game.");
			return;
		}

		if (!key_exists($sender->getName(), CombatManager::$players)) {
			$sender->sendForm(self::getArenasForm());
		} else {
			$sender->sendMessage("§cCliquez sur le lit pour quitter la fîle d'attente.");
		}
	}

	public static function getArenasForm() : MenuForm {

		$buttons = [];
		$duels = [];

		$i = 0;
		foreach (Combat::getInstance()->getManager()->getDuels() as $duelId => $duel) {
			if ($duel->getPosition(1) instanceof Position AND $duel->getPosition(2) instanceof Position) {
				$buttons[] = new MenuOption($duel->getDisplayName() . "\n" . $duel->getSlots());
				$duels[$i] = $duelId;
				$i++;
			}
		}

		return new MenuForm(
			"Liste des arènes",
			"§l» §rVoici la liste des arènes disponibles:",
			$buttons,
			function (Player $player, int $selectedOption) use ($duels) : void {
				$duel = Combat::getInstance()->getManager()->getDuel($duels[$selectedOption]);
				if ($duel->getPlayer1() == null) $duel->setPlayer(1, $player, true);
				else $duel->setPlayer(2, $player, true);

				if ($duel->getSlots(true) == 2) {
					if ($duel->getPlayer1()->isOnline()) {
						$player->sendMessage(str_replace("{ARENA_NAME}", $duel->getDisplayName(), Combat::getInstance()->getConfig()->get("messages")["joined-duel"]));
						$duel->getPlayer1()->sendMessage(str_replace("{PLAYER}", $player->getName(), Combat::getInstance()->getConfig()->get("messages")["player-joined-duel"]));
						$duel->getPlayer1()->getInventory()->removeItem(VanillaItems::RED_BED());
						$duel->start();
					}else{
						$duel->setPlayer(1, $player, true);
						$duel->setPlayer(2, null);
						$player->sendMessage(str_replace("{ARENA_NAME}", $duel->getDisplayName(), Combat::getInstance()->getConfig()->get("messages")["joinded-duel"]));
						$player->sendMessage(str_replace("{ARENA_NAME}", $duel->getDisplayName(), Combat::getInstance()->getConfig()->get("messages")["old-waiting-for-player"]));
						$player->getInventory()->setItem(8, VanillaItems::RED_BED());
					}
				} elseif ($duel->getSlots(true) == 1) {
					$player->sendMessage(str_replace("{ARENA_NAME}", $duel->getDisplayName(), Combat::getInstance()->getConfig()->get("messages")["waiting-for-player"]));
					$player->getInventory()->setItem(8, VanillaItems::RED_BED());
				}
			}
		);
	}

	protected function prepare(): void {
		// TODO: Implement prepare() method.
	}
}