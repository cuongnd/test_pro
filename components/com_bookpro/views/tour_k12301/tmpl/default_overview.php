<div class="row-fluid">
			<div class="span12">     
               <?php 
                echo $this->loadTemplate('itinerary_head');
               ?>
                
                <?php 
                $layout = new JLayoutFile('itinerary_brief', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
                $html = $layout->render($this->tour);
                echo $html;
                ?>      
			</div>
		</div>  
        
         <div class="div5_col_left overview-footer">
            <div class="row-fluid">
                <div class="span7">
                    <p class="text_div5_1 text-left">WHAT IS INCLUDED IN THIS TOUR ?</p>
                    <?php echo $this->tour->include ?>

                </div>
                <div class="span5">
                    <div class="span5_div5_col_left_1">
                        <p class="text_1">TRAVELLERS RATED THIS TOUR</p>
                        <p class="text_2">9.8/10</p>
                        <p class="text_3">95% of guests would take our tours again</p>
                    </div>
                    <div class="span5_div5_col_left_2 text-left">
                        <p>Download Tour Document</p>
                    </div>
                    <div class="span5_div5_col_left_3 text-left">
                        <p>Frequent Ask Questions</p>
                    </div>
                    <div class="span5_div5_col_left_4 text-left">
                        <p>Vietnam, Laos, Cambodia, Thailand</p>
                    </div>
                </div>
            </div>
        </div>
                  