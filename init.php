<?php defined('SYSPATH') OR die('No direct script access');

/**
 * Hook class for the MASAS plugin
 *
 * PHP version 5
 * @author     Matthew Griffiths
 * @package    Swiftriver_Masas
 * @copyright  None
 * @licence    MIT
 */
class Masas_Init {
	
	public function __construct()
	{
        // Register the plugin for sql running
        Swiftriver_Plugins::register("masas", Masas::install());

		// Hook into the settings page
		Swiftriver_Event::add('swiftriver.settings.nav', array($this, 'settings_nav'));
	}

	/**
	 * Display link in the settings navigation
	 * 
	 * @return	void
	 */
	public function settings_nav()
	{
		$active = Swiftriver_Event::$data;
		
		echo '<li '.(($active == 'masas') ? 'class="active"' : '').'>'.
			HTML::anchor(URL::site('settings/masas'), __('MASAS Settings & Log')).
			'</li>';
	}

}

new Masas_Init;

?>
