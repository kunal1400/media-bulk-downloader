<?php 
$archiveFolderPath = removeBackSlashes( BILMAR_ABSOLUTE_FILE_PATH ).'archives';
$files = list_files( $archiveFolderPath );
?>
<div class="wrap">
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
						$filename = wp_basename($file);
						$za->open($file);
						?>
						<tr>
							<td><?php echo $i+1 ?></td>
							<td><?php echo $filename ?></td>
							<td><?php echo formatSizeUnits( filesize($file) ) ?></td>
							<td><?php echo $za->numFiles ?> Files</td>
							<td>
								<a class="button button-primary" target="_blank" href="<?php echo plugins_url()."/media-select-bulk-download/archives/".$filename ?>">Download</a>
								<a class="button button-warning" href="<?php echo '?page=wp-bilmar-downloader&deletefile='.$filename ?>">Delete</a>
							</td>
						</tr>
						<?php
						$za->close(); 
					}
				}
				else {
					echo "<tr><td align='center' colspan='4'>No Archives Found</td></tr>";
				}
			?>
		</tbody>
	</table>
</div>