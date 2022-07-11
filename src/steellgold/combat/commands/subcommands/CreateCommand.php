<?php

namespace steellgold\combat\commands\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use steellgold\combat\Combat;

class CreateCommand extends BaseSubCommand {

	/**
	 * @throws ArgumentOrderException
	 */
	protected function prepare(): void {
		$this->registerArgument(0,new RawStringArgument("id",false));
		$this->registerArgument(1,new RawStringArgument("world",false));
		$this->registerArgument(2,new TextArgument("display_name",false));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
		if (Combat::getInstance()->getManager()->duelExist($args["id"])) {
			$sender->sendMessage("§cL'identifiant §f{$args["id"]} §cest déjà attribué à une instance de combat.");
			return;
		}

		if (!Server::getInstance()->getWorldManager()->isWorldLoaded($args["world"])) {
			$sender->sendMessage("§cLe monde §f{$args["world"]} §cest introuvable, ou n'est pas chargé");
			return;
		}

		$world = Server::getInstance()->getWorldManager()->getWorldByName($args["world"]);
		Combat::getInstance()->getManager()->createDuel($args["id"], $world, $args["display_name"]);
	}
}