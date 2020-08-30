<?php

namespace AG\GitRepoSearch\Widget;

defined('ABSPATH') or die();

class GitRepoSearchFormWidget extends \WP_Widget
{
    /**
     * Sets up a new WeatherNow Widget instance.
     *
     * @since 2.8.0
     */
    public function __construct()
    {
        $widget_ops = array(
            'classname'                   => 'git_repo_search_form_widget',
            'description'                 => __('Shows a Github Repository search form widget using GitHub API.'),
            'customize_selective_refresh' => true,
        );

        parent::__construct('ag_git_repo_search_form_widget', __('Git Repo Search Form Widget'), $widget_ops);
        $this->alt_option_name = 'ag_git_repo_search_form_widget';
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

        $heading = (!empty($instance['heading'])) ? $instance['heading'] : __('');
        
        // populate HTML view with data
        require_once AG_GIT_REPO_SEARCH_PLUGIN_DIR . 'pages/gitRepoFormWidgetTemplate.php';
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
        $instance           = $old_instance;
        $instance['heading']  = sanitize_text_field($new_instance['heading']);

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
?>
        <div>
            <p><?php _e('Adds your GitHub Repo Search Form Widget.'); ?></p>
            <p>
                <label for="<?php echo $this->get_field_id('heading'); ?>"><?php _e('Title:'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('heading'); ?>" name="<?php echo $this->get_field_name('heading'); ?>" type="text" value="<?php echo $heading; ?>" />
            </p>
        </div>
<?php
    }
}
