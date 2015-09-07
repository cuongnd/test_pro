<div class="row-fluid">
			<div class="span12">    
               <?php 
                echo $this->loadTemplate('itinerary_head');
               ?>
                
                <?php 
                $layout = new JLayoutFile('itinerary_detail', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
                $html = $layout->render($this->tour);
                echo $html;
                ?>      
			</div>
		</div>  