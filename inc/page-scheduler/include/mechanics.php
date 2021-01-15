<?php

// set timezone to local time (a little unsure about relying on this method)
date_default_timezone_set("America/New_York");

  // db connect and query string
  include 'db.php';

  $list_tempGen = array();
  while ($row = mysqli_fetch_assoc($query)) {
    // create SEED list
    $list_tempGen[] = $row;
  }

  $list_master_roles = array();
  $list_team_members = array();

  foreach ($list_tempGen as $null_key => $list_row) {
    // grab the base variables, roles and people
    // Roles
    if (!in_array($list_row['slug'], $list_master_roles)) {
      $list_master_roles[] = $list_row['slug'];
    }
    // People
    if (!in_array($list_row['post_title'], $list_team_members)) {
      $list_team_members[] = $list_row['post_title'];
    }
  } // instantiation for base arrays


  // p($list_team_members);

  include 'time.php';

  // role create | to avoid errors
  foreach ($list_master_roles as $null_index => $role) {
    // echo $role;
    foreach ($list_tempGen as $null_key => $list_row) {
      // rewriting to empty role as string and create array
      $list_master[$role] = array();
    }
  }

  // p($list_master);

      // List Master | General Structure | inserting people and creating lower-level structure
      // $role = either fellow, faculty, faculty adjunct -- small list of roles
      foreach ($list_master_roles as $null_index => $role) {
        foreach ($list_tempGen as $null_key => $list_row) {
          // if master lists print row == little lists role
          if ($list_row['slug'] == $role) {
            $role_member = $list_row['post_title'];
            $role_title  = $list_row['slug'];
            // if alex is not in the [role] --
            if (!in_array($role_member, $list_master[$role_title])) {
              $list_master[$role_title][$role_member] = array(
                'email'             => "",
                'phone'             => "",
                'teaching_schedule' => $create_days_teaching_schedule,
                'office_hours'      => $create_days_office_hours
               );
            } // in_array (member)
          } // if slug == role
        } // list_row
      } // master

