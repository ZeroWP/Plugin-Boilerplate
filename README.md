## A plugin framework for WordPress. 

This is an empty plugin designed to be hacked and transformed in your next awesome plugin.

This code is used by us, as a starting point, to build plugins for http://zerowp.com and other sister sites.

The code is distributed under GPLv2 license(just like WordPress itself). You are free to use it in any of your **personal or commercial** projects. Even if it's not required, if you can, include somewhere an attribution link to this page. We'll appreciate it.

### Note: This is NOT a theme framework.

## Getting started

Before you proceed let's assume that the new plugin ID will be `my-todo-app`.

1. Create a copy of `zerowp-plugin-boilerplate`.
1. Rename plugin folder name from `zerowp-plugin-boilerplate` to your plugin slug `my-todo-app`.
1. Rename plugin file name from `zerowp-plugin-boilerplate.php` to your plugin slug `my-todo-app.php`.

##### Step 2: Search and replace

Do a global `search and replace`,  *Case-sensitive*, in `my-todo-app` folder. 
**The order is important!!!**

```
_TEXT_DOMAIN_     // The plugin ID. In this case it is `my-todo-app`
_PLUGIN_NAME_     // The plugin name. Example: `My TODO App`
_PLUGIN_URI_      // The plugin URI.
_AUTHOR_URI_      // The author URI.
_AUTHOR_          // The author name.
_MIN_PHP_VERSION_ // The minimum version of PHP required.
_VERSION_         // The version. Example: `1.0`
_DESCRIPTION_     // The short description.
_LICENSE_URI_     // The license URI.
_LICENSE_         // The license. Example GPL-2.0+
_NAMESPACE_       // The PHP namespace. This one is is used by autoloader.php
ZPB               // The uppercase prefix. This one is used by constants and it's the function name of main plugin instance.
zpb               // The lowercase prefix. This prefix is used for css classes, functions and hooks.
```

##### Step 3: Add plugin data for wp.org plugin repository.

1. Open `readme.txt` file and change the content to match you plugin data.

## Create the plugin
`plugin.php` file is the plugin foundation. Everything what happens in your plugin starts there.

Within this file you'll find the necesary methods to make it functional. Some of these methods are:

* `__construct()` - is where you must include the core files. Include them just before `$this->buildPlugin()`; You better create the `components` and inject the code there using the `zpb:init` action hook.
* `init()` - Later you'll create the core classes in `engine` folder and then you must call them. You can do so just before this line: `do_action( 'mtapp_init' );`.
* `initWidgets()` - Register widgets with `register_widget` function, just before this line: `do_action( 'mtapp_widgets_init' );`.
* `frontendScriptsAndStyles()` and `backendScriptsAndStyles()` will register/enqueue scripts and styles. You must uncomment the enqueue lines.

Everything else is self documented. See the source code for more info.

## How to create the core

* The core files must be included in `engine` folder. The file names for PHP classes must include the suffix `.class.php`. This will make possible to be loaded automatically when needed. Example: `SpecialExample.class.php`
* All core classes must use the previously defined namespace. Suppose it is: *MyTodoApp*. Examples:

#### File: `engine/SpecialExample.class.php`
```php 
namespace MyTodoApp;

class SpecialExample{
	// Class code here
}
```

#### File: `engine/Admin/Settings.class.php`
```php 
namespace MyTodoApp\Admin;

class Settings{
	// Class code here
}
```

* You may separate the code in `components` folder. Then inject each component using the `zpb:init` action hook. Note: Replace the `zpb` prefix! 

## FYI

* `assets` - folder includes main styles, scripts and other data used by plugin.
* `languages` - This is the folder where the language files and template(.pot) files are saved.
* `functions.php` - You may need to define some functions. This file is the easiest way to do it. Of course you can define them in other files and then include them directly in `MTAPP_Plugin::__construct()`. 
* `uninstall.php` - It used to perform some actions when the plugin is uninstalled. For example you may want to delete the data that the plugin added to DB.

## Suggestions

If you think that this project could be improved, visit [Issues](https://github.com/ZeroWP/Plugin-Boilerplate/issues) section and create a new topic.

If you want to contribute, create a pull request and explain what your contribution does and why it should be merged.

**Made with love by [ZeroWP](http://zerowp.com)**
