#!/usr/bin/php
<?php

$sys = array('/dev/null', '/dev/zero');
$dsk = glob('/dev/disk?');
$dev = array_merge($sys, $dsk);

$conf = <<<EOCONF
# Set window title
*.title = dd-gui

# Add a popup menu
pop.type = popup
pop.label = Devices:
pop.width = 320

EOCONF;

foreach ($dev as $d) {
	$conf = $conf . "pop.option = $d\n";
}
$conf = $conf . "pop.default = $d\n";

// Define what the dialog should be like
// Take a look at Pashua's Readme file for more info on the syntax
$conf = $conf . <<<EOCONF2

# Add a cancel button with default label
cb.type = cancelbutton
EOCONF2;

# Pass the configuration string to the Pashua module
$result = pashua_run($conf);

/**
 * Wrapper function for accessing Pashua from PHP
 *
 * @param string Configuration string to pass to Pashua
 * @param optional string Configuration string's text encoding (default: "macroman")
 * @param optional string Absolute filesystem path to directory containing Pashua
 * @return array Associative array of values returned by Pashua
 * @author Carsten Bluem <carsten@bluem.net>
 * @version 2005-04-26
 */
function pashua_run($conf, $encoding = 'macroman', $apppath = null) {

	// Check for safe mode
	if (ini_get('safe_mode')) {
		die("\n  Sorry, to use Pashua you will have to disable\n".
		    "  safe mode or change the function pashua_run()\n".
		    "  to fit your environment.\n\n");
	}

	// Write configuration string to temporary config file
	$configfile = tempnam('/tmp', 'Pashua_');
	$fp = fopen($configfile, 'w') or user_error("Error trying to open $configfile", E_USER_ERROR);
	fwrite($fp, $conf);
	fclose ($fp);
	
	// Try to figure out the path to pashua
	$bundlepath = "Pashua.app/Contents/MacOS/Pashua";
	$path = '';

	if ($apppath) {
		// A directory path was given
		$path = str_replace('//', '/', $apppath.'/'.$bundlepath);
	}
	else {
		// Try find Pashua in one of the common places
		$paths = array(
			dirname(__FILE__).'/Pashua',
			dirname(__FILE__)."/$bundlepath",
			"./$bundlepath",
			"/Applications/$bundlepath",
			"$_SERVER[HOME]/Applications/$bundlepath"
		);
		// Then, look in each of these places
		foreach ($paths as $searchpath) {
			if (file_exists($searchpath) and
				is_executable($searchpath)) {
				// Looks like Pashua is in $dir --> exit the loop
				$path = $searchpath;
				break;
			}
		}
	}

	// Raise an error if we didn't find the application
	if (empty($path)) {
		user_error('Unable to locate Pashua', E_USER_ERROR);
	}

	// Call pashua binary with config file as argument and read result
	$cmd = preg_match('#^\w+$#', $encoding) ? "'$path' -e $encoding $configfile" : "'$path' $configfile";
	$result = `$cmd`;

	// Remove config file
	unlink($configfile);
	
	// Init result array
	$parsed = array();

	// Parse result
	foreach (explode("\n", $result) as $line) {
		preg_match('/^(\w+)=(.*)$/', $line, $matches);
		if (empty($matches) or empty($matches[1])) {
			continue;
		}
		$parsed[$matches[1]] = $matches[2];
	}

	return $parsed;

} // function pashua_run($conf)
?>