    function validateDate(startDateId, endDateId){
        var startDate   = jQuery('#'+startDateId).val();
        var endDate     = jQuery('#'+endDateId).val();  
        var check       = true; 
        
        var day=1000*60*60*24;
        var date1= convertDateFormat(startDate);
        var date2= convertDateFormat(endDate);  
        
        date1= new Date(date1);
        date2= new Date(date2);

        var diff=Math.abs(date2.getTime()-date1.getTime())
        var diffdays=Math.round(diff/day)

      
            if(!startDate){
                alert('From date is required field');
                check = false;
            }else if(!endDate){
                alert('To date is required field');
                check = false;
            }else if(new Date(startDate) > new Date(endDate)){
                alert('From date should be lesser than To date');
                check = false;
            }else if(diffdays > 59){
                alert('Maximum time window is 60 days');
                check = false;
            }                     
        return check;
    }
    
        function convertDateFormat(strDate)
        {
            if (trim(strDate) == '') return '';
            return strDate.replace(/-/g, "/");
        }

