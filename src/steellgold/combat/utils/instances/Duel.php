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

	private function setId(string $id): void {
		$this->id = $id;
	}

	public function getWorld(): ?World {
		return $this->world;
	}

	private function setWorld(?World $world): void {
		$this->world = $world;
	}

	public function getDisplayName(): string {
		return $this->display_name;
	}

	public function setDisplayName(string $display_name): void {
		$this->display_name = $display_name;
	}

	public function setPlayer(int $id, ?Player $player): void {
		match ($id) {
			1 => $this->player1 = $player,
			2 => $this->player2 = $player
		};

		$this->player1->teleport(match ($id) {
			1 => $this->position1,
			2 => $this->position2
		});

		$this->player1->setGamemode(GameMode::SURVIVAL());
		$this->player1->setImmobile();
	}

	public function getPlayer1(): ?Player {
		return $this->player1;
	}

	public function getPlayer2(): ?Player {
		return $this->player2;
	}

	public function setPosition(int $id, Position $position): void {
		match ($id) {
			1 => $this->position1 = $position,
			2 => $this->position2 = $position
		};
	}

	public function getPosition(int $id, bool $toString = false): null|string|Position {
		$position = match ($id) {
			1 => $this->position1,
			2 => $this->position2,
			default => null
		};

		if ($toString) {
			return "{$position->getFloorX()}:{$position->getFloorY()}:{$position->getFloorZ()}:{$position->getWorld()->getFolderName()}";
		}

		return $position;
	}

	public static function fromPositionStrign(string $position) : Position {
		$pos = explode(":", $position);
		return new Position((int)$pos[0],(int)$pos[1],(int)$pos[2], Server::getInstance()->getWorldManager()->getWorldByName($pos[3]));
	}

	public function setStarted(bool $status = true): void {
		$this->isStarted = $status;
	}

	public function isStarted(): bool {
		return $this->isStarted;
	}
}