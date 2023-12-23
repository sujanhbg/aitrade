<?php
$kdata = $this->model()->get_kring_data();
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class=" container">
        <a class="navbar-brand" href="{baseurl}"><?php echo $kdata['company_name']; ?></a>
        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarsExample05" aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-collapse collapse" id="navbarsExample05" style="">
            <ul class="navbar-nav mr-auto">
                <?php
                foreach ($menus as $tm) {
                    echo "<li class=\"nav-item active\">
            <a class=\"nav-link\" href=\"{baseurl}/{$tm['menu_url']}\">{$tm['menu_name']}</a>
          </li>";
                }
                ?>

            </ul>

        </div>
    </div>
</nav>