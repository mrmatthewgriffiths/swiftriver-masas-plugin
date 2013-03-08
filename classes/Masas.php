<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * MASAS Settings Controller
 *
 * PHP version 5
 * @author     Matthew Griffiths
 * @package    Swiftriver_Masas
 * @category   Controllers
 * @copyright  None
 */
class Masas {

    /**
     * Database table prefix
     * @var string
     */
    protected static $table_prefix = '';

    public static function install()
    {
        $db_config = Kohana::$config->load('database');
        $default = $db_config->get('default');
        self::$table_prefix = $default['table_prefix'];

        self::_sql();
    }

    private static function _sql()
    {
        $db = Database::instance('default');

        $create = "
			CREATE TABLE IF NOT EXISTS `".self::$table_prefix."masas_settings`
			(
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `user_id` int(11) unsigned NOT NULL,
			  `masas_url` varchar(256) NOT NULL,
			  `masas_secret` varchar(256) NOT NULL,
			  `masas_category` varchar(256) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ";

        $db->query(NULL, $create, TRUE);

        $create = "
			CREATE TABLE IF NOT EXISTS `".self::$table_prefix."masas_log`
			(
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `user_id` int(11) unsigned NOT NULL,
			  `droplet_id` int(30) unsigned NOT NULL,
			  `masas_url` varchar(256) NOT NULL,
			  `masas_secret` varchar(256) NOT NULL,
			  `masas_category` varchar(256) NOT NULL,
			  `created` int(30) NOT NULL,
			  `status` varchar(256) NOT NULL,
			  `return` varchar(1024) NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		";

        $db->query(NULL, $create, TRUE);
    }

    public static function send_to_hub($masas_url, $masas_secret, $title, $content, $droplet_id, $geo)
    {
        $message =
            '<?xml version="1.0" encoding="UTF-8"?>' .
            '<entry xmlns="http://www.w3.org/2005/Atom">'.
                '<category label="Status" scheme="masas:category:status" term="Actual"/>'.
                '<category label="Icon" scheme="masas:category:icon" term="other"/>'.
                '<title type="xhtml">'.
                    '<div xmlns="http://www.w3.org/1999/xhtml">'.
                        '<div xml:lang="en">' . $title . '</div>'.
                    '</div>'.
                '</title>'.
                '<content type="xhtml">'.
                    '<div xmlns="http://www.w3.org/1999/xhtml">'.
                        '<div xml:lang="en">' . $content . '</div>'.
                    '</div>'.
                '</content>'.
                '{GEO}'.
            '</entry>';

        if ($geo != null)
            $message = str_replace('{GEO}', '<point xmlns="http://www.georss.org/georss">'.$geo.'</point>', $message);

        $curl_error = "";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/atom+xml'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $masas_url . '?secret=' . $masas_secret);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
        $return = curl_exec($ch);
        $curl_error_number = curl_errno($ch);
        if ($curl_error_number)
            $curl_error = curl_error($ch);
        curl_close($ch);
        $passed = ($curl_error == "" && strpos(strtolower($return), "error") === false);

        $date = new DateTime();

        $log = ORM::factory('Masas_Logentry');
        $log->user_id = Auth::instance()->get_user()->id;
        $log->droplet_id = $droplet_id;
        $log->masas_url = $masas_url;
        $log->masas_secret = $masas_secret;
        $log->masas_category = '';
        $log->created = $date->format("U");
        $log->status = $passed;
        $log->return = ($curl_error == "") ? $return : $curl_error;
        $log->save();

        return array(
            $passed, //Passed?
            $return, //return from MASAS
            $curl_error //any curl error
        );
    }
}