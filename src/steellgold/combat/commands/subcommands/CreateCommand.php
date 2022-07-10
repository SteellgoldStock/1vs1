<?php

namespace steellgold\combat\commands\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;

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
		var_dump("create");
	}
}