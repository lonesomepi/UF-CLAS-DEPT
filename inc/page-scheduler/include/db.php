<?php

    global $wpdb;
    $host = $wpdb->dbhost;
    $user = $wpdb->dbuser;
    $pass = $wpdb->dbpassword;
    $data = $wpdb->dbname;

    $link = mysqli_connect($host, $user, $pass, $data);
    if (!$link) {
      echo "Error: Unable to connect to MySQL." . PHP_EOL;
      echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
      echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
      exit;
    }

    $current_blog_id           = get_current_blog_id();
    $formatted_current_blog_id = ($current_blog_id == 1) ? "_" : "_" . $current_blog_id . "_";

    $count    = "SELECT ";
      $count .= "ID, post_title, ";
      $count .= "object_id, term_taxonomy_id, ";
      $count .= "term_id, name, slug, ";
      $count .= "post_id, meta_key, meta_value ";

    $count   .= "FROM ";
      $count .= "wp{$formatted_current_blog_id}posts, ";
      $count .= "wp{$formatted_current_blog_id}term_relationships, ";
      $count .= "wp{$formatted_current_blog_id}terms, ";
      $count .= "wp{$formatted_current_blog_id}postmeta ";

    $count   .= "WHERE ";
      $count .= "post_type = 'clas_team_members' AND post_status = 'publish' ";
      $count .= "AND ";
      $count .= "wp{$formatted_current_blog_id}posts.ID = wp{$formatted_current_blog_id}term_relationships.object_id ";
      $count .= "AND ";
      $count .= "wp{$formatted_current_blog_id}term_relationships.term_taxonomy_id = wp{$formatted_current_blog_id}terms.term_id ";
      $count .= "AND ";
      $count .= "wp{$formatted_current_blog_id}postmeta.post_id = wp{$formatted_current_blog_id}term_relationships.object_id";

      $query  = mysqli_query($link, $count);
      if (!$query) { die("Query Failed <br>" . mysqli_error($link)); }


?>
