<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * MASAS Settings Controller
 *
 * PHP version 5
 * @author     Matthew Griffiths 
 * @package    Swiftriver_Masas
 * @category   Controllers
 * @copyright  None
 * @licence    MIT
 */
class Controller_Settings_Masas extends Controller_Settings_Main {
	
	
	/**
	 * River collaborators restful api
	 * 
	 * @return	void
	 */	
	public function action_index()
	{
        $url_regex = "%^((https?://)|(www\.))([a-z0-9-].?)+(:[0-9]+)?(/.*)?$%i"; //"^(https?|ftp)://[^\\\s/$.?#].[^\\\s]*$@iS";

        $id = '';
        $user_id = Auth::instance()->get_user()->id;
        $masas_url = "";
        $masas_secret = "";
        $masas_category = "";
        $errors = Array();
        $messages = Array();

        if (HTTP_Request::POST == $this->request->method())
        {
            $id = Arr::get($_POST, "id", null);
            $user_id = Arr::get($_POST, 'user_id', "");
            $masas_url = trim(Arr::get($_POST, "masas_url", ""));
            $masas_secret = trim(Arr::get($_POST, "masas_secret", ""));
            $masas_category = trim(Arr::get($_POST, "masas_category", ""));

            if ($masas_url === "" || !preg_match($url_regex, $masas_url))
                array_push($errors, __("The url you entered does not look like a valid url."));

            if ($masas_secret === "")
                array_push($errors, __("You have to enter a secret."));

            if (count($errors) == 0)
            {
                $settings = ORM::factory("Masas_Settings", $id);
                $settings->values($_POST);
                $settings->save();

                $curl_return = Masas::send_to_hub(
                    $masas_url,
                    $masas_secret,
                    "This is a test message sent from Swiftriver",
                    "This is the content of the test messages sent from Swiftriver",
                    0, //This droplet id identifies this as a test message,
                    "0, 0" //This is a dummy location
                );

                $passed = $curl_return[0];
                $return = $curl_return[1];
                $curl_error = $curl_return[2];

                if ($passed)
                {
                    array_push($messages, __("A test messages was successfully sent to MASAS. Please confirm that that the message shows up in the hub before continuing."));
                    array_push($messages, __("Your settings have been saved and will be used from now on."));
                }
                else
                {
                    if ($curl_error != "")
                        array_push($errors, __("Swiftriver failed to send a test message to this MASAS instance. The error said: <strong>" . $curl_error . "</strong>"));
                    if (strpos(strtolower($return), "error") !== false)
                        array_push($errors, __("Swiftriver failed to send a test message to this MASAS instance. the error returned by the hub was: <strong>" . $return . "</strong>"));
                    array_push($errors, __("Your settings have been saved but you should work to fix this issue or no drops will be sent to the hub."));
                }
            }
        }

        $settings = ORM::factory('Masas_Settings')
            ->where("user_id", "=", $user_id)
            ->find();

        if ($settings->loaded())
        {
            $id = $settings->id;
            $masas_url = $settings->masas_url;
            $masas_secret = $settings->masas_secret;
            $masas_category = $settings->masas_category;
        }

        $log_entries = ORM::factory('Masas_Logentry')
            ->where('user_id', '=', $user_id)
            ->order_by('created', 'DESC')
            ->limit(20)
            ->find_all();

        $this->template->header->title = __('MASAS Configuration');
        $this->active = 'masas';
        $this->settings_content = View::factory('settings/masasconfiguration')
            ->bind('id', $id)
            ->bind('user_id', $user_id)
            ->bind('masas_url', $masas_url)
            ->bind('masas_secret', $masas_secret)
            ->bind('masas_category', $masas_category)
            ->bind('errors', $errors)
            ->bind('log_entries', $log_entries)
            ->bind('messages', $messages);
    }

}

