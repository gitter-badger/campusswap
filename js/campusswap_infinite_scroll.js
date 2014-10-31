var count = 0;

if(pages == 0){
    if(total <= 39){
        pages = 1;
    }
}

var first = 40;

jQuery(window).scroll(function(){

    if(jQuery(window).scrollTop() === jQuery(document).height() - jQuery(window).height()){

        jQuery('div#loaded').html('Content being loaded...');

        jQuery('div#loadmoreajaxloader').show();

        if(pages > 0){

            if(pages > count){

                jQuery.ajax({
                    url: "scroll.php?college=" + college + "&search=" + search + 
                            "&sort=" + sort + "&first=" + first,
                    success: function(html){

                        if(html){
                            jQuery("#postswrapper").append(html);
                            jQuery('div#loadmoreajaxloader').hide();
                            count++;
                        }else{
                            jQuery('div#loadmoreajaxloader').html('<center>No more posts to show.</center>');
                        }
                    }

                });
            first = first + 10;
            } else {
                    jQuery('div#loadmoreajaxloader').html('<center>No more posts to show.</center>');
            }
        } else {
                jQuery('div#loadmoreajaxloader').html('<center>No more posts to show.</center>');
        }
    }
});