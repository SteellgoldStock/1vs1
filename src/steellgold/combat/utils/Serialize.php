<?php

namespace steellgold\combat\utils;

use pocketmine\item\Item;
use pocketmine\nbt\BigEndianNbtSerializer;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\TreeRoot;

class Serialize {

	private BigEndianNbtSerializer $nbtSerializer;

	public function __construct() {
		$this->nbtSerializer = new BigEndianNbtSerializer();
	}

	public function read(string $data) : array {
		$contents = [];
		$inventoryTag = $this->nbtSerializer->read(zlib_decode($data))->mustGetCompoundTag()->getListTag("Inventory");
		/** @var CompoundTag $tag */
		foreach($inventoryTag as $tag){
			$contents[$tag->getByte("Slot")] = Item::nbtDeserialize($tag);
		}

		return $contents;
	}

	public function write(array $items) :  string|bool {
		$contents = [];
		/** @var Item[] $items */
		foreach($items as $slot => $item){
			$contents[] = $item->nbtSerialize($slot);
		}

		return zlib_encode($this->nbtSerializer->write(new TreeRoot(CompoundTag::create()
			->setTag("Inventory", new ListTag($contents, NBT::TAG_Compound))
		)), ZLIB_ENCODING_GZIP);
	}
}