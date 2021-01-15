/* ---------------------------------------------------------------------------------------------------------------- */
+-----------+
| Structure |
+-----------+

          - .../UF-CLAS-DEPT/
(index)     -- page-scheduler.php
            -- /inc/
              --- /page-scheduler/
(main)          ---- page-result.php
(main)          ---- page-schedule.php
(css)           ---- styles.php
                ---- <!-- readme.txt -->
                ---- /include/
                  ----- db.php
                  ----- mechanics.php
(js)              ----- script.php
                  ----- time.php


/* ---------------------------------------------------------------------------------------------------------------- */

+-----------------+
| function checks |
+-----------------+

- search "days" returns true, but doesn't instantiate a boolean variable to be called later
- search "roles" cannot return true because its stripping
  -- search - flicks $request_get to cheat the boolean | var $menu follows suit (mechanics.php)
  -- and there IS a clean function -- that's not actually written as a function (mechanics.php)
- Dashboard - you have to add the position separately -- twice
- capitalization matters for sort (index)


/* ---------------------------------------------------------------------------------------------------------------- */

+-----------+
| Variables |
+-----------+

  // $list_master_roles = faculty_adjunct...
  // $list_team_members = team member name

  // $list_master_periods_uf = "1" => array("7:25 am", "8:15 am"),
> // $list_master_periods    = converted uf periods 1:40 p.m => 1340

  // $list_master = instantiation list for role-based structure -- rewrite this
