## A plugin framework for WordPress. 

This is an empty plugin designed to be hacked and transformed in your next awesome plugin.

This code is used by us, as a start point, to build plugins for http://zerowp.com and other sister sites.

The code is distributed under GPLv2 license(just like WordPress itself). You are free to use it in any of your **personal or commercial** projects. Even if it's not required, if you can, include somewhere an attribution link to this page. We'll appreciate it.

### Note: This is NOT a theme framework.

## Getting started

Before you start building your plugin, you must do some changes. 

Let's assume that your new plugin will have the: 
```
name:      My TODO App // Plugin name
slug:      my-todo-app // Text used for folder/plugin file name and text-domain
prefix:    MTAPP       // Prefix used for constants and some PHP classes.
prefix:    mtapp       // Prefix used for functions, hooks, css classnames/ids, etc.
namespace: MyTodoApp   // The namespace used for PHP classes.  
```

##### Step 1: Prepare the structure
1. Rename plugin folder name from `zerowp-plugin-boilerplate` to your plugin slug `my-todo-app`.
2. Rename plugin file name from `zerowp-plugin-boilerplate.php` to your plugin slug `my-todo-app.php`.
3. Open `my-todo-app.php` and update the header info. change plugin name, description, version and so on...

##### Step 2: Search and replace

Perform a global **Case-sensitive** `search and replace` in `my-todo-app` folder. The order is important!

1. Search for `zerowp-plugin-boilerplate` and replace with `my-todo-app`
2. Search for `ZeroWP Plugin Boilerplate` and replace with `My TODO App`
3. Search for prefix `ZPBNamespace` and replace with `MyTodoApp`.
4. Search for prefix `ZPB` and replace with `MTAPP`.
5. Search for prefix `zpb` and replace with `mtapp`.

##### Step 3: Add plugin data for wp.org plugin repository.

1. Open `readme.txt` file and change the content to match you plugin data.

## What's next?

`my-todo-app.php` file contains the base constants. 

1. Change the value of `MTAPP_MIN_PHP_VERSION` constant with the minimum PHP version that your plugin requires. It can't be lower than `5.3`. This will display an upgrade notice to users that use your plugin and does not have the required PHP version.
2. Update `MTAPP_VERSION` to match the version from plugin header. This value must be changed on every plugin update.


## Create the plugin
`plugin.php` files is the plugin foundation. Everything what happens in your plugin starts from there.

Inside you'll find the necesary methods to make it functional. Some of these methods are:

* `config()` - Here you must define the main settings. Then you can access them ouside by calling `MTAPP()->config( $key = false )`.
* `__construct()` - is where you must include the core files. Include them just before `$this->buildPlugin()`;
* `init()` - Later you'll create the core classes in `engine` folder and then you must call them. You can do so just before this line: `do_action( 'mtapp_init' );`.
* `initWidgets()` - Register widgets with `register_widget` function, just before this line: `do_action( 'mtapp_widgets_init' );`.
* `frontendScriptsAndStyles()` and `backendScriptsAndStyles()` will register/enqueue scripts and styles. You must uncomment the enqueue lines.

Everything else is self documented. See the source code for more info.

## How to create the core

* The core files must be included in `engine` folder. The file names for PHP classes must include the suffix `.class.php`. This will make possible to be loaded automatically when needed. Example: `SpecialExample.class.php`
* All core classes must use the previously defined namespace( *MyTodoApp* ). Examples:

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

## FYI

* `assets` - folder includes main styles, scripts and other data used by plugin.
* `languages` - This is the folder where the language files and template(.pot) files are saved.
* `functions.php` - You may need to define some functions. This file is the easiest way to do it. Of course you can define them in other files and then include them directly in `MTAPP_Plugin::__construct()`. 
* `uninstall.php` - It used to perform some actions when the plugin is uninstalled. For example you may want to delete the data that the plugin added to DB.

## Suggestions

If you think that this project could be improved, visit [Issues](https://github.com/ZeroWP/Plugin-Boilerplate/issues) section and create a new topic.

If you want to contribute, create a pull request and exaplain what your contribution does and why.

**Made with :heart: by [ZeroWP](http://zerowp.com)**