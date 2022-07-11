<?php

namespace steellgold\combat\commands;

use CortexPE\Commando\BaseCommand;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use steellgold\combat\Combat;

class CombatCommand extends BaseCommand {

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
		if (!$sender instanceof Player){
			$sender->sendMessage("§cThis command can only be used in-game.");
			return;
		}

		$sender->sendForm(self::getArenasForm());
	}

	public static function getArenasForm() : MenuForm {

		$buttons = [];
		$duels = [];

		$i = 0;
		foreach (Combat::getInstance()->getManager()->getDuels() as $duelId => $duel) {
			$buttons[] = new MenuOption($duel->getDisplayName() . "\n" . $duel->getSlots());
			$duels[$i] = $duelId;
			$i++;
		}

		return new MenuForm(
			"Liste des arènes",
			$buttons,
			}
		);
	}

	protected function prepare(): void {
		// TODO: Implement prepare() method.
	}
}