<?php

namespace steellgold\combat\commands;

use CortexPE\Commando\BaseCommand;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class CombatCommand extends BaseCommand {

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
		if (!$sender instanceof Player){
			$sender->sendMessage("§cThis command can only be used in-game.");
			return;
		}

		$sender->sendForm(self::getArenasForm());
	}

	public static function getArenasForm() : MenuForm {

		return new MenuForm(
			"Liste des arènes",
			"blabla",
			[
				new MenuOption("test 1")
			],
			function (Player $player, int $selectedOption) : void {

			}
		);
	}

	protected function prepare(): void {
		// TODO: Implement prepare() method.
	}
}