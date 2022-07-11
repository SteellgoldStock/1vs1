<?php

namespace steellgold\combat\utils\instances;

use JsonException;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\world\Position;
use pocketmine\world\World;
use steellgold\combat\Combat;
use steellgold\combat\tasks\DuelCountdownTask;

class Duel {

	const WIN = 5;
	const LOOSE = 7;

	/**
	 * @param string $id
	 * @param World|null $world
	 * @param string $display_name
	 * @param Player|null $player1
	 * @param Player|null $player2
	 * @param Position|null $position1
	 * @param Position|null $position2
	 * @param bool $isStarted
	 * @param Item[] $inventory
	 * @param Item[] $armor
	 * @param Item[] $offhand
	 * @param array $blocksPlaced
	 */
	public function __construct(
		private string    $id,
		private ?World    $world,
		private string    $display_name,
		private ?Player   $player1 = null,
		private ?Player   $player2 = null,
		private ?Position $position1 = null,
		private ?Position $position2 = null,
		private bool      $isStarted = false,
		private array     $inventory = [],
		private array     $armor = [],
		private array     $offhand = [],
		private array     $blocksPlaced = [],
	) {

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