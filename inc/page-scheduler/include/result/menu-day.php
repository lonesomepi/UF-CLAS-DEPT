<!-- days  -->
<?php if ($menu == "days") { ?>
  <ul class="menu_nav">
    <?php
    foreach ($days as $numeric_day => $verbal_day) {
      foreach ($list_days as $null_index => $day) {
        if ($verbal_day == $day) { ?>
          <li><a <?php if ($day == $show) {echo "class=\"highlight_list_item\" "; } ?>href="<?php the_permalink(); ?>?menu=days&show=<?php echo $day; ?>"><?php echo $day; ?></a></li>
      <?php }
      }
    } ?>
  </ul>
  <!-- menu nav -->

<?php } ?>
