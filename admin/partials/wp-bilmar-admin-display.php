<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.webwave.ch
 * @since      1.0.0
 *
 * @package    Wp_bilmar
 * @subpackage Wp_bilmar/admin/partials
 */
 global $wpdb;
 $downloadlink ="";

function format_size($size) {
	$sizes = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
	if ($size == 0) { return('n/a'); } else {
	return (round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizes[$i]); }
}

if( isset($_POST["submit"]) ) {
	$files = $_POST["file"];
 	//ZIP File erstellen
	$zip = new ZipArchive();
	$date = date("m-d-y_h_i_s");
	$filename = dirname(dirname( plugin_dir_path(__FILE__) ))."/archives/".$date.".zip";
	$filename = removeBackSlashes($filename);

	if ( $zip->open($filename, ZipArchive::CREATE) !== TRUE ) {
	    exit("cannot open <$filename>\n");
	}
	
	$zip->addEmptyDir('test');

	/*foreach($files as $file){
		$filepath = get_attached_file( $file );
		$zip->addFile($filepath, basename($filepath));
	}*/
	$zip->close();

	// $downloadlink =  plugins_url()."/media-select-bulk-downloader/archives/".$date.".zip";
	// $downloadlink = ABSPATH."wp-content/plugins/media-select-bulk-downloader/archives/".$date.".zip";
	$downloadlink =  site_url()."/?download=true&filename=".$date.".zip";
}
	 ?>
<form method="post" action="upload.php?page=wp-bilmar-selector"> 
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

<h2><?php echo esc_html(get_admin_page_title()); ?></h2>

<?php if(isset($_POST["submit"])){ ?>
	<div class="notice notice-info inline">
		<h4 style="margin: 10px 0"><?php echo "Creating Archive...";?></h4>
		<div id="loading_info" class="spinner is-active" style="height: 30px; float:none;width:auto;padding: 0;background-position:0px 0;padding-left: 30px;padding; top: 2px; margin-left: 0px;">Adding Files to Archive (<font id="files_now">0</font>/<font id="files_total"><?php echo count($files); ?></font>)</div>
		<p id="download_btn" style="display: none" ><a href="?page=wp-bilmar-downloader" class="button-primary">View Archive (<font id="zipfilesize"></font>)</a></p>
	</div>
<?php } ?>

    <?php
		//Anzeige aller Medien:
		$medien = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE post_type='attachment' ORDER BY post_date DESC" ); 
		?>
		<?php 
		echo "<table class='wp-list-table widefat fixed striped media'>";
		echo "<th style='width: 5%; margin: 0'><input id='checkAll' style='margin: 0' type='checkbox'></th>";
		echo '<th style="width: 50%">File</th>';
		echo "<th>Size</th>";
		echo "<th>Filetype</th>";
		foreach ($medien as $media){
			
			$filetype = wp_check_filetype($media->guid);
			?>
			<tr class="bilmar_tr" id="<?php echo $media->ID;?>">
				<td><input class="bilmar_checkbox" id="cb_<?php echo $media->ID;?>" name="file[]" value="<?php echo $media->ID;?>" type="checkbox"></td>
				<td><div style="width: 60px; display: inline-block"><?php 
				if(strpos($filetype["type"], "image") !== false){
					echo wp_get_attachment_image( $media->ID, array('60', '60'), "", array( "class" => "img-responsive") );
				}	 
				else{
					?>
					<img src="<?php echo get_site_url();?>/wp-includes/images/media/document.png">
					<?php 
				}
				?>
				</div>
				<div style="width: 80%; display: inline-block; vertical-align: top">
					<p style="margin-bottom: 0; font-weight: bold;vertical-align: top;margin-left: 10px;">
					<?php 
					echo basename($media->guid); 
					?>
					</p>
					<p style=" font-size: 11px; color: gray;vertical-align: top;margin-left: 10px;">
					<?php 
					echo $media->guid; 
					?>
					</p>
				</div>
				</td>
				<td><?php  echo format_size(filesize(get_attached_file($media->ID))); ?></td>
				<td><?php  echo $filetype["type"]; ?></td>
			</tr>
			<?php 
		}
		echo "</table>"
		?>
</div>
<div class="wrap" id="footer">
	<input id="createArchiveBtn" class="button-primary" disabled="disabled" type="submit" name="submit" value="<?php esc_attr_e( 'Create Archive' ); ?>" />
