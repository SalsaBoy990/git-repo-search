<div class="wrap">

  <div id="icon-options-general" class="icon32"></div>
  <h1><?php esc_attr_e('GitHub API Repo Live Search Docs'); ?></h1>

  <p><?php esc_attr_e('Read this short instruction manual to use the shortcodes/widgets.'); ?>
    <a href="https://docs.github.com/en/rest/reference/search" target="_blank"><?php esc_attr_e('More info about the GitHub API.'); ?></a>
  </p>

  <div id="poststuff">

    <div id="post-body" class="metabox-holder columns-2">

      <!-- main content -->
      <div id="post-body-content">

        <div class="meta-box-sortables ui-sortable">

          <div class="postbox">

            <h2><span><?php esc_attr_e('GitHub API Repo Live Search Shortcodes'); ?></span></h2>

            <div class="inside">
              <p><?php esc_attr_e(
                    'Add Live Search Form shortcode and cached (with transients) Search Results shortcode.'
                  ); ?></p>

              <h3><?php esc_attr_e('Inserts a search form into a page/post:'); ?></h3>
              <code>[github_repo_search_form]</code>

              <p><?php
                  esc_attr_e(
                    'Limitations: you cannot have arguments for this implementation.
                Arguments are passed in via jQuery AJAX. To change the default query parameters,
                modify "/js/gitRepoSearchWidget.js" file in the plugin folder.'
                  ); ?>
              </p>

              <h4>Query arguments available</h4>
              <ul>
                <li>Sort repositories -> <strong>sort</strong>: "stars" / "forks" / "help-wanted-issues" / "updated" (default: "stars")
                </li>
                <li>Ordering repositories -> <strong>order</strong>: "desc" / "asc" (default: "desc")</li>
                <li>Number of results to show -> <strong>per_page</strong>: 10 (default) </li>
              </ul>

              <h3><?php esc_attr_e('Inserts a cached results table into a page/post:'); ?></h3>
              <code>[github_repo_search_results keyword="php" sort="stars" order="desc" per_page=30 ]</code>

              <h4>Query arguments available</h4>
              <ul>
                <li>Sort repositories -> <strong>sort</strong>: "stars" / "forks" / "help-wanted-issues" / "updated" (default: "stars")
                </li>
                <li>Ordering repositories -> <strong>order</strong>: "desc" / "asc" (default: "desc")</li>
                <li>Number of results to show -> <strong>per_page</strong>: 30 (default) </li>
              </ul>

              <p>The transients' maximum(!) expiration date is one day (it can expire sooner). After expiration, the plugin calls the Git API again to get the updated results and caches them again.</p>
              <p>Reason to use transients: we are using the GitHub API here unauthorized which is why we have rate limits on API calls. We can spare some API calls this way.</p>
            </div>
            <!-- .inside -->

          </div>
          <!-- .postbox -->

        </div>
        <!-- .meta-box-sortables .ui-sortable -->

      </div>
      <!-- post-body-content -->

      <!-- sidebar -->
      <div id="postbox-container-1" class="postbox-container">

        <div class="meta-box-sortables">

          <div class="postbox">

            <h2><span><?php esc_attr_e(
                        'GitHub API Repo Live Search Widgets'
                      ); ?></span></h2>

            <div class="inside">
              <p><?php esc_attr_e(
                    'Add Live Search Form widget and cached (with transients) Search Results widget.'
                  ); ?></p>
              <h3><?php esc_attr_e('Git Repo Search Form Widget'); ?></h3>
              <p><?php
                  esc_attr_e(
                    'Limitations: you cannot have arguments for this implementation.
                Arguments are passed in via jQuery AJAX. To change the default query parameters,
                modify "/js/gitRepoSearchWidget.js" file in the plugin folder.'
                  ); ?>
              </p>
              <p><?php
                  esc_attr_e(
                    'In contrast to the shortcode search, this widget shows the result in a list view instead of a table.'
                  ); ?></p>

              <h3><?php esc_attr_e('Git Repo Search Results Widget'); ?></h3>
              <p><?php
                  esc_attr_e(
                    'This widget uses a different transient as the shortcode, so you can show different query result. Also, it has a list view instead of a table.'
                  ); ?></p>

              <h4>Arguments available in this widget:</h4>
              <ul>
                <li><strong>heading</strong></li>
                <li><strong>keyword</strong></li>
                <li><strong>sort</strong></li>
                <li><strong>order</strong></li>
                <li><strong>per_page</strong></li>
              </ul>

            </div>
            <!-- .inside -->

          </div>
          <!-- .postbox -->

        </div>
        <!-- .meta-box-sortables -->

      </div>
      <!-- #postbox-container-1 .postbox-container -->

    </div>
    <!-- #post-body .metabox-holder .columns-2 -->

    <br class="clear">
  </div>
  <!-- #poststuff -->

</div> <!-- .wrap -->