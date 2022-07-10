<?php

namespace steellgold\combat\events;

use pocketmine\event\Event;
use steellgold\combat\utils\instances\Duel;

class DuelEvent extends Event {
	public function __construct(
		private readonly Duel $duel
	) {

	}

	public function getDuel(): Duel {
		return $this->duel;
	}
}