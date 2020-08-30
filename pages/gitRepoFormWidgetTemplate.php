<?php
echo $before_widget;

if ($heading) {
    echo $before_title . $heading . $after_title;
}

?>
<div>
    <form action="" method="">
        <label for="search"><?php _e('Keresés'); ?></label><br>
        <input style="width: 298px; height: 44px; font-size: 16px;" type="search" name="search" id="git-search-field-widget" placeholder="PHP, JavaScript">
    </form>
</div>
<p><?php _e('Keresési eredmények:'); ?></p>
<div id="ag-git-repo-search-results-widget"></div>
<?php

echo $after_widget;
