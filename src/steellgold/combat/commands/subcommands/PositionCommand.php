<?php

namespace steellgold\combat\commands\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use steellgold\combat\Combat;

class PositionCommand extends BaseSubCommand {

	protected function prepare(): void {
		$this->registerArgument(0,new RawStringArgument("id",false));
		$this->registerArgument(1,new IntegerArgument("position_id",false));
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
		$duel->setPosition($args["position_id"],$sender->getPosition());
		$sender->sendMessage(str_replace(["{POSITION_ID}", "{ARENA_NAME}"],[$args["id"], $duel->getDisplayName()],Combat::getInstance()->getConfig()->get("messages")["position"]));
	}
}