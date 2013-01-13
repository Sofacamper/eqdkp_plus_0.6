<?PHP
  $new_version    = '2.0.3';
  $updateFunction = false;
  $updateSQL = array(
  	"ALTER TABLE __gallery_pictures ADD `votes` TEXT NULL AFTER `comment`;",
  	"ALTER TABLE __gallery_pictures ADD `rating_points` TEXT NULL AFTER `votes`;",
  	"ALTER TABLE __gallery_pictures ADD `rating` TEXT NULL AFTER `rating_points`;",
  	"ALTER TABLE __gallery_pictures ADD `voted_users` TEXT NULL AFTER `rating`;",
  );
?>