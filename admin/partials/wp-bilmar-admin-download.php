<?php 
$archiveFolderPath = removeBackSlashes( BILMAR_ABSOLUTE_FILE_PATH ).'archives';
$files = list_files( $archiveFolderPath );

function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824)
    {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif ($bytes >= 1048576)
    {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif ($bytes >= 1024)
    {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    }
    elseif ($bytes > 1)
    {
        $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1)
    {
        $bytes = $bytes . ' byte';
    }
    else
    {
        $bytes = '0 bytes';
    }

    return $bytes;
}
?>
<div class="wrap">
	<table class='wp-list-table widefat fixed striped media'>
		<thead>
			<tr>
				<td width="100">Sno</td>
				<td>File Name</td>
				<td>size</td>
				<td>Action</td>
			</tr>
		</thead>
		<tbody>
			<?php
				if ( count($files) > 0 ) {
					foreach ($files as $i => $file) { 
						$filename = wp_basename($file);
					?>
						<tr>
							<td><?php echo $i+1 ?></td>
							<td><?php echo $filename ?></td>
							<td><?php echo formatSizeUnits(filesize($file)) ?></td>
							<td>
								<a class="button button-primary" target="_blank" href="<?php echo plugins_url()."/media-select-bulk-download/archives/".$filename ?>">Download</a>
								<a class="button button-warning" href="<?php echo '?page=wp-bilmar-downloader&deletefile='.$filename ?>">Delete</a>
							</td>
						</tr>
					<?php }
				}
				else {
					echo "<tr><td align='center' colspan='4'>No Archives Found</td></tr>";
				}
			?>
		</tbody>
	</table>
</div>