<?php

namespace AG\GitRepoSearch;

// use \AG\GitRepoSearch\Menu\Settings as Settings;

use \AG\GitRepoSearch\Shortcodes\ShortCodes as ShortCodes;

defined('ABSPATH') or die();

/**
 * Class made to access the GitHub Search Repositories API
 * Search in repository names, description, and in readme file
 * @see https://docs.github.com/en/rest/reference/search
 * Author: András Gulácsi 2020
 */
final class GitRepoSearch
{
    private const TEXT_DOMAIN = 'ag-git-repo-search';

    private const OPTION_NAME = 'ag_git_repo_search_version';

    private const OPTION_VERSION = '0.1';

    private const TRANSIENT_NAME = 'ag_git_repo_search_results';

    // class instance
    private static $instance;

    private static $shortcodes;

    /**
     * Get class instance, if not exists -> instantiate it
     * @return self $instance
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self(
                new Shortcodes()
            );
        }
        return self::$instance;
    }


    // CONSTRUCTOR ------------------------------
    // initialize properties, some defaults added
    private function __construct(Shortcodes $shortcodes)
    {
        self::$shortcodes = $shortcodes;
        add_action('plugins_loaded', array($this, 'loadTextdomain'));

        // register shortcode for search results
        add_shortcode('github_repo_search_results', array(self::$shortcodes, 'generateSearchResults'));

        // register shortcode for search form
        add_shortcode('github_repo_search_form', array(self::$shortcodes, 'generateSearchForm'));

        // add_filter('admin_init', array(self::$settings, 'create_settings'));

        // add admin menu and page
        add_action('admin_menu', array($this, 'addAdminMenu'));

        // add script on the backend
        add_action('admin_enqueue_scripts', array($this, 'adminLoadScripts'));

        // put the css into head (only admin page)
        // add_action('admin_head', array($this, 'addCSS'));

        // put the css before end of </body>
        add_action('wp_enqueue_scripts', array($this, 'addCSS'));

        // add ajax script
        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_script('git-repo-search-js', plugin_dir_url(dirname(__FILE__)) . 'js/gitRepoSearchWidget.js', array('jquery'));

            // enable ajax on frontend
            wp_localize_script('git-repo-search-js', 'GitRepoSearchAjax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'security' => wp_create_nonce('gitreposearchajax-t6zhsyddea')
            ));
        });

        // connect AJAX request with PHP hooks
        add_action('wp_ajax_ag_github_repos_ajax_action', array($this, 'githubAjaxHandler'));
        add_action('wp_ajax_nopriv_ag_github_repos_ajax_action', array($this, 'githubAjaxHandler'));


        // hook for our widget implementation
        add_action('widgets_init', array($this, 'registerWidgets'));
    }


    // DESCTRUCTOR -------------------------------
    public function __destruct()
    {
    }

    // getter
    public function __get($property)
    {
        // get private property
    }

    // setter
    public function __set($property, $value)
    {
        // set private property
    }


    // METHODS
    public static function loadTextdomain(): void
    {
        // modified slightly from https://gist.github.com/grappler/7060277#file-plugin-name-php

        $domain = self::TEXT_DOMAIN;
        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, trailingslashit(\WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
        load_plugin_textdomain($domain, false, basename(dirname(__FILE__, 2)) . '/languages/');
    }

    /**
     * Register admin menu page and submenu page
     * @return void
     */
    public function addAdminMenu(): void
    {
        global $ag_git_repo_search_settings_page;
        $ag_git_repo_search_settings_page = add_options_page(
            __('GitHub Repo Search Admin'),
            __('GitHub API Search Docs'),
            'manage_options',
            'git_repo_search_docs',
            array($this, 'createOptionsPage')
        );
    }

    public function adminLoadScripts($hook)
    {
        global $ag_git_repo_search_settings_page;

        if ($hook != $ag_git_repo_search_settings_page) {
            return;
        }

        wp_enqueue_style(
            'ag_git_repo_search_css',
            plugins_url('css/git-repo-search.css', dirname(__FILE__, 1))
        );
        // wp_enqueue_script('custom-js', plugins_url('js/custom.js', dirname(__FILE__, 2)));
    }

    public function createOptionsPage()
    {
        require AG_GIT_REPO_SEARCH_PLUGIN_DIR . 'pages/settingsPage.php';
    }





    /**
     * Add some styling to the plugin's admin and shortcode UI
     * @return void
     */
    public function addCSS(): void
    {
        wp_enqueue_style(
            'ag_git_repo_search_css',
            plugins_url() . '/git-repo-search/css/git-repo-search.css'
        );
    }


    /**
     * Add add an option with the version when activated
     */
    public static function activatePlugin(): void
    {
        $option = self::OPTION_NAME;
        // check if option exists, then delete
        if (!get_option($option)) {
            add_option($option, self::OPTION_VERSION);
        }
    }


    // This code will only run when plugin is deleted
    // it will drop the custom database table, delete wp_option record (if exists)
    public static function uninstallPlugin()
    {
        // check if option exists, then delete
        if (get_option(self::OPTION_NAME)) {
            delete_option(self::OPTION_NAME);
        }

        // delete settings option created via Settings API
        if (get_option('ag_git_repo_search_options')) {
            delete_option('ag_git_repo_search_options');
        }

        // delete transient if exists
        if (get_transient(self::TRANSIENT_NAME)) {
            delete_transient(self::TRANSIENT_NAME);
        }
    }


    /**
     * Register the new widget.
     *
     * @see 'widgets_init'
     */
    public function registerWidgets()
    {
        register_widget('\AG\GitRepoSearch\Widget\GitRepoSearchFormWidget');
        register_widget('\AG\GitRepoSearch\Widget\GitRepoSearchResultsWidget');
    }


    public function githubAjaxHandler()
    {
        if (check_ajax_referer('gitreposearchajax-t6zhsyddea', 'security')) {
            $keyword = $_REQUEST['keyword'];
            $args = $_REQUEST['args'];
            $getData = new \AG\GitRepoSearch\API\GetData();

            // json decoded data from API
            $responseData = $getData->apiCall($keyword, $args);

            wp_send_json_success($responseData, 200);
        } else {
            wp_send_json_error();
        }
        wp_die();
    }
}
