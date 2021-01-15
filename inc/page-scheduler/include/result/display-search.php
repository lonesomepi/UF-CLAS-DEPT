<?php

  if ($menu == "search") {

    // check if search term was a specific day of the week
    foreach ($days as $day) {
      if ($search_term == $day) {
        echo "<p><a href=\"";
        the_permalink();
        echo "?menu=days&show={$day}\"><b>View " . $day . " schedules</b></a></p>";
      }
    }
    // check if search term was explicitly a Role
    foreach ($list_master_roles as $role) {
      echo $search_term;
      if ($search_term == $role) {
        echo "<p><a href=\"";
        the_permalink();
        echo "?menu=roles&show={$role}\"><b>View schedules by role: ".$role."</b></a></p>";
      }
    }

    // EXPLICIT-SEARCH-TERMS
    // begin natural search Clear through Empty Gate
    if ($search_term != null && $search_term_length > 0) {
      $list_of_keys = array();
      // master search loop || loop through everything: role > person > details
      foreach ($list_master as $null_role => $list_people) {
        // loop through person > their details
        foreach ($list_people as $person => $list_person_details) {
          // backend lowercase name as new variable for preserved call
          $clean_person = strtolower($person);
          // IF NAME
          if (strpos($clean_person, $search_term) !== false) {
            $list_of_keys[] = $person;
          }
          // IF NAME
          // -- loop through their details: email, phone, teaching_schedule[], office_hours[] => string / array
          foreach ($list_person_details as $key_personal_details => $value_personalDetails) {
            // deal with the strings
            if ($key_personal_details == "email") {
              if (strpos($value_personalDetails, $search_term) !== false) {
                $list_of_keys[] = $value_personalDetails;
              }
            } // (if email) via is_string
            if ($key_personal_details == "phone") {
              // strip phone
              // 352-000-0000 => 3520000000 to enable less choosy find
              $value_personalDetails = str_replace("-","",$value_personalDetails);
              // if number exists in master
              if (strpos($value_personalDetails, $search_term) !== false) {
                // reformat the stripped phone number
                if (preg_match('/^(\d{3})(\d{3})(\d{4})$/', $value_personalDetails,  $pattern_match)) {
                  $newnum = $pattern_match[1] . '-' .$pattern_match[2] . '-' . $pattern_match[3];
                }
                $list_of_keys[] = $newnum;
              } // (if number exists in master)
            } // (if phone) via is_string
          } // overall details loop: email, phone
        } // people as person => details
      } // master search loop /searchLoop /searchLoop /searchLoop

      foreach(search($list_of_keys) as $name => $null_details) {
        parse_search($name, list_master_person($name));
      }
      if (empty($list_of_keys)) {
        echo "<p>Your search for \"<span class=\"strong\">{$search_term}</span>\" returned zero results!</p><p>Please try searching again or <a href=\"";
        the_permalink();
        echo "\">check the index page</a> to find what you're looking for!</p>";
      }
    } else {
      echo "<p>Your search was empty!</p><p>Please try searching again or <a href=\"";
      the_permalink();
      echo "\">check the index page</a> to find what you're looking for!</p>";
    }
  } // end search
