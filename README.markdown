# Facebook Plugin

Provide a Authenticate Object to use Facebook Connect to handle user authentication.
http://book.cakephp.org/2.0/en/core-libraries/components/authentication.html

## Requirements

* CakePHP 2.x
* PHP5.3

## Installation

_[Manual]_

* Download
* Unzip that download.
* Copy the resulting folder to `app/Plugin`
* Rename the folder you just copied to `Facebook`

_[GIT Submodule]_

In your app directory type:

	git submodule add git://github.com/Danielpk/CakePHP-Facebook-Auth.git Plugin/Facebook
	git submodule init
	git submodule update

_[GIT Clone]_

In your `Plugin` directory type:

	git clone git://github.com/Danielpk/CakePHP-Facebook-Auth.git Facebook

### Enable plugin

In 2.0 you need to enable the plugin your `app/Config/bootstrap.php` file:
	
	CakePlugin::load('Facebook');

If you are already using `CakePlugin::loadAll();`, then this is not necessary.

## Usage

### Configure

Create a new file `app/Config/facebook.php`:

	$config = array(
		'Facebook' => array(
			'appId'  => 'XXXXXXXXXXXX',
			'secret' => 'XXXXXXXXXXXX',
		)
	);

Then load this config file at `app/Config/bootstrap.php`:
	Configure::load('facebook');

Now you can set Facebook Connect as Authenticate Object:

	$this->Auth->authenticate = array(
		'Facebook.Connect' => array(
			'facebook' => array(
				'scope' => 'email'
			)
		)
	);

### Authenticate options:

* `facebook`: Namespace used at Facebook::getLoginUrl(); More at https://developers.facebook.com/docs/reference/php/facebook-getLoginUrl
	* `scope`:(optional) The permissions to request from the user. If this property is not specified, basic permissions will be requested from the user.
	* `redirect_uri`: (optional) The URL to redirect the user to once the login/authorization process is complete. The user will be redirected to the URL on both login success and failure, so you must check the error parameters in the URL as described in the authentication documentation. If this property is not specified, the user will be redirected to the current URL (i.e. the URL of the page where this method was called, typically the current URL in the user's browser).
	* `display`: (optional) The display mode in which to render the dialog. The default is page, but can be set to other values such as popup.

## TODO

* Merge with https://github.com/webtechnick/CakePHP-Facebook-Plugin
* More Tests

## License

Mit license: http://en.wikipedia.org/wiki/MIT_License