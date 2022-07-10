<?php

namespace steellgold\combat\listeners;

use pocketmine\event\Listener;

class DuelListeners implements Listener {

	public function onDuelCreated(onDuelCreatedEvent $event) {
		var_dump($event->getDuel()->getId());
	}
}