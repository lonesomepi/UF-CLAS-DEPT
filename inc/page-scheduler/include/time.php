<?php

    $list_master_periods_uf = array(
      "1"  => array("7:25 am",  "8:15 am"),
      "2"  => array("8:30 am",  "9:20 am"),
      "3"  => array("9:35 am",  "10:25 am"),
      "4"  => array("10:40 am", "11:30 am"),
      "5"  => array("11:45 am", "12:35 pm"),
      "6"  => array("12:50 pm", "1:40 pm"),
      "7"  => array("1:55 pm",  "2:45 pm"),
      "8"  => array("3:00 pm",  "3:50 pm"),
      "9"  => array("4:05 pm",  "4:55 pm"),
      "10" => array("5:10 pm",  "6:00 pm")
      // ends before Es
    );


    // convert 12 hour clock to 24 hour clock
      foreach ($list_master_periods_uf as $period => $list_times) {
        foreach ($list_times as $index => $time) {
          $time =  date("Gi", strtotime($time));
          $list_master_periods[$period][$index] = $time;
        }
      }

      // p($list_master_periods);

      $days = array(
        "1" => "monday",
        "2" => "tuesday",
        "3" => "wednesday",
        "4" => "thursday",
        "5" => "friday",
        "6" => "saturday",
        "7" => "sunday"
      );

      // create days list for the master time loop
      foreach ($days as $day) {
        $create_days_office_hours[$day] = array(
          "1" => array("start" => "", "end" => ""),
          "2" => array("start" => "", "end" => ""),
          "3" => array("start" => "", "end" => "")
          // add additional meeting time slots here
        );
      } // create pre-populating office hours list

      // create days list for the master time loop
      foreach ($days as $day) {
        $create_days_teaching_schedule[$day] = array(
          "1"  => "", "2"  => "", "3"  => "", "4"  => "", "5"  => "", "6"  => "", "7"  => "", "8"  => "", "9"  => "", "10" => ""
        );
      } // create pre-populating teaching schedule list
