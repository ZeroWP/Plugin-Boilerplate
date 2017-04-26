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

1. Rename plugin folder name from `zerowp-plugin-boilerplate` to your plugin slug `my-todo-app`.
2. Rename plugin file name from `zerowp-plugin-boilerplate.php` to your plugin slug `my-todo-app.php`.
3. Open `my-todo-app.php` and update the header info. change plugin name, description, version and so on...
	
	Perform a global **Case-sensitive** `search and replace` in `my-todo-app` folder. The order is important!

4. Search for `zerowp-plugin-boilerplate` and replace with `my-todo-app`
5. Search for `ZeroWP Plugin Boilerplate` and replace with `My TODO App`
6. Search for prefix `ZPBNamespace` and replace with `MyTodoApp`.
7. Search for prefix `ZPB` and replace with `MTAPP`.
8. Search for prefix `zpb` and replace with `mtapp`.

9. Update `readme.txt` file with correct data.