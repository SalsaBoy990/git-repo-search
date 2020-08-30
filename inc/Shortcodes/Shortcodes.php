<?php

namespace AG\GitRepoSearch\Shortcodes;

defined('\ABSPATH') or die();

use AG\GitRepoSearch\API\GetData as GetData;


/**
 * Shortcode functionality class
 * @see https://docs.github.com/en/rest/reference/search
 */
class ShortCodes extends GetData
{
    private const DEBUG = 0;
    private const LOGGING = 1;

    public function __construct()
    {
    }
    public function __destruct()
    {
    }


    /**
     * Show git repo search results in a shortcode
     * @param array $atts shortcode arguments as key-value pairs
     * @return string
     * @see https://developer.wordpress.org/reference/functions/shortcode_atts/
     */
    public function generateSearchResults(array $atts, string $content = null): string
    {
        $this->logger(self::DEBUG, self::LOGGING);

        global $post;

        /**
         * extract shortcode arguments
         * @see https://developer.wordpress.org/reference/functions/shortcode_atts/
         */
        extract(shortcode_atts(array(
            'keyword'   => 'php',
            'sort'      => 'stars',
            'order'     => 'desc',
            'per_page'  => 30
        ), $atts));

        $keyword = esc_html($keyword);
        $sort = mb_strtolower(esc_html($sort));
        $order = mb_strtolower(esc_html($order));
        $per_page = esc_html($per_page);

        $repos = null;
        $repos = $this->storeGitRepos($keyword, array(
            'sort'      =>  $sort,
            'order'     =>  $order,
            'per_page'  =>  $per_page
        ));

        ob_start();

        require AG_GIT_REPO_SEARCH_PLUGIN_DIR . 'pages/gitRepoSearchResultsTemplate.php';
      
        $content = ob_get_clean();

        return $content;
    }

     /**
     * Show git repo live search form in a shortcode
     * @param array $atts shortcode arguments as key-value pairs
     * @return string
     * @see https://developer.wordpress.org/reference/functions/shortcode_atts/
     */
    public function generateSearchForm(string $content = null): string
    {
        $this->logger(self::DEBUG, self::LOGGING);

        global $post;

        ob_start();

        require AG_GIT_REPO_SEARCH_PLUGIN_DIR . 'pages/gitRepoSearchFormTemplate.php';
      
        $content = ob_get_clean();

        return $content;
    }
}