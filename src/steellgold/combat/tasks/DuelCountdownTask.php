<?php

namespace steellgold\combat\tasks;

use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use steellgold\combat\utils\instances\Duel;

class DuelCountdownTask extends Task {

	public function __construct(
		private Duel $duel,
		private int  $time = 5
	) {

	}

	public function onRun(): void {
		// Check player 1 connected:
		if ((!$this->duel->getPlayer1()->isOnline() AND !$this->duel->getPlayer1() instanceof Player) or $this->duel->getPlayer2() == null) {
			$this->duel->setPlayer(1, $this->duel->getPlayer2());
			$this->duel->setPlayer(2,null);
			$this->duel->getPlayer1()->sendMessage("§cDuel: Le compte à rebours est annulé, du fait que votre adversaire est hors-ligne");
			$this->getHandler()->cancel();
		}

		// Check player 2 connected:
		if (!$this->duel->getPlayer2()->isOnline() AND !$this->duel->getPlayer2() instanceof Player) {
			$this->duel->setPlayer(2,null);
			$this->getHandler()->cancel();
			return;
		}

		$color = match ($this->time) {
			3 => "§4§l",
			2 => "§c§l",
			1 => "§2§l",
			0 => "§a§l",
			default => "§f§l",
		};

		$this->duel->getPlayer1()->sendTitle($color.$this->time,"",5,5,5);
		$this->duel->getPlayer2()->sendTitle($color.$this->time,"",5,5,5);

		if ($this->time <= 0) {
			$this->duel->setStarted();
			$this->getHandler()->cancel();
		}else{
			$this->time = $this->time - 1;
		}
	}
}