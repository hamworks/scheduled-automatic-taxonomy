<?php
/**
 * Plugin Name:     scheduled-automatic-taxonomy
 * Plugin URI:      https://github.com/team-hamworks/scheduled-automatic-taxonomy
 * Description:     CSV Exporter
 * Author:          HAMWORKS
 * Author URI:      https://ham.works
 * License:         GPLv2 or later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     scheduled-automatic-taxonomy
 * Domain Path:     /languages
 * Version: 2.0.0
 */

use HAMWORKS\WP\Scheduled_Automatic_Taxonomy\Scheduled_Automatic_Taxonomy;

require_once __DIR__ . '/vendor/autoload.php';

new Scheduled_Automatic_Taxonomy();
