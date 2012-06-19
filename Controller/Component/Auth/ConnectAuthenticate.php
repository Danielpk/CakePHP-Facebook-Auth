<?php 
App::uses('BasicAuthenticate', 'Controller/Component/Auth');
App::import('Vendor', 'Facebook.FacebookSDK', array('file' => 'facebook' . DS . 'facebook.php'));

/**
 * Use Facebook Connect to authenticate user.
 * 
 * Exemple:
 *  $this->Auth->authenticate = array(
 *		'Facebook.Connect' => array(
 *			'facebook' => array(
 *				'scope' => 'email,publish_stream'
 *			)
 *		)
 *	);
 */
class ConnectAuthenticate extends BasicAuthenticate{

/**
 * Connect Settings
 */
	public $settings = array(
		'facebook' => array(
			'scope' => null,
			'redirect_uri' => null,
			'display' => 'page'
		)
	);
/**
 * Facebook SDK
 * 
 * @link https://developers.facebook.com/docs/reference/php
 */
	public $Facebook;

	public function __construct(ComponentCollection $collection, $settings) {
		parent::__construct($collection, $settings);
		extract(Configure::read('Facebook'));
		
		if(!isset($appId) OR !isset($secret)){
			throw new InternalErrorException(__('You need configure file config/facebook.php properly.'));
		}

		if(!isset($fileUpload)){
			$fileUpload = false;
		}

		$this->Facebook = new Facebook(compact('appId', 'secret', 'fileUpload'));
	}
/**
 * Authenticate user.
 * 
 * @return User data.
 */
	public function authenticate(CakeRequest $request, CakeResponse $response) {
		$result = $this->getUser($request);
		if(empty($result)){
			$response->header('Location', $this->Facebook->getLoginUrl($this->getAuthParams()));
			$response->statusCode(401);
			$response->send();
			return false;
		}

		return $result;
	}
/**
 * Try to fetch user from facebook.
 * 
 * @return mixed user array or null.
 */
	public function getUser(CakeRequest $request) {
		$user = $this->Facebook->getUser();
		if(!empty($user)){
			try{
				$user = $this->Facebook->api('/me');
				$user['access_token'] = $this->Facebook->getAccessToken();
				return $user;
			}catch(FacebookApiException $e){
				return null;
			}
		}
		return null;
	}
/**
 * Return params of getLoginUrl()
 * 
 * @return array
 * @link https://developers.facebook.com/docs/reference/php/facebook-getLoginUrl/
 */
	public function getAuthParams() {
		$settings = array_filter($this->settings['facebook'], function($k){
			return !is_null($k) OR $k == true;
		});

		if(isset($settings['scope']) && is_array($settings['scope'])){
			$settings['scope'] = implode(',', $settings['scope']);
		}

		return $settings;
	}
}