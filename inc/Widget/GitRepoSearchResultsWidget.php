<?php

namespace AG\GitRepoSearch\Widget;

defined('ABSPATH') or die();

use AG\GitRepoSearch\Log\Logger as Logger;

use AG\GitRepoSearch\API\GetData as GetData;

class GitRepoSearchResultsWidget extends \WP_Widget
{
    use Logger;

    private const DEBUG = 0;

    private const LOGGING = 1;

    private const TRANSIENT_NAME = 'ag_git_repo_search_results_widget';

    private $getData;

    /**
     * Sets up a new Widget instance.
     *
     * @since 2.8.0
     */
    public function __construct()
    {
        $widget_ops = array(
            'classname'                   => 'git_repo_search_results_widget',
            'description'                 => __('Shows a Github Repository search results widget using GitHub API.'),
            'customize_selective_refresh' => true,
        );

        parent::__construct('ag_git_repo_search_results_widget', __('Git Repo Search Results Widget'), $widget_ops);
        $this->alt_option_name = 'ag_git_repo_search_results_widget';

        $this->getData = new GetData();
    }

    /**
     * Outputs the content for the current widget instance.
     *
     * @since 2.8.0
     *
     * @param array $args     Display arguments including 'before_title', 'after_title',
     *                        'before_widget', and 'after_widget'.
     * @param array $instance Settings for the current Recent Reviews widget instance.
     */
    public function widget($args, $instance)
    {
        extract($args);

        if (!isset($args['widget_id'])) {
            $args['widget_id'] = $this->id;
        }

        $heading = (!empty($instance['heading'])) ? $instance['heading'] : '';
        $keyword = (!empty($instance['keyword'])) ? $instance['keyword'] : 'php';
        $sort = (!empty($instance['sort'])) ? $instance['sort'] : 'stars';
        $order = (!empty($instance['order'])) ? $instance['order'] : 'desc';
        $per_page = (!empty($instance['per_page'])) ? $instance['per_page'] : 5;

        $queryArgs = array(
            'sort'      => $sort,
            'order'     => $order,
            'per_page'  => $per_page
        );

        $repos = $this->getData->storeGitRepos($keyword, $queryArgs, self::TRANSIENT_NAME);

        // populate HTML view with data
        require_once AG_GIT_REPO_SEARCH_PLUGIN_DIR . 'pages/gitRepoResultsWidgetTemplate.php';
    }



    /**
     * Handles updating the settings for the current AG_YT_Video_Embed_Single widget instance.
     *
     * @since 2.8.0
     *
     * @param array $new_instance New settings for this instance as input by the user via
     *                            WP_Widget::form().
     * @param array $old_instance Old settings for this instance.
     * @return array Updated settings to save.
     */
    public function update($new_instance, $old_instance)
    {
        $instance               = $old_instance;
        $instance['heading']    = sanitize_text_field($new_instance['heading']);
        $instance['keyword']    = sanitize_text_field($new_instance['keyword']);
        $instance['sort']       = sanitize_text_field($new_instance['sort']);
        $instance['order']      = sanitize_text_field($new_instance['order']);
        $instance['per_page']   = filter_var(sanitize_text_field($new_instance['per_page']), FILTER_VALIDATE_INT);

        return $instance;
    }

    /**
     * Outputs the settings form for the AG_YT_Video_Embed_Single widget.
     *
     * @since 2.8.0
     *
     * @param array $instance Current settings.
     */
    public function form($instance)
    {
        $heading = isset($instance['heading']) ? esc_attr($instance['heading']) : '';
        $keyword = isset($instance['keyword']) ? esc_attr($instance['keyword']) : 'php';
        $sort = isset($instance['sort']) ? esc_attr($instance['sort']) : 'stars';
        $order = isset($instance['order']) ? esc_attr($instance['order']) : 'desc';
        $per_page = isset($instance['per_page']) ? esc_attr($instance['per_page']) : 10;

?>
        <div>
            <p><?php _e('Adds your GitHub Repo Search Results Widget.'); ?></p>
            <p>
                <label for="<?php echo $this->get_field_id('heading'); ?>"><?php _e('Title:'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('heading'); ?>" name="<?php echo $this->get_field_name('heading'); ?>" type="text" value="<?php echo $heading; ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('keyword'); ?>"><?php _e('Keyword:'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('keyword'); ?>" name="<?php echo $this->get_field_name('keyword'); ?>" type="text" value="<?php echo $keyword; ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('sort'); ?>"><?php _e('Sort By:'); ?></label><br />
                <select id="<?php echo $this->get_field_id('sort'); ?>" name="<?php echo $this->get_field_name('sort'); ?>">
                    <option value="stars" <?php echo selected($sort, 'stars'); ?>>Stargazers Count</option>
                    <option value="forks" <?php echo selected($sort, 'forks'); ?>>Number of Forks</option>
                    <option value="help-wanted-issues" <?php echo selected($sort, 'help-wanted-issues'); ?>>Number of Issues</option>
                    <option value="updated" <?php echo selected($sort, 'updated'); ?>>Date of update</option>
                </select>

            </p>
            <p>
                <label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order By:'); ?></label><br />
                <select id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
                    <option value="desc" <?php echo selected($order, 'desc'); ?>>Descending</option>
                    <option value="asc" <?php echo selected($order, 'asc'); ?>>Ascending</option>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('per_page'); ?>"><?php _e('Limit Number of Results:'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('per_page'); ?>" name="<?php echo $this->get_field_name('per_page'); ?>" type="number" min="1" max="30" step="1" value="<?php echo $per_page; ?>" />
            </p>
        </div>
<?php
    }
}
