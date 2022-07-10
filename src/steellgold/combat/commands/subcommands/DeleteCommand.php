<?php

namespace steellgold\combat\commands\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;

class DeleteCommand extends BaseSubCommand {

	protected function prepare(): void {
		$this->registerArgument(0,new RawStringArgument("id",false));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
		var_dump("delete");
	}
}