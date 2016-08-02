<?php

/******************************
* data processing
******************************/

// this function saves the data
add_action("wp_ajax_my_ajax", "my_ajax");
add_action("wp_ajax_nopriv_my_ajax", "my_ajax");

function my_ajax() {

   /*if ( !wp_verify_nonce( $_REQUEST['nonce'], "my_user_vote_nonce")) {
      exit("No naughty business please");
   }*/
   $sku=$_REQUEST['sku'];
   $pur=$_REQUEST['pur'];
   $sold=$_REQUEST['sold'];
   //echo $sold.''.$sku.''.$pur;
   global $wpdb;
   $table = $wpdb->prefix."postmeta";
   $result= array();
   if($pur!='' or $sold!='')
   {
   //$liveposts = $wpdb->get_results("SELECT * FROM $table");
   $liveposts = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $table where meta_key=%s and meta_value=%s",'_sku',$sku));
   $num=$wpdb->num_rows;
       if($num>0){
             foreach ($liveposts as $livepost)
             {
              $p1=$livepost->post_id;
              $stok=$wpdb->get_results("SELECT meta_value FROM $table where meta_key='_stock' and post_id='$p1'");
              foreach ($stok as $quantity) {
                 $q=$quantity->meta_value;
                  if ($pur=='' and $sold!='') {
                    if($sold<$q)
                    {
                    $q=$q-$sold;
                    $wpdb->get_results("UPDATE $table SET `meta_value` =$q WHERE `post_id` ='$p1' and `meta_key`='_stock'");
                    $result['result']="<div class='result'>New Quantity for ".$sku." has been changed to ".$q."</div>";
                    }
                    else
                    $result['result']="<div class='err'>Present Quantity is less then given value.</div>";
                 }
                 elseif ($sold=='' and $pur!='') {
                  
                  $q=$q+$pur;
                  $wpdb->get_results("UPDATE $table SET `meta_value` =$q WHERE `post_id` ='$p1' and `meta_key`='_stock'");
                  $result['result']="<div class='result'>New Quantity for ".$sku." has been changed to ".$q."</div>";
                  
                 }
               //

              }
             }
          }
          else $result['result']="<div class='err'>No products found With SKU ".$sku."</div>"; 
     }
     else
    $result['result']="<div class='err'>Purchased or Sold Quantity Must be given</div>";
    echo __($result['result'],'aia');
}
?>