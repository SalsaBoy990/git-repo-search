<?php
echo $before_widget;

if ($heading) {
    echo $before_title . $heading . $after_title;
}

?>

<ul style="margin-top: 30px;">
    <?php
    foreach ($repos as $repo) {
        // truncate description at 256 chars
        $description = mb_substr($repo->description, 0, 255);
        $dots = mb_strlen($repo->description) < 255 ? '' : '...';
        $description .= $dots;

        // only need the year of creation
        $year = date("Y", strtotime($repo->created_at));

        $urlToRepo = $repo->html_url;
        $repoName = $repo->name;
        $repoStars =  $repo->stargazers_count;

        echo <<<GETGITREPOS
 <li>
    <a href="$urlToRepo" target="_blank">$repoName ($repoStars stars)</a>
    <p>$description</p>
 </li>
GETGITREPOS;
    }
    ?>

</ul>
<?php

echo $after_widget;