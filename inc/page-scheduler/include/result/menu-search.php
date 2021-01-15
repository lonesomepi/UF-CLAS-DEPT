<!-- search -->
<?php if ($menu == "search") { ?>
  <form action="<?php echo the_permalink(); ?>" method="post">
    <input type="text" name="search_term" value="<?php echo $search_term; ?>" placeholder="search" required>
    <input type="submit" name="submit_search" value="search">
  </form>
<?php } ?>
