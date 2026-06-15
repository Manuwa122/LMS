<?php
// Run monthly access reset on every 30th day or after 30th if site was not opened on 30th

$current_day = (int) date("d");
$current_month = date("Y-m");

// Only run on day 30 or 31
if ($current_day >= 30) {

   // Check if reset already done this month
   $check_reset = mysqli_query($conn, "
      SELECT setting_value 
      FROM system_settings 
      WHERE setting_key = 'last_access_reset_month'
   ");

   $last_reset_month = "";

   if ($check_reset && mysqli_num_rows($check_reset) > 0) {
      $reset_row = mysqli_fetch_assoc($check_reset);
      $last_reset_month = $reset_row['setting_value'];
   }

   // If not reset this month, reset all approved access
   if ($last_reset_month != $current_month) {

      mysqli_query($conn, "
         UPDATE access_requests 
         SET status = 'unpaid'
         WHERE status = 'approved'
      ");

      // Save this month as reset done
      mysqli_query($conn, "
         INSERT INTO system_settings (setting_key, setting_value)
         VALUES ('last_access_reset_month', '$current_month')
         ON DUPLICATE KEY UPDATE setting_value = '$current_month'
      ");
   }
}
?>