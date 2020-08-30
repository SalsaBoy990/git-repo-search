<table border="1" style="border-collapse: collapse; border-spacing: 0; margin-top: 30px;">
    <thead>
        <tr>
            <th><?php _e('Repo neve');?></th>
            <th><?php _e('Leírás');?></th>
            <th><?php _e('Létrehozva');?></th>
            <th><?php _e('Csillagok száma');?></th>
        </tr>
    </thead>
    <tbody>
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
 <tr>
    <td><a href="$urlToRepo" target="_blank">$repoName</a></td>
    <td>$description</td>
    <td>$year</td>
    <td>$repoStars</td>
 </tr>
GETGITREPOS;
        }
        ?>
    </tbody>
</table>