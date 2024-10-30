<?php

class MeliboValidation {
	protected $API_KEY_CHECK_URL;
    protected $API_KEY_ENV_CHECK_URL;
	protected $SPACE_LETTER;
    protected $apiKeyValid;
	protected $validationErrors;

	public function __construct() {
		$this->API_KEY_CHECK_URL = 'https://api.melibo.de/stingray/settings/widget/{apiKey}/public';
		$this->API_KEY_ENV_CHECK_URL = 'https://api.melibo.de/stingray/settings/widget/{apiKey}/public' . '?environment={environmentID}';
		$this->SPACE_LETTER = ' ';
		$this->apiKeyValid = false;
		$this->validationErrors = [];
	}

	public function getValidationErrors() {
		return $this->getValidationErrors();
	}

    public function apiKeyValidation($input) {
		$fieldName = 'melibo_api_key';
        $oldFieldValue = get_option($fieldName);

		if(empty($input)) {
            $input = $oldFieldValue;
            $this->apiKeyValid = false;
            add_settings_error($fieldName, $fieldName . '_error', __('The API key must not be empty.', MeliboChatbot::PLUGIN_NAME), 'error');

        } else if (strpos($input, $this->SPACE_LETTER) !== FALSE) {
            $input = $oldFieldValue;
            $this->apiKeyValid = false;
            add_settings_error($fieldName, $fieldName . '_error', __('The API key must not contain any spaces.', MeliboChatbot::PLUGIN_NAME), 'error');

        } else {
            $jsonResponse = $this->checkAPIKeyViaMeliboRequest($input);
            
            if(property_exists($jsonResponse, 'chatbotKey')) {
                $this->apiKeyValid = true;
            }

            if(property_exists($jsonResponse, 'statusCode')) {
                $statusCode = $jsonResponse->statusCode;
                $input = $oldFieldValue;
                $this->apiKeyValid = false;
                if ($statusCode == '500') {
                    add_settings_error($fieldName, $fieldName . '_error', __('The entered API key is not valid.', MeliboChatbot::PLUGIN_NAME), 'error');
                }
            }
        }

		return $input;
	}

	public function environmentIDValidation($input) {
        $apiKey = get_option('melibo_api_key');
        $fieldName = 'melibo_environment_id';
        $oldFieldValue = get_option($fieldName);

        if (strpos($input, $this->SPACE_LETTER) !== FALSE) {
            $input = $oldFieldValue;
            add_settings_error($fieldName, $fieldName . '_error', __('The Environment ID must not contain any spaces.', MeliboChatbot::PLUGIN_NAME), 'error');
        } else {
            $jsonResponse = $this->checkAPIKeyViaMeliboRequest($apiKey, $input);

            if(property_exists($jsonResponse, 'chatbotKey')) {
                $this->apiKeyValid = true;
            }

            if(property_exists($jsonResponse, 'statusCode')) {
                $statusCode = $jsonResponse->statusCode;
                $input = "";
                $this->apiKeyValid = false;
                if ($statusCode == '500') {
                    add_settings_error($fieldName, $fieldName . '_error', __('The entered EnvironmentID is not valid with the API key.', MeliboChatbot::PLUGIN_NAME), 'error');                    
                }
            }
        }

        return $input;
    }

	private function checkAPIKeyViaMeliboRequest($apiKey, $environmentID = false) {

        $jsonResponse = $this->callStingrayAPI($apiKey, $environmentID);

        if(is_object($jsonResponse) == FALSE) {
            add_settings_error($fieldName, $fieldName . '_error', __('An unexpected error occurred during testing.', MeliboChatbot::PLUGIN_NAME), 'error');
            $this->apiKeyValid = false;
            return $apiKey;
        }

        return $jsonResponse;
    }

	private function callStingrayAPI($apiKey, $environmentID) {
        
        if(!empty($environmentID)) {
            $url = str_replace('{apiKey}', $apiKey, $this->API_KEY_ENV_CHECK_URL);
            $url = str_replace('{environmentID}', $environmentID, $url);
        } else {
            $url = str_replace('{apiKey}', $apiKey, $this->API_KEY_CHECK_URL);
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $jsonResponse = json_decode(curl_exec($ch));
        curl_close($ch);

        return $jsonResponse;
    }

	public function activateValidation($input) {
        if($this->apiKeyValid == FALSE) {
            $input = '0';
        }
        return $input;
    }
}