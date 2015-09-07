<?php  ?>
<p class="text2_span8 text-left">WHAT TO SEE</p>
				<table class="table content_table">
					<tbody>
					<?php 
					$i = 1;
					foreach ($displayData->dests as $key=>$dest) { 
					if ($i == 1) {
						echo '<tr>';
					}
					?>
					<td><?php echo $dest->title; ?></td>
					<?php 
					if ($i%4 == 0 && $i< count($displayData->dests)) {
						echo '</tr><tr>';
					}
					if ($i == count($displayData->dests) && count($displayData->dests) > 4) {
						echo '</tr>';
					}
					$i++;
					?>
						
					<?php } ?>
						
					</tbody>
				</table>