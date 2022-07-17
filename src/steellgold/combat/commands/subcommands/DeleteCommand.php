<?php

namespace steellgold\combat\commands\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use JsonException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use steellgold\combat\Combat;

class DeleteCommand extends BaseSubCommand {

	/**
	 * @throws ArgumentOrderException
	 */
	protected function prepare(): void {
		$this->registerArgument(0,new RawStringArgument("id",false));
	}

	/**
	 * @throws JsonException
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
		if (!$sender instanceof Player){
			$sender->sendMessage("§cThis command can only be used in-game.");
			return;
		}

		if (!Combat::getInstance()->getManager()->duelExist($args["id"])) {
			$sender->sendMessage(str_replace("{ID}", $args["id"], Combat::getInstance()->getConfig()->get("messages")["duel-not-found"]));
			return;
		}

		$duel = Combat::getInstance()->getManager()->getDuel($args["id"]);
		$duel->delete();
		$sender->sendMessage(str_replace("{ID}",$args["id"],Combat::getInstance()->getConfig()->get("messages")["deleted"]));
	}
}