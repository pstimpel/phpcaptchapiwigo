# PHP Captcha for Piwigo

* Plugins home in Piwigo extensions gallery: https://piwigo.org/ext/extension_view.php?eid=882
* Plugin repository home: https://github.com/pstimpel/phpcaptchapiwigo
* Developers home: https://wp.peters-webcorner.de/


## About

A small and lightweight Captcha solution to keep your installation free from bot spam comments and bot fake registrations.

It does not rely on any remote resources - like Recaptcha from Google or any others. This means you do not have to mention it in your GDPR compliance declaration. If Google enables Recaptcha V3 soon, Recaptcha will act like a tracker, since the advice is to implement it on every single page of your website. You will dislike this, hopefully.

Installation is as easy as with any other plugin. Since this plugin does not use any database tables it does not matter which MySQL version you are using.

The Captcha works the classic way. There are some configuration options like colors, OCR confusion, dimensions and length of challenge. Just explore the possibilities in the plugin area of Piwigo admin.

Yes, it is the 5th or so Captcha solution, but all other solutions rely on remote resources or are not maintained anymore.

Would love to receive feedback.

## Installation

### Using your Piwigo mechanisms (recommended)

1. Go to your Piwigo admin area, and search for PHP Captcha for Piwigo
1. Configure the plugin in your plugins area
1. use it

### By manual install

1. Download the latest release from https://github.com/pstimpel/phpcaptchapiwigo/releases
1. unzip to a folder
1. create the folder phpcaptchapiwigo in your Piwigos plugin folder
1. Move the content of the zip into this new folder
1. On Unix run chmod -R 666 phpcaptchapiwigo, maybe adap the ownership of files and directories as well
1. Configure the plugin in your plugins area
1. use it


## Credits

Heavily based on work by Mistic and his Plugin Crypto Captcha

Colorpicker by Stefan Petre

Thanks to MatthieuLP for making the stuff compatible with Piwigo 12
