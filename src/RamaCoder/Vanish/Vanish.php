<?php
/**
 *  ____                      __     __          _     _
 * / ___| _   _ _ __   ___ _ _\ \   / /_ _ _ __ (_)___| |__
 * \___ \| | | | '_ \ / _ \ '__\ \ / / _` | '_ \| / __| '_ \
 *  ___) | |_| | |_) |  __/ |   \ V / (_| | | | | \__ \ | | |
 * |____/ \__,_| .__/ \___|_|    \_/ \__,_|_| |_|_|___/_| |_|
 *             |_|
 * @author RamaCoder
 * @discord Rama29#5506
 * @YouTube RamaCoder
 * @website https://github.com/TheBigZombieXD/SuperVanish
 * 
 * Copyright (c) RamaCoder 2019-2021
 * 
 * -----------------------------------------
 * This software is distributed under "GNU General Public License v3.0".
 *
 * SuperVanish is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License v3.0 for more details.
 *
 * You should have received a copy of the GNU General Public License v3.0
 * along with this program. If not, see
 * <https://opensource.org/licenses/GPL-3.0>.
 * -----------------------------------------
 */
declare(strict_types = 1);

namespace RamaCoder\Vanish;

use pocketmine\plugin\PluginBase;

use pocketmine\event\Listener;

use pocketmine\utils\TextFormat as C;
use pocketmine\utils\Config;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\entity\Entity;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

/**
 * 
 * @package RamaCoder\Vanish
 * 
 */
class Vanish extends PluginBase implements Listener {

    public $prefix = C::BLUE."[§aSuper§2Vanish§b]§r".C::DARK_GRAY." >".C::WHITE." ";

    public $config;

    public $vanish = array();

    public function onEnable(){
        $this->getLogger()->info($this->prefix . C::GREEN . "Plugin Actived. Plugin By RamaCrasher");
        $this->saveResource("config.yml");
        @mkdir($this->getDataFolder());
        $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML, [
            "Adventure_Vanish" => true
        ]);
        $this->config->set("Adventure_Vanish", true);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    /**
     * 
     * @param CommandSender $sender
     * @param Command $command
     * @param string $label
     * @param array $args
     * return bool
     */
    
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
        switch($command->getName() == "vanish") {
            if(!$sender instanceof Player) {
				$sender->sendMessage($this->prefix . "§r§cThis game only usage in-game!");
				return true;
			}
            if($sender->hasPermission("supervanish.spectate")){
                $name = $sender->getName();
                if (!in_array($name, $this->vanish)) {
                    $this->vanish[] = $name;
                    $sender->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, true);
                    $sender->setNameTagVisible(false);
                    if($this->config->get("Adventure_Vanish") == true){
                        $sender->setGamemode(2);
                    }
                    $sender->sendMessage($this->prefix . C::GREEN . "You are now vanished. No one can see you.");
                    return true;
                } elseif (in_array($name, $this->vanish)) {
                    unset($this->vanish[array_search($name, $this->vanish)]);
                    $sender->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, false);
                    $sender->setNameTagVisible(true);
                    if($this->config->get("Adventure_Vanish") == true){
                        $sender->setGamemode(0);
                    }
                    $sender->setHealth(20);
                    $sender->setFood(20);
                    $sender->sendMessage($this->prefix . C::RED . "You are no longer vanished!. Everyone should be able to see you.");
                    return true;
                }
            }
        }
    }
}
