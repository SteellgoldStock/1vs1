<?php

namespace steellgold\combat\commands;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use steellgold\combat\commands\subcommands\CreateCommand;
use steellgold\combat\commands\subcommands\DeleteCommand;
use steellgold\combat\commands\subcommands\InventoryCommand;
use steellgold\combat\commands\subcommands\PositionCommand;

class CombatAdminCommand extends BaseCommand {

	protected function prepare(): void {
		$this->registerSubCommand(new CreateCommand("create", "Créer une nouvelle instance de combat"));
		$this->registerSubCommand(new PositionCommand("position", "Définir les positions d'apparition des joueurs"));
		$this->registerSubCommand(new DeleteCommand("delete", "Supprimer une instance de combat"));
		$this->registerSubCommand(new InventoryCommand("inventory", "Copier votre inventaire dans une instance de combat"));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

	}
}