
  <aside>
    <div class="container_aside_innard">
      <p><a href="<?php the_permalink(); ?>">back</a></p>
    </div>
    <div class="container_aside_innard">
      <?php
        echo "<h2>".ucwords(urldecode($menu))."</h2>";
        include 'include/result/menu-day.php';
        include 'include/result/menu-role.php';
        include 'include/result/menu-search.php';
        include 'include/result/menu-available.php';
      ?>
    </div>
  </aside>

  <main>
    <?php
      include 'include/result/display-day.php';
      include 'include/result/display-role.php';
      include 'include/result/display-search.php';
      include 'include/result/display-available.php';
    ?>
  </main>
