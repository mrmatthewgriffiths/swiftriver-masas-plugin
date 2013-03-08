<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Config for Masas Plugin
 *
 * PHP version 5
 * @author     Matthew Griffiths
 * @package    Swiftriver_Masas
 * @copyright  None
 * @licence    MIT
 */
return array(
	'masas' => array(
		'name'			=> 'Masas',
		'description'	        => 'Allows Swiftriver to send content to MASAS',
		'author'		=> 'Matthew Griffiths',
		'email'			=> 'mg@metalayer.com',
		'version'		=> '0.0.1',
		'settings'		=> TRUE,
		'dependencies'	=> array(
			'core' => array(
				'min' => '0.2.0',
				'max' => '10.0.0',
			),
		)	
	),
);
