<aside>

  <div class="container_aside_innard">
    <form action="<?php echo the_permalink(); ?>" method="post">
      <input type="text" name="search_term" value="<?php echo $search_term; ?>" placeholder="search">
      <input type="submit" name="submit_search" value="search">
    </form>
  </div>

  <?php if ($boo_available_now) { // page-scheduler.php head ?>
    <div class="container_aside_innard">
      <p><a href="<?php echo the_permalink(); ?>?menu=available&show=now">Available now</a><?php //if (false) {echo "next available"; } ?></p>
    </div>
  <?php } ?>


  <div class="container_aside_innard">
    <h2>Days</h2>
    <ul class="menu_nav">
      <?php
      foreach ($days as $numeric_day => $verbal_day) {
        foreach ($list_days as $null_index => $day) {
          if ($verbal_day == $day) { ?>
            <li><a href="<?php the_permalink(); ?>?menu=days&show=<?php echo $day; ?>"><?php echo $day; ?></a></li>
          <?php }
        }
      } ?>
    </ul>
  </div>

  <div class="container_aside_innard">
    <h2>Roles</h2>
    <ul class="menu_nav">
      <?php foreach ($list_roles as $role) { ?>
        <li><a href="<?php the_permalink(); ?>?menu=roles&show=<?php echo $role; ?>"><?php echo $role; ?></a></li>
      <?php } ?>
    </ul>
  </div>
</aside>

<main>
  <div class="container_main_innard">
    <h2>People</h2>
    <ul class="menu_nav">
      <?php
        $i = 1;
        foreach ($list_people as $person) { ?>
         <li class="cursorPointer" id="remote<?php echo $i; ?>"><?php echo $person; ?></li>
         <!-- <li class="cursorPointer" id="remote<?php echo $i; ?>"><a href="<?php echo the_permalink(); ?>?showPerson=<?php echo urlencode($person); ?>"><?php echo $person; ?></a></li> -->
         <?php// p(list_master_person($person)); ?>
          <div class="container_personsDetails toggs" id="toggle_<?php echo $i; ?>">
            <ul>
            <?php
               $n = 0;
               foreach (list_master_person($person) as $core => $list_values_variable) {
                 if (!empty($list_values_variable)) {
                 if (is_string($list_values_variable)) { ?>
                   <li><?php  echo $list_values_variable; ?></li><?php
                } else if (is_array($list_values_variable)) {

                  foreach ($list_values_variable as $key_scheduleType => $value_list_schedule) {
                  if ($key_scheduleType == "teaching_schedule") {
                    echo "<h4>Teaching Schedule</h4>";
                    // p($value_list_schedule);
                    aggitate_teaching_schedule($value_list_schedule);
                  }
                  if ($key_scheduleType == "office_hours") {
                    echo "<h4>Office Hours</h4>";
                    aggitate_office_hours($value_list_schedule);
                  }
                }
              }
              // there was a php space break here | unsure
            } else {
                $n++;
                if ($n > 5) {
                  echo $person . " doesn't have any details";
                }
              }
              ?>
            <?php } ?>
            </ul>
          </div>
          <!-- Toggs toggle | container persons details -->
          <?php
            $i++;
            $count = $i;
          ?>
       <?php } ?>
    </ul>
  </div>
  <!-- container main innnard -->
</main>
