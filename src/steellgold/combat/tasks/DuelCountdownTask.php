<?php

namespace steellgold\combat\tasks;

use pocketmine\item\VanillaItems;
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
		if (!$this->duel->getPlayer1()->isOnline() and $this->duel->getPlayer2()->isOnline()) {
			$this->duel->setPlayer(1, $this->duel->getPlayer2());
			$this->duel->getPlayer1()->getInventory()->setItem(8, VanillaItems::RED_BED());
			$this->duel->setPlayer(2,null);
			$this->duel->getPlayer1()->sendMessage("§cDuel: Le compte à rebours est annulé, du fait que votre adversaire est hors-ligne");
			$this->getHandler()->cancel();
		}

		if (!$this->duel->getPlayer2()->isOnline() and $this->duel->getPlayer1()->isOnline()) {
			$this->duel->getPlayer1()->sendMessage("§cDuel: Le compte à rebours est annulé, du fait que votre adversaire est hors-ligne");
			$this->duel->setPlayer(2,null);
			$this->getHandler()->cancel();
			return;
		}

		if ($this->duel->getPlayer1()->isOnline() AND $this->duel->getPlayer2()->isOnline()) {
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
}