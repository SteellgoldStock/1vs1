<?php

namespace steellgold\combat\commands\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use steellgold\combat\Combat;

class InventoryCommand extends BaseSubCommand {

	protected function prepare(): void {
		$this->registerArgument(0,new RawStringArgument("id",false));
	}

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
		$duel->setInventory($sender->getInventory()->getContents());
		$duel->setArmor($sender->getArmorInventory()->getContents());
		$duel->setOffhand($sender->getOffhandInventory()->getContents());
		$sender->sendMessage(str_replace(["{ARENA_NAME}"],[$duel->getDisplayName()],Combat::getInstance()->getConfig()->get("messages")["inventory"]));
	}
}