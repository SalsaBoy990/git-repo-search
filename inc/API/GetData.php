<?php

namespace AG\GitRepoSearch\API;

class GetData
{
    use \AG\GitRepoSearch\Log\Logger;

    private const DEBUG = 0;
    private const LOGGING = 1;
    private const TRANSIENT_NAME = 'ag_git_repo_search_results';

    public function apiCall(
        string $keyword,
        array $args = array(
            'sort'      => 'stars',
            'order'     => 'desc',
            'per_page'  => 30
        )
    ) {
        // sanitize
        $keyword = wp_strip_all_tags(trim($keyword));
        $keyword = esc_html($keyword);

        $baseurl = 'https://api.github.com/search/repositories';

        $queryString = '?q=' . $keyword . '+language=' . $keyword . '&' . $keyword . '+description';

        if ($args['sort']) {
            $queryString .= '&sort=' . esc_html($args['sort']);
        }
        if ($args['order']) {
            $queryString .= '&order=' . esc_html($args['order']);
        }
        if ($args['per_page']) {
            $queryString .= '&per_page=' . esc_html($args['per_page']);
        }


        // construct request url
        $requestUrl = $baseurl . $queryString;

        // $repos = null;

        // Report all errors except E_NOTICE
        error_reporting(E_ALL & ~E_WARNING);

        try {
            // WP HTTP API
            $response = wp_safe_remote_get($requestUrl);

            // get status code and message for error handling
            $statusCode = wp_remote_retrieve_response_code($response);
            $response_message = wp_remote_retrieve_response_message($response);

            if (is_wp_error($response) || $statusCode !== 200) {
                throw new APIQueryException($response_message);
            }

            $repos = wp_remote_retrieve_body($response);
        } catch (APIQueryException $ex) {
            echo '<div class="notice notice-error"><p>' . $ex->getMessage() . '</p></div>';
            $this->exceptionLogger(\AG_GIT_REPO_SEARCH_LOGGING, $ex);
        } catch (\Exception $ex) {
            echo '<div class="notice notice-error"><p>' . $ex->getMessage() . '</p></div>';
            $this->exceptionLogger(\AG_GIT_REPO_SEARCH_LOGGING, $ex);
        }
        // report all errors
        error_reporting(E_ALL);

        return json_decode($repos);
    }


    /**
     * Store git repos in transients
     * @return bool
     */
    public function storeGitRepos(string $keyword = 'php', array $args, string $transientName = self::TRANSIENT_NAME)
    {
        $this->logger(self::DEBUG, self::LOGGING);

        // get transient if already exists
        $currentTransient = get_transient($transientName);
        if ($currentTransient === false) {
            // call Git API and set the transient
            $reposArray = $this->apiCall($keyword, $args);
            set_transient($transientName, $reposArray, 7 * \DAY_IN_SECONDS);
        } else {
            return $currentTransient->items;
        }
        return $reposArray->items;
    }
}
