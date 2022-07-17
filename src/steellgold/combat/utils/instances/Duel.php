<?php

namespace steellgold\combat\utils\instances;

use JsonException;
use onebone\economyapi\EconomyAPI;
use pocketmine\block\VanillaBlocks;
use pocketmine\entity\HungerManager;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\world\Position;
use pocketmine\world\World;
use steellgold\combat\Combat;
use steellgold\combat\tasks\DuelCountdownTask;
use steellgold\combat\utils\CombatManager;

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

	public function setPlayer(int $id, ?Player $player = null, bool $teleport = false): void {
		match ($id) {
			1 => $this->player1 = $player,
			2 => $this->player2 = $player
		};

		if ($teleport) $player->teleport(match ($id) {
			1 => $this->position1,
			2 => $this->position2
		});

		if ($player !== null) {


			CombatManager::$players[$player->getName()] = $this->getId();
			$player->setGamemode(GameMode::ADVENTURE());
			$player->setImmobile();
		}
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
			$position = "{$position->getFloorX()}:{$position->getFloorY()}:{$position->getFloorZ()}:{$position->getWorld()->getFolderName()}";
		}

		return $position;
	}

	public static function fromPositionStrign(string $position) : Position {
		$pos = explode(":", $position);
		return new Position((int)$pos[0],(int)$pos[1],(int)$pos[2], Server::getInstance()->getWorldManager()->getWorldByName($pos[3]));
	}

	public function setStarted(bool $status = true): void {
		$this->isStarted = $status;

		if ($status) {
			/** @var Player $player */
			foreach ([$this->player1, $this->player2] as $player) {
				$player->setImmobile(false);
				$player->setGamemode(GameMode::SURVIVAL());
				$player->setImmobile(false);
				$player->setHealth(20);
				$player->getHungerManager()->setFood(20);

				$player->getInventory()->setContents($this->inventory);
				$player->getArmorInventory()->setContents($this->armor);
				$player->getOffhandInventory()->setContents($this->offhand);
			}
		}
	}

	public function isStarted(): bool {
		return $this->isStarted;
	}

	/**
	 * @return Item[]
	 */
	public function getInventory(): array {
		return $this->inventory;
	}

	/**
	 * @param Item[] $inventory
	 * @return void
	 */
	public function setInventory(array $inventory): void {
		$this->inventory = $inventory;
	}

	/**
	 * @return Item[]
	 */
	public function getArmor(): array {
		return $this->armor;
	}


	/**
	 * @param Item[] $armor
	 * @return void
	 */
	public function setArmor(array $armor): void {
		$this->armor = $armor;
	}

	/**
	 * @return Item[]
	 */
	public function getOffhand(): array {
		return $this->offhand;
	}

	/**
	 * @param Item[] $offhand
	 * @return void
	 */
	public function setOffhand(array $offhand): void {
		$this->offhand = $offhand;
	}

	public function getSlots(bool $integer = false): int|string {
		if ($integer) {
			$i = 0;
			if ($this->getPlayer1() !== null) $i++;
			if ($this->getPlayer2() !== null) $i++;
			return $i;
		}

		if ($this->player1 !== null and $this->player2 == null) return "1/2";
		elseif ($this->player1 == null and $this->player2 == null) return "§2Arène disponible (0/2)";
		elseif ($this->player1 !== null and $this->player2 !== null) return "§cDuel en cours (2/2)";
		else return "1/2";
	}

	/**
	 * @throws JsonException
	 */
	public function save(): void {
		$file = new Config(Combat::getInstance()->getDataFolder() . "duels.json", Config::JSON);

		if ($this->position1 instanceof Position AND $this->position2 instanceof Position) {
			$serializer = Combat::getInstance()->getSerializer();
			$armor = $serializer->write($this->getArmor());
			$inventory = $serializer->write($this->getInventory());
			$offhand = $serializer->write($this->getOffhand());

			$file->set($this->getId(), [
				"world" => $this->getWorld()->getFolderName(),
				"display_name" => $this->getDisplayName(),
				"positions" => [
					"1" => $this->position1 !== null ? $this->getPosition(1, true) : null,
					"2" => $this->position2 !== null ? $this->getPosition(2, true) : null
				],
				"inventory" => [
					"content" => base64_encode(serialize($inventory)),
					"armor" => base64_encode(serialize($armor)),
					"offhand" => base64_encode(serialize($offhand))
				]
			]);
			$file->save();
		}
	}

	public function addBlock(Position $position): void {
		$xyz = "{$position->getFloorX()}:{$position->getFloorY()}:{$position->getFloorZ()}";
		$this->blocksPlaced[] = $xyz;
	}

	public function removeBlocksPlaced(): void {
		foreach ($this->blocksPlaced as $pos) {
			$this->world->setBlock(new Vector3(...explode(":", $pos)), VanillaBlocks::AIR());
			$this->blocksPlaced = array_diff($this->blocksPlaced, [$pos]);
		}
	}

	public function getBlocksPlaced(): array {
		return $this->blocksPlaced;
	}

	// STATUS FUNCTIONS

	public function start(): void {
		Combat::getInstance()->getScheduler()->scheduleRepeatingTask(new DuelCountdownTask($this), 20);
	}

	public function end(Player $winner, Player $looser): void {
		/** @var Player $player */
		foreach ([$this->player1, $this->player2] as $player) {
			$player->getInventory()->setContents([]);
			$player->getArmorInventory()->setContents([]);
			$player->getOffhandInventory()->setContents([]);

			$player->teleport(Server::getInstance()->getWorldManager()->getDefaultWorld()->getSafeSpawn());
		}

		unset(CombatManager::$players[$this->player1->getName()]);
		unset(CombatManager::$players[$this->player2->getName()]);
		$this->setPlayer(1, null);
		$this->setPlayer(2, null);

		EconomyAPI::getInstance()->addMoney($winner, self::WIN);
		EconomyAPI::getInstance()->reduceMoney($looser, self::LOOSE);

		$this->removeBlocksPlaced();

		$this->setStarted(false);
	}

	public function cancel(): void {
		/** @var Player $player */
		$this->player1->getInventory()->setContents([]);
		$this->player1->getArmorInventory()->setContents([]);
		$this->player1->getOffhandInventory()->setContents([]);
		$this->player1->setImmobile(false);
		$this->player1->setGamemode(GameMode::SURVIVAL());
		$this->player1->teleport(Server::getInstance()->getWorldManager()->getDefaultWorld()->getSafeSpawn());

		unset(CombatManager::$players[$this->player1->getName()]);

		$this->setPlayer(1, null);
		$this->setPlayer(2, null);

		$this->removeBlocksPlaced();
		$this->setStarted(false);
	}

	/**
	 * @throws JsonException
	 */
	public function delete(): void {
		Combat::getInstance()->getManager()->remove($this->getId());
	}
}