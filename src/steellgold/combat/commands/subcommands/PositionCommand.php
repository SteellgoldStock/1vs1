<?php

namespace steellgold\combat\commands\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;

class PositionCommand extends BaseSubCommand {

	protected function prepare(): void {
		$this->registerArgument(0,new RawStringArgument("id",false));
		$this->registerArgument(1,new IntegerArgument("position_id",false));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
		var_dump("position");
	}
}