<?php
/* Template Name: Scheduler */

  get_header();

  $path_include      = "inc/page-scheduler/";
  $boo_available_now = false;

  include $path_include . 'include/mechanics.php';
  include $path_include . 'styles.php';
?>

  <div id="container_wrap_scheduler">
    <?php
      if (!$request_get) {
        include $path_include . 'page-schedule.php';
      } else {
        include $path_include . 'page-result.php';
      }
    ?>
  </div>

  <?php include $path_include . "include/script.php"; ?>

<?php get_footer(); ?>
