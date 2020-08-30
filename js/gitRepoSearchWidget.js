jQuery(document).ready(function ($) {
  // Shortcode live search
  if ($("#git-search-field").length > 0) {
    ajaxGetGitRepos(
      "#git-search-field",
      "#ag-git-repo-search-results",
      "table"
    );
  }

  // Widget live search
  if ($("#git-search-field-widget").length > 0) {
    ajaxGetGitRepos(
      "#git-search-field-widget",
      "#ag-git-repo-search-results-widget",
      "list"
    );
  }

  function ajaxGetGitRepos(inputId, containerId, outputType) {
    $(inputId).on("keyup", function (count) {
      // get keyword
      var keyword = $(inputId).val();

      // sanitize it to avoid XSS attacks
      keyword = sanitize(keyword);

      // only send AJAX request when the weather now widget is added
      if ($(containerId).length > 0) {
        if ($(inputId).val() != false) {
          // request body
          var data = {
            action: "ag_github_repos_ajax_action",
            security: GitRepoSearchAjax.security,
            keyword: keyword,
            args: {
              sort: "stars",
              order: "desc",
              per_page: 10,
            },
          };

          console.log(GitRepoSearchAjax.ajax_url);

          $.ajax({
            type: "GET",
            url: GitRepoSearchAjax.ajax_url,
            data: data,
            dataType: "json",
          })
            .done(function (response) {
              console.table(response);
              var repos = response.data.items;
              console.log("Git Repo Search AJAX - OK response.");

              // if we get no results
              if (repos.length === 0) {
                $(containerId).html(
                  '<p>A keresett kifejezésre ("' +
                    keyword +
                    '") nincs találat.</p>'
                );
                return;
              } else {
                var myHtml = generateHtml(repos, outputType);
                $(containerId).html(myHtml);
              }
            })
            .fail(function () {
              console.log("AG Git Repo Search AJAX error response.");
            })
            .always(function () {
              console.log("AG Git Repo Search AJAX finished.");
            });
        } else {
          $(containerId).text("");
        }
      }
    });
  }

  // sanitize it to avoid XSS attacks
  // more work needed on this
  // @source: https://codepen.io/gabrieleromanato/pen/GpELf
  function sanitize(input) {
    var output = input
      .replace(/<script[^>]*?>.*?<\/script>/gi, "")
      .replace(/<[\/\!]*?[^<>]*?>/gi, "")
      .replace(/<style[^>]*?>.*?<\/style>/gi, "")
      .replace(/<![\s\S]*?--[ \t\n\r]*>/gi, "");
    return output;
  }

  function generateHtml(repos, type) {
    if (type === "table") {
      var html =
        '<table border="1" style="border-collapse: collapse; border-spacing: 0; margin-top: 30px;">' +
        "<thead>" +
        "<tr>" +
        "<th>Repo neve</th><th>Leírás</th><th>Létrehozva</th><th>Csillagok száma</th>" +
        "</tr>" +
        "</thead>" +
        "<tbody>";

      repos.forEach((element) => {
        // truncate description at 256 chars
        var description = element.description.slice(0, 255);
        var points = element.description.length < 255 ? "" : "...";
        description += points;

        // only need the year of creation
        var year = new Date(element.created_at).getFullYear();

        html += "<tr>";
        html +=
          '<td><a href="' +
          element.html_url +
          '">' +
          element.name +
          "</a></td>";
        html += "<td>" + description + "</td>";
        html += "<td>" + year + "</td>";
        html += "<td>" + element.stargazers_count + "</td>";
        html += "</tr>";
      });

      html += "</tbody>";
      html += "</table>";
    } else if (type === "list") {
      var html = '<ul style="margin-top: 30px;">';

      repos.forEach((element) => {
        // truncate description at 256 chars
        var description = element.description.slice(0, 255);
        var points = element.description.length < 255 ? "" : "...";
        description += points;

        // only need the year of creation
        var year = new Date(element.created_at).getFullYear();

        html += "<li>";
        html +=
          '<a href="' +
          element.html_url +
          '">' +
          element.name +
          " (" +
          element.stargazers_count +
          ")</a>";
        html += "<p>" + description + "</p>";
        html += "</li>";
      });

      html += "</ul>";
    }

    return html;
  }
});
