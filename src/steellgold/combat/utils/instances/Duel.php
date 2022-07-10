<?php

namespace steellgold\combat\utils\instances;

use pocketmine\player\Player;

class Duel {

	/**
	 * @param string $id
	 * @param Player|null $player1
	 * @param Player|null $player2
	 * @param bool $isStarted
	 */
	public function __construct(
		private readonly string $id,
		private ?Player         $player1 = null,
		private ?Player         $player2 = null,
		private bool            $isStarted = false
	) {
		var_dump($this->player1->getName() . " vs " . $this->player2->getName());
	}

	public function getId(): string {
		return $this->id;
	}

	public function setPlayer1(?Player $player1): void {
		$this->player1 = $player1;
	}

	public function getPlayer1(): ?Player {
		return $this->player1;
	}

	public function setPlayer2(?Player $player2): void {
		$this->player2 = $player2;
	}

	public function getPlayer2(): ?Player {
		return $this->player2;
	}

	public function setStarted(): void {
		$this->isStarted = true;
	}

	public function isStarted(): bool {
		return $this->isStarted;
	}
}