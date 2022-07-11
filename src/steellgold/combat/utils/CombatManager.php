<?php

namespace steellgold\combat\utils;

use pocketmine\utils\Config;
use steellgold\combat\Combat;
use steellgold\combat\utils\instances\Duel;

class CombatManager {

	/** @var Duel[] */
	public array $duels;

	public function __construct() {
		$duels = new Config(Combat::getInstance()->getDataFolder() . "duels.json", Config::JSON);
		foreach ($duels->getAll() as $duelId => $data) {
			if (!Server::getInstance()->getWorldManager()->isWorldLoaded($data["world"])) {
				Server::getInstance()->getWorldManager()->loadWorld($data["world"]);
				Server::getInstance()->getLogger()->info("World " . $data["world"] . " was loaded because it was in the duel list.");
			}
			$this->duels[$duelId] = new Duel(
				$duelId,
				Server::getInstance()->getWorldManager()->getWorldByName($data["world"]),$data["display_name"],
				null, null,
				Duel::fromPositionStrign($data["positions"]["1"]) ?? null,
				Duel::fromPositionStrign($data["positions"]["2"]) ?? null,
				false,
				$serializer->read(unserialize(base64_decode($data["inventory"]["content"]))),
				$serializer->read(unserialize(base64_decode($data["inventory"]["armor"]))),
				$serializer->read(unserialize(base64_decode($data["inventory"]["offhand"])))
			);
		}
	}

	public function getDuel(string $id): ?Duel {
		foreach ($this->duels as $duel) {
			if ($duel->getId() === $id) {
				return $duel;
			}
		}
		return null;
	}

	public function duelExist(string $id) : bool {
		return $this->getDuel($id) !== null;
	}

	public function getDuels(): array {
		return $this->duels;
	}

	public function createDuel(string $id, World $world, string $display_name): Duel {
		$this->duels[$id] = new Duel($id, $world, $display_name);
		return $this->duels[$id];
	}
}