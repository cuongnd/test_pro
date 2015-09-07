                        <div class="div4_col_left">   
                            <div class="content_overview">
                                <div class="overview_nav_tabs">
                                    <div class="div1_overview_nav_tabs text-left">
                                        <p style="color:#333366; font-size:18px;font-weight:bold;">PASSION OF SOUTH-EASTERN ASIA  </p>
                                        <div class="row-fluid">
                                            <div class="span8">
                                                
                                                <p class="text_div1_overview">Trip code : PSEA 120,  private departures</p>
                                                <p class="text_div1_overview" style="padding-bottom:10px;">Country visited: Vietnam, Laos, Cambodia, Thailand  </p>
                                                <span class="content_img">
                                                    <?php echo $this->imagesAct; ?>
                                                </span>
                                            </div>
                                            <div class="span4">
                                                <p class="text2_div1_overview">Tour length: 20 days  and 19 nights Minum price from US$ 1520/person </p>
                                                <button type="button" class="btn button_div1_overview" onclick="jQuery('a[href=#date_price]').tab('show');">CHECK THE AVAILABILITY</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="div2_overview_nav_tabs text-left">
                                        <p class="text-left" style="color:#cc0000; text-transform:uppercase; font-weight:bold; font-size:14px;padding-top:10px; padding-left:10px;">DEPARTURES AND PRICES  </p>
                                        <div class="content_div2_overiew_nav_tabs" style="text-align: justify;">
                                            <p style="font-weight:bold;">The trip document tells you everything you need to know about your holiday.  We have prepared specific notes for special departures, which helps to make the perfect choice of  the trip and departure dates for your dream holiday.  You can download this trip information as a PDF.  You should make sure that you have in hand a final copy of your trip document  in together with the destination userful information a couple of days prior to your travel date.</p>
                                        </div>
                                    
                                    </div>
                                </div>
                            </div>
                        
                        
                        </div>
                        <div class="div5_col_left">
                            <div class="row-fluid">
                                <div class="span7">
                                    <p class="text_div5_1 text-left">ICONS EXPLAINED</p>
                                        <div class="content_text_div5_1">
                                            <div class="text_div5_2 text-left">
                                                <p class="group">The group trips average about 12 travellers per departure, depending on the adventure. The maximum is usually no more than 16..</p>
                                            </div>
                                        </div>
                                        <div class="content_text_div5_1">
                                            <div class="text_div5_2 text-left">
                                                <p class="group_2">These are some light walking and hiking combining with the long drive distance, local train,... but it is suitable for most fitness levels. Nothing too challenging </p>
                                            </div>
                                            </div>
                                            <div class="content_text_div5_1">
                                                <div class="text_div5_2 text-left">
                                                    <p class="group_3">Designed for maximum variety, these trips are geared towards travellers searching for a healthy mix of active exploration, uncommon landscapes, amazing wildlife and local cultures
 </p>                                            </div>
                                            </div>
                                        </div>
                                        <div class="span5">
                                            <div class="span5_div5_col_left_1 center">
                                                <p class="text_1">DOWNLOAD DOCUMENT</p>
                                                <p style="text-transform:uppercase; color:#cc0000; font-size:24px;font-weight:bold;">click</p>
                                            </div>
                                        <div class="span5_div5_col_left_2 text-left">
                                        <p>Recommended Readings</p>
                                        <ul style="list-style:none;">
                                            <li>
                                                <a href="#">1. Story of Vietnam</a>
                                            </li>
                                            <li>
                                                <a href="#">2. Learn Thai language</a>
                                            </li>
                                            <li>
                                                <a href="#">3. Angkor History</a>
                                            </li>
                                            <li>
                                                <a href="#">4. Laos Culture</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="span5_div5_col_left_3 text-left">
                                        <p> Thailand  Travel Guide</p>
                                    </div>
                                    <div class="span5_div5_col_left_4 text-left">
                                        <p> Laos Travel Guide</p>
                                    </div>
                                    <div class="span5_div5_col_left_4 text-left">
                                        <p> Laos Travel Guide</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="div6_col_left">
                            <p class="text-left">FREQUENT ASK QUESTIONS</p>
                            <?php if(count($this->faqs)>0){
                                for($i=0;$i<count($this->faqs);$i++)
                                {
                                  $faq = $this->faqs[$i];
                                  ?>
                                        <div class="div_questions">
                                            <p class="text-left div"><?php echo $faq->title; ?></p>
                                            <div class="text-left div1" style="text-align: justify;"><?php echo $faq->desc; ?></div>
                                        </div> 
                                  <?php  
                                }
                            }?>      
                        </div>
          
 