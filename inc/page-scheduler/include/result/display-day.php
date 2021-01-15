
  <?php
   if ($menu == "days") {

  // days_details @ scheduler_mechanics: line 350
  if (!empty(days_details($show))) {
    foreach (days_details($show) as $person => $list_schedule) {
      echo "<h3>{$person}</h3>";

      // parse core pulls/pushes the main four: role, email, number, website
      parse_core(list_master_person($person));

      foreach ($list_schedule as $schedule_type => $list_details) {
        if ($schedule_type == "teaching_schedule") {
          echo "<h4>Teaching Periods</h4>";
          echo "<p>";
          foreach ($list_details as $null_index => $period) {
            echo "{$period}</li>";
            if ($period !== end($list_details)) {
              echo ", ";
            }
          }
          echo "</p>";
        }
        if ($schedule_type == "office_hours") {
          echo "<h4>Office Hours</h4>";
          foreach ($list_details as $null_index => $list_port) {
            foreach ($list_port as $port => $time) {
              $time = date('g:i a', strtotime($time));
              echo $time;
              if ($port == "start") {
                echo " to ";
              }
              if ($port == "end") {
                echo "<br>";
              }
            }
          }
        }
      }
    }
  } else {
    echo "There are no events scheduled for " . ucfirst($show) . ".";
    echo "<br><br>";
    echo "Would you like to search for <a href=\"";
    the_permalink();
    echo "?search={$show}&exception=wildcard\">{$show}</a>?";
  }

}
  ?>
