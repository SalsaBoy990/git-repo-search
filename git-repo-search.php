<?php

namespace AG\GitRepoSearch;

defined('ABSPATH') or die();

/*
Plugin Name: GitHub Repo Live Search
Plugin URI: https://github.com/SalsaBoy990/weather-now
Description: GitHub Repo Live Search, search for Github repositories
Version: 1.0
Author: András Gulácsi
Author URI: https://github.com/SalsaBoy990
License: GPLv2 or later
Text Domain: ag-git-repo-search
Domain Path: /languages
*/

// require all requires once
require_once 'requires.php';

use \AG\GitRepoSearch\GitRepoSearch as GitRepoSearch;

use \AG\GitRepoSearch\Log\KLogger as Klogger;


$ag_git_repo_search_log_file_path = plugin_dir_path(__FILE__) . '/log';

$ag_git_repo_search_log = new KLogger($ag_git_repo_search_log_file_path, KLogger::INFO);

// main class
GitRepoSearch::getInstance();

// we don't need to do anything when deactivation
// register_deactivation_hook(__FILE__, function () {});

register_activation_hook(__FILE__, '\AG\GitRepoSearch\GitRepoSearch::activatePlugin');

// delete options when uninstalling the plugin
register_uninstall_hook(__FILE__, '\AG\GitRepoSearch\GitRepoSearch::uninstallPlugin');
