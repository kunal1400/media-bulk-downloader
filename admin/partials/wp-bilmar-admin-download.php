<?php 
$archiveFolderPath = mbd_remove_back_slashes( MBD_BILMAR_ABSOLUTE_FILE_PATH ).'archives';
$files = list_files( $archiveFolderPath );
?>
<div class="wrap">
	<h2>All Archives</h2>
	<table class='wp-list-table widefat fixed striped media'>
		<thead>
			<tr>
				<td width="100">Sno</td>
				<td>File Name</td>
				<td>size</td>
				<td># of files</td>
				<td>Action</td>
			</tr>
		</thead>
		<tbody>
			<?php
				if ( count($files) > 0 ) {
					$za = new ZipArchive;
					foreach ($files as $i => $file) {
						$fileType = wp_check_filetype( $file );
						$numberOfFilesInZip = "Not a zip file";

						// If filetype is zip then do zip functions
						if ( $fileType['ext'] == 'zip' || $fileType['type'] == 'application/zip' ) {
							$za->open($file);
							$numberOfFilesInZip = $za->numFiles;
						}
						
						$filename = wp_basename( $file );
						?>
						<tr>
							<td><?php echo $i+1 ?></td>
							<td><?php echo $filename ?></td>
							<td><?php echo mbd_format_size_units( filesize($file) ) ?></td>
							<td><?php echo $numberOfFilesInZip ?> Files</td>
							<td>
								<a class="button button-primary" target="_blank" href="<?php echo MBD_BILMAR_RELATIVE_ARCHIVES_PATH.$filename ?>">Download</a>
								<a class="button button-warning" href="<?php echo '?page=wp-bilmar-downloader&deletefile='.$filename ?>">Delete</a>
							</td>
						</tr>
						<?php
						if ( $fileType['ext'] == 'zip' || $fileType['type'] == 'application/zip' ) {
							$za->close(); 
						}
					}
				}
				else {
					echo "<tr><td align='center' colspan='4'>No Archives Found</td></tr>";
				}
			?>
		</tbody>
	</table>
</div>