<?php


namespace PGMB\Upgrader;

use InvalidArgumentException;
use PGMB\BackgroundProcessing\BackgroundProcess;

class Upgrader extends \WP_Background_Process implements BackgroundProcess {
	private $database_version;
	private $plugin_version;

	protected $action = 'mbp_upgrade_process';



	protected $available_upgrades = [
		'2.2.3'
	];

	/**
	 * @param $version PHP-standardized version number
	 *
	 * @return bool Supplied version number is valid
	 */
	public function validate_version_number($version){
		if(version_compare( $version, '0.0.1', '>=' )){
			return true;
		}
		return false;
	}

	public function __construct($database_version, $plugin_version) {
		parent::__construct();
		if( !$this->validate_version_number($database_version) || !$this->validate_version_number($plugin_version)){
			throw new InvalidArgumentException("Invalid version number(s) supplied to Upgrader constructor");
		}
		$this->database_version = $database_version;
		$this->plugin_version   = $plugin_version;
	}

	public function init(){
		if(version_compare($this->plugin_version, $this->database_version, '==')){ return; } //If the latest version is already installed

		$upgrade_running = get_option('mbp_upgrade_running');
//
		if($upgrade_running){ return; }

		update_option('mbp_upgrade_running', true);
		foreach($this->get_required_upgrades() as $required_upgrade){
			$upgrade = $this->get_upgrade_instance($required_upgrade);
			if($upgrade){
				$upgrade->run();
			}
		}
		update_option('mbp_version', $this->plugin_version);
		delete_option('mbp_upgrade_running');
	}

	/**
	 * @param $version
	 *
	 * @return Upgrade | bool Upgrade instance or false
	 */
	protected function get_upgrade_instance($version){
		$class_name = '\PGMB\Upgrader\Upgrade_'.str_replace('.', '_', $version);

		if(!class_exists($class_name)){ return false; }

		$upgrade = new $class_name($this);
		if(!$upgrade instanceof Upgrade){ return false; }
		return $upgrade;
	}

	protected function get_required_upgrades(){
		$required_upgrades = [];
		foreach($this->available_upgrades as $available_upgrade){
			if(version_compare($this->database_version, $available_upgrade, '<')){
				$required_upgrades[] = $available_upgrade;
			}
		}
		return $required_upgrades;
	}


	/**
	 * @inheritDoc
	 */
	protected function task( $item ) {
		if(!$upgrade = $this->get_upgrade_instance($item['version'])){ return false; }

		return $upgrade->task($item);

	}

	protected function complete() {
		parent::complete(); // TODO: Change the autogenerated stub

	}
}
