jQuery(document).ready(function($){
    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function(e) {
        tab_active_index= $(e.target).parent('li').index(); // activated tab
        if(tab_active_index==1)
        {
            $('#customlist_chzn').css({
                'width':'inherit'
            });
        }

    })
});