// DETAILS -- setting lower-level information: email, phone, officeHours, teachingSchedule
      foreach ($list_master_roles as $null_index => $role) {
        foreach ($list_tempGen as $null_key => $list_row) {
          $role_member = $list_row['post_title'];
          $role_title  = $list_row['slug'];
          $role_period = $list_row['meta_value'];

          // email
          if ($list_row['meta_key'] == "member-email") {
            $list_master[$role_title][$role_member]['email'] = $list_row['meta_value'];
          }
          // phone
          if ($list_row['meta_key'] == "member-phone") {
            $list_master[$role_title][$role_member]['phone'] = $list_row['meta_value'];
          }
          // website
          if ($list_row['meta_key'] == "member-website") {
            $list_master[$role_title][$role_member]['website'] = $list_row['meta_value'];
          }

          // teaching schedule
          if (strpos($list_row['meta_key'], "period") !== false) {

            $role_member = $list_row['post_title'];
            $role_title  = $list_row['slug'];
            $role_period = $list_row['meta_value'];

            $role_period    = str_replace("period_","",$role_period);
            $explode_period = explode("_", $role_period);

            $period_day  = $explode_period['0'];
            $period_slot = $explode_period['1'];

            foreach ($list_master[$role_title][$role_member]['teaching_schedule'][$period_day] as $key_period => $empty_value) {
              if ($key_period == $period_slot) {
                $list_master[$role_title][$role_member]['teaching_schedule'][$period_day][$key_period] = $period_slot;
              }
            }
          } // teaching schedule

          // office hours
          if (strpos($list_row['meta_key'], "appt") !== false) {
            $role_appt = $list_row['meta_key'];   // appt_day_slot_port
            $role_time = $list_row['meta_value']; // 16:00
            $role_appt = str_replace("appt_","", $role_appt); // saturday_3_1 | monday_1_0
            $explode_appt = explode("_", $role_appt);
            $role_appt_day  = $explode_appt['0']; // saturday
            $role_appt_slot = $explode_appt['1']; // 3
            $role_appt_port = $explode_appt['2']; // 1
            foreach ($days as $numeric => $day) {
              if ($day === $role_appt_day) {
                if ($role_appt_port == "0") {
                  $list_master[$role_title][$role_member]['office_hours'][$day][$role_appt_slot]['start'] = $role_time;
                }
                if ($role_appt_port == "1") {
                  $list_master[$role_title][$role_member]['office_hours'][$day][$role_appt_slot]['end'] = $role_time;
                }
              }
            }
          } // office Hours
        }
      }

      // make days that have scheduled events
      $list_days = array();
          foreach ($list_master as $role => $people) {
            foreach ($people as $person => $schedule) {
              foreach ($schedule as $type => $times) {
                // "teaching_schedule" => "ts"
                if ($type == "teaching_schedule") {
                  foreach ($times as $day_ts => $list_ts) {
                    foreach ($list_ts as $key_ts_slot => $value_ts_slot) {
                      if (!empty($value_ts_slot)) {
                        if (!in_array($day_ts, $list_days)) {
                          $list_days[] = $day_ts;
                        }
                      }
                    }
                  }
                } // /if teaching_schedule

                // "office_hours" => "oh"
                if ($type == "office_hours") {
                  foreach ($times as $day_oh => $list_oh) {
                    foreach ($list_oh as $key_oh_slot => $list_oh_ports) {
                      foreach ($list_oh_ports as $port => $oh_time) {
                        if (!empty($oh_time)) {
                          if (!in_array($day_oh, $list_days)) {
                            $list_days[] = $day_oh;
                          }
                        }
                      }
                    }
                  }
                } // /if office_hours
              }
            }
          }

          // roles
          $list_roles = array();
          foreach ($list_master as $role => $people) {
            if (!in_array($role, $list_roles)) {
              $list_roles[] = $role;
            }
          }
          sort($list_roles);

          // people
          $list_people = array();
          foreach ($list_master as $role => $people) {
            foreach ($people as $person => $null_list) {
              if (!in_array($person, $list_people)) {
                $list_people[] = $person;
              }
            }
          }
          sort($list_people);

          // Function: "People" for displaying page i, drop down through JavaScript
          function list_master_person($selected_parameter) {
            global $list_master;
            $list_master_teaching_schedule = array();
            foreach ($list_master as $role => $people) {
                foreach ($people as $person => $list_core) {
                  // selected parameter
                  // $role = "foo";
                  if ($person == $selected_parameter) {
                    foreach ($list_master as $role_role => $role_people) {
                      foreach ($role_people as $role_person => $role_list_core) {
                        if ($role_person == $selected_parameter) {
                          // it struggles with role setting because person is a subset of role -- so role > person > person details
                          // to the point that this is kind of redundant because the person wont exist in the system if they dont have a role because roles are the master keys
                          $role_baz = !empty($role_role) ? $role_role : "";
                        }
                      }
                    }
                    $email    = !empty($list_core['email'])   ? $list_core['email']   : "";
                    $phone    = !empty($list_core['phone'])   ? $list_core['phone']   : "";
                    $website  = !empty($list_core['website']) ? $list_core['website'] : "";

                    foreach ($list_core as $core => $variable_values) {
                      if ($core == "teaching_schedule") {
                        foreach ($variable_values as $day => $list_slots_check) {
                          foreach ($list_slots_check as $slot => $check) {
                            if (!empty($check)) {
                              $list_master_teaching_schedule['teaching_schedule'][$day][] = $check;
                            }
                          }
                        }
                      }
                      if ($core == "office_hours") {
                        foreach ($variable_values as $day => $list_slots_check) {
                          foreach ($list_slots_check as $slot => $list_port_time) {
                            foreach ($list_port_time as $port => $time) {
                              if (!empty($time)) {
                                $list_master_teaching_schedule['office_hours'][$day][$slot][$port] = $time;
                              }
                            }
                          }
                        }
                      }
                    }
                  } // if person == parameter
                } // loop details
              } // master loop

             $super_list = array(
               "role"     => $role_baz,
               "email"    => $email,
               "phone"    => $phone,
               "website"  => $website,
               "schedule" => $list_master_teaching_schedule
             );

             foreach ($super_list as $key => $value) {
               // if (!empty($value)) {
                 $list_person[$key] = $value;
               // }
             }

             return $list_person;
            } // / list master person function ()


          // Function: "days" for displaying page ii
          function days_details($selected_parameter) {
            global $list_master;
            $list_master_days = array();
            foreach ($list_master as $role => $people) {
              foreach ($people as $person => $list_core) {
                foreach ($list_core as $core => $variable_values) {
                  if ($core == "teaching_schedule") {
                    foreach ($variable_values as $day => $list_slot_check) {
                      foreach ($list_slot_check as $slot => $check) {
                        if (!empty($check) && $day == $selected_parameter) {
                          $list_master_days[$person]['teaching_schedule'][] = $check;
                        }
                      }
                    }
                  }

                  if ($core == "office_hours") {
                    foreach ($variable_values as $day => $list_slot_port) {
                      foreach ($list_slot_port as $slot => $list_port) {
                        foreach ($list_port as $port => $time) {
                          if (!empty($time) && $day == $selected_parameter) {
                            $list_master_days[$person]['office_hours'][$slot][$port] = $time;
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
            return $list_master_days;
          } // / Function by Days



          // Function: "Roles" for displaying page ii
          function schedule_details($selected_parameter) {
            global $list_master;
            foreach ($list_master as $role => $people) {
              foreach ($people as $person => $list_core) {
                // selected parameter
                if ($selected_parameter == $role) {
                  echo "<h3>{$person}</h3>";
                  if (!empty($list_core['phone'])) {
                    echo "<li>".$list_core['phone']."</li>";
                  }
                  if (!empty($list_core['email'])) {
                    echo "<li>".$list_core['email']."</li>";
                  }
                  if (!empty($list_core['website'])) {
                    echo "<li><a href=\"".$list_core['website']."\" target=\"_blank\">".$list_core['website']."</a></li>";
                  }
                  $list_master_teaching_schedule = array();
                  $list_master_office_hours      = array();
                  // schedule factoring "schedule times" should be cool because its only being practically called here and for the two schedules
                  foreach ($list_core as $core_one => $schedule_times) {
                    // try to find teaching times
                    if ($core_one == "teaching_schedule") {
                      foreach ($schedule_times as $day => $list_period_structure) {
                        foreach ($list_period_structure as $nullCheck_key_period => $scheduled_period) {
                          if (!empty($scheduled_period)) {
                            $list_master_teaching_schedule['teaching_schedule'][$day][] = $scheduled_period;
                          }
                        }
                      }
                    } // /teaching schedule

                    // if office hours exist
                    if ($core_one == "office_hours") {
                      foreach ($schedule_times as $day => $list_period_structure) {
                        foreach ($list_period_structure as $slot => $list_ports) {
                          foreach ($list_ports as $port => $time) {
                            if (!empty($time)) {
                              $list_master_office_hours[$day][$slot][$port] = $time;
                            }
                          }
                        }
                      }
                    } // /office hours
                  }// schedule factoring


                if (!empty($list_master_teaching_schedule)) {
                  echo "<h4>Teaching Schedule</h4>";
                  echo "<table>";
                  foreach ($list_master_teaching_schedule as $null_name => $list_dayTime) {
                    foreach ($list_dayTime as $day => $list_period) {
                      echo "<tr>";
                      echo     "<td>{$day}</td>";
                      echo     "<td>";
                      foreach ($list_period as $period_slot => $value_period) {
                        if (!empty($value_period)) {
                          echo $value_period;
                          if ($value_period !== end($list_period)) {
                            echo ", ";
                          }
                        }
                      }
                      echo    "<td>";
                      echo "</tr>";
                    }
                  }
                  echo "</table>";
                }

                  // print office hours
                  if (!empty($list_master_office_hours)) {
                    echo "<h4>Office Hours</h4>";
                    foreach ($list_master_office_hours as $day => $list_slot) {
                      echo "<h5>{$day}</h5>";
                      foreach ($list_slot as $slot => $list_port) {
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
                  } // print office hours
                } // if argument == argument
              } // loop role people
            } // loop list_master
          } // /end show(function)


          function aggitate_teaching_schedule($masterList) {
            foreach ($masterList as $day => $list_periods) {
              echo "<h5>{$day}</h5>";
              echo "<p>";
              foreach ($list_periods as $null_index => $period) {
                echo $period;
                if ($period !== end($list_periods)) {
                  echo ", ";
                }
              }
              echo "</p>";
            }
          }


          function aggitate_office_hours ($masterList) {
            foreach ($masterList as $day => $list_slot) {
              echo "<h5>{$day}</h5>";
              foreach ($list_slot as $slot => $list_port) {
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
          } // function aggitate office hours

          function parse_core($list_master_person) {
            foreach ($list_master_person as $core => $variable_values) {
              if (!empty($variable_values)) {
                if (is_string($variable_values)) {
                  echo "<li>{$variable_values}</li>";
                }
              }
            }
          }

// function search, search, search
          function search ($list_of_keys) {
            global $list_master;
            // cycle keys with search_key
            foreach ($list_of_keys as $search_key) {
              // list_master
              foreach ($list_master as $role => $people) {
                foreach ($people as $person => $details) {
                  if ($search_key == $person) {
                    // they have to be exact because originations
                    $list[$person] = array();
                  }
                  foreach ($details as $core => $variable_values) {
                    // clear gate [string]
                    if (is_string($variable_values)) {
                      if (strpos($variable_values, $search_key) !== false) {
                        //calling search_term to replace / highlight
                        // dont do this
                        // $variable_values = str_replace("{$search_term}","<span class=\"strong\">{$search_term}</span>",$variable_values);
                        // you dont really need to add the complexity to the list since the name[key] will become the trigger
                        $list[$person][$core] = $variable_values;
                      }
                    }
                  }
                }
              }
            }
            if (!empty($list)) {
              ksort($list);
            } else {
              $list = array();
            }
            return $list;
          } // end function (search)

// final function parse search results and prepare for javascript
// this is fragile because it's specific to be called within the overall search function looping through the keysList

          function parse_search($name, $list_master_person) {
            echo "<h4>{$name}</h4>";
            foreach ($list_master_person as $core => $variable_values) {
              if (!empty($variable_values)) {
                if (is_string($variable_values)) {
                  echo "<li>{$variable_values}</li>";
                }
                if (is_array($variable_values)) {
                  foreach ($variable_values as $schedule_type => $list_variables) {
                    if ($schedule_type == "teaching_schedule") {
                      echo "<li>Teaching Schedule</li>";
                      echo "<table>";
                      foreach ($list_variables as $day => $list_periods) {
                        echo "<tr><td>{$day}</td>";
                        foreach ($list_periods as $slot => $period) {
                          echo "<td>{$period}";
                          if ($period !== end($list_periods)) {
                            echo ", ";
                          }
                          echo "</td>";
                        }
                        echo "</tr>";
                      }
                      echo "</table>";
                    }
                    if ($schedule_type == "office_hours") {
                      echo "<li>Office Hours</li>";
                      foreach ($list_variables as $day => $list_slot) {
                        echo "<p>{$day}</p>";
                        foreach ($list_slot as $slot => $list_port) {
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
                }
              } // end if !empty values
            }
          }
// function /search, /search, /search

/// function Avaiable Now
  function clock_available_now() {
    global $list_master;
    global $list_master_periods;
    $list_clock_officeHours = array();
    $list_clock_teaching_schedule = array();
    // instantiate as day.militaryHour.minute
    $todays_day = strtolower(date("l"));
    // manipulate day here  manipulate day here
    $todays_day = "friday";
    $key_now    = strtolower(date("Gi"));
    foreach ($list_master as $role => $details) {
      foreach ($details as $person => $list_person_details) {
        foreach ($list_person_details as $core => $variable_values) {
          if ($core == "office_hours") {
            foreach ($variable_values as $day => $list_slot_port) {
              foreach ($list_slot_port as $slot => $list_port) {
                foreach ($list_port as $port => $time) {
                  if (!empty($time)) {
                    // isolate the time to if its today
                    if ($todays_day == $day) {
                      $time = str_replace(":","",$time);
                      if ($port == "start") {
                        $lock_start = $time;
                        $list_clock_officeHours[$person][$slot][$port] = $lock_start;
                      }
                      if ($port == "end") {
                        $lock_end   = $time;
                        $list_clock_officeHours[$person][$slot][$port] = $lock_end;
                      }
                    }
                  }
                }
              }
            }
          } // end if (office_hours)
          if ($core == "teaching_schedule") {
            foreach($variable_values as $ts_day => $list_slot_period) {
              // go through everyone
              foreach ($list_slot_period as $ts_slot => $ts_period) {
                // if there's a value, echo the key
                if (!empty($ts_period)) {
                  if ($ts_day == $todays_day) {
                    $list_clock_teaching_schedule[$person][] = $ts_period;
                  }
                }
              }
            }
          } // end if (teaching_schedule) || INTRODUCES list_master_periods_uf convert(period => times)
        }
      }
    }
    // return $list_clock_officeHours;
    foreach ($list_clock_officeHours as $person => $list_slot_port) {
      // foreach (clock_available_now() as $person => $list_slot_port) {
      foreach ($list_slot_port as $slot => $list_ports) {
        $port_start = $list_ports['start'];
        $port_end   = $list_ports['end'];
        // if now is between the scheduled office hour slot
        if ($key_now >= $port_start && $key_now <= $port_end) {
          $port_start = date('g:i a', strtotime($port_start));
          $list_clock[$person]['office_hours']['start'] = $port_start;
          $port_end   = date('g:i a', strtotime($port_end));
          $list_clock[$person]['office_hours']['end']   = $port_end;
        }
      }
    } // end $list_clock_officeHours

    foreach ($list_clock_teaching_schedule as $person => $list_index_periods) {
      foreach ($list_index_periods as $index => $teaching_period) {
        foreach ($list_master_periods as $uf_period => $list_index_uf_periodTimes) {
          if ($uf_period == $teaching_period) {
            $uf_open  = $list_index_uf_periodTimes[0];
            $uf_close = $list_index_uf_periodTimes[1];
            if ($key_now >= $uf_open && $key_now <= $uf_close) {
              $uf_open  = substr_replace($uf_open,  ":", -2, 0);
              $uf_open  = date('g:i a', strtotime($uf_open));
              $uf_close = substr_replace($uf_close, ":", -2, 0);
              $uf_close = date('g:i a', strtotime($uf_close));
              $list_clock[$person]['teaching_schedule']['open']  = $uf_open;
              $list_clock[$person]['teaching_schedule']['close'] = $uf_close;
            }
          }
        }
      }
    }

    if (!empty($list_clock)) {
      ksort($list_clock);
    } else {
      $list_clock = "There's nothing scheduled right now! Check the Days section for the next available person!";
    }

    return $list_clock;

  }
// end avaiable_now();

    // previously written in page-scheduler:
    $request_get = false;
    // request_get is dictated by GET['menu'];
    // menu: filer => show: this
    if (isset($_GET['menu'])) {
      $request_get = true;
      $menu = $_GET['menu'];
      $show = $_GET['show'];
    }

    $search_term = "";
    if (isset($_GET['exception'])) {
      $search_term = $_GET['search'];
    }

    if (isset($_POST['submit_search'])) {
      $request_get = true;
      $menu = "search";
      $search_term = $_POST['search_term'];

      // clean
      $search_term        = trim($search_term);
      $search_term        = strip_tags($search_term);
      $search_term        = preg_replace("/[^A-Za-z0-9 ]/", '', $search_term);
      $search_term        = strtolower($search_term);
      $search_term_length = strlen($search_term);
    }



?>
