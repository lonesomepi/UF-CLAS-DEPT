<?php
  if ($menu == "available") {
    if (is_array(clock_available_now())) {
      foreach (clock_available_now() as $person => $schedule) {
        echo "<h4>{$person}</h4>";
        parse_core(list_master_person($person));
        foreach ($schedule as $schedule_type => $port_time) {
          if ($schedule_type == "teaching_schedule") {
            echo "<h5>Teaching Now</h5>";
            echo "From " . $port_time['open'];
            echo " to "  . $port_time['close'];
          }

          if ($schedule_type == "office_hours") {
            echo "<h5>Office Hours Now</h5>";
            echo "From " . $port_time['start'];
            echo " to "  . $port_time['end'];
          }
        }
      }
    } else { // if it's a string -- meaning there were no results
      echo clock_available_now();
    }
  } // /if (menu == avaiable)
?>