</div>
</form>
<script>
<?php 
// function getAbsolutePath( $attachment_id ) {
// 	$file = get_post_meta( $attachment_id, '_wp_attached_file', true );
// 	// If the file is relative, prepend upload dir.
//     if ( $file && 0 !== strpos( $file, '/' ) && ! preg_match( '|^.:\\\|', $file ) ) {
//         $uploads = wp_get_upload_dir();
//         if ( false === $uploads['error'] ) {
//             $file = get_home_path().'/wp-content/uploads'.$uploads['subdir'].'/'.$file;
//         }
//     }
//     return $file;
// }
?>
<?php if(isset($_POST["submit"])) { ?>
	jQuery(document).ready(function() {				
		// var files_now = 0;
		// var files_total = <?php echo count($files);?>;
		// /** In windows the absolute path has back slash and to avoid that I am ignoring use of get_attached_file function
		// **/
		// var files = [<?php 
		// 	foreach($files as $file){
		// 		echo '"';
		// 		// echo getAbsolutePath( $file );
		// 		echo removeBackSlashes( get_attached_file( $file ) );
		// 		echo '", ';
		// 	}
		// ?>];

		// function loopfiles(){
		// 	jQuery.ajax({
		// 	  method: "POST",
		// 	  // url: "<?php //echo plugins_url()."/media-select-bulk-downloader/admin"; ?>/addfileToArchive.php",
		// 	  url: ajaxurl,
		// 	  data: { 
		// 	  	action: 'add_files_to_archive', 
		// 	  	zipfile: "<?php echo $filename;?>", 
		// 	  	file: $files
		// 	  }
		// 	})
		// 	.always(function( msg ) {
		// 		files_now++;
		// 		jQuery("#files_now").html(files_now);
		// 		if (files_now == files_total){
		// 			jQuery("#zipfilesize").html(msg);
		// 			jQuery("#loading_info").slideUp();
		// 			jQuery("#download_btn").slideDown();		
		// 		}
		// 		else{
		// 			loopfiles()
		// 		}
		// 	});
		// }
		// if (files_now != files_total){
		// 	loopfiles();	
		// }

		jQuery.ajax({
			method: "POST",
			url: "<?php echo admin_url('admin-ajax.php') ?>",
			data: { 
				action: 'add_files_to_archive', 
				zipfile: "<?php echo $filename;?>", 
				attachment_ids: <?php echo json_encode($files) ?>
			}
		})
		.done(function(response) {
			jQuery("#zipfilesize").html(response);
			jQuery("#loading_info").slideUp();
			jQuery("#download_btn").slideDown();	
			console.log(response, "response")
			location.href = "?page=wp-bilmar-downloader"
		})
	});	
<?php } ?>
	  jQuery(document).ready(function() {
			/*jQuery(".bilmar_tr").on("click", function(){
				var id = jQuery(this).attr("id");
				if(jQuery('#cb_'+id).is(':checked') == true){
					jQuery('#cb_'+id).attr('checked', false);
				}
				else{
					jQuery('#cb_'+id).attr('checked', true);
				}
				if(jQuery( ".bilmar_checkbox:checked" ).length > 0){
					jQuery("#createArchiveBtn").prop('disabled', false);
				}
				else{
					jQuery("#createArchiveBtn").prop('disabled', true);
				}
			});*/
			
			jQuery("#checkAll").on("click", function(){
				if(jQuery('#checkAll').is(':checked') == true){
					jQuery('.bilmar_checkbox').attr('checked', true);
				}
				else{
					jQuery('.bilmar_checkbox').attr('checked', false);
				}
				if(jQuery( ".bilmar_checkbox:checked" ).length > 0){
					jQuery("#createArchiveBtn").prop('disabled', false);
				}
				else{
					jQuery("#createArchiveBtn").prop('disabled', true);
				}
			});
			jQuery(".bilmar_checkbox").on("click", function(){
				/*if(jQuery(this).is(':checked') == true){
					jQuery(this).attr('checked', false);
				}
				else{
					jQuery(this).attr('checked', true);
				}*/
				if(jQuery( ".bilmar_checkbox:checked" ).length > 0){
					jQuery("#createArchiveBtn").prop('disabled', false);
				}
				else{
					jQuery("#createArchiveBtn").prop('disabled', true);
				}
			});
	  });
</script>