<?php 
/*
This is just an example component.
All you have to do is to create a folder with a unique name in `components` directory.
The folder name will be included in namspace for classes included in this component.
Then this file that hooks into 'zpb:init' action.
Note the priority is set by default to 10. If you have a component that requires another
component, just set a higher priority number.
*/

/* No direct access allowed!
---------------------------------*/
if ( ! defined( 'ABSPATH' ) ) exit;

/* Inject the component
----------------------------*/
add_action( 'zpb:init', function(){
	
	// Do anything you want here...
	// It's recomended to do all logic in classes, then call them here 
	// and they will be included automatically by autoloader.php
	
	// Example: This hook works
	// echo str_repeat('Hello World!<br>', 10);

	// Example: Autoloading works
	// _NAMESPACE_\Component\ExampleComponent\Thing\Create::test();

}, 90);