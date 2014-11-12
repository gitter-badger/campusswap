var cswap_count = 0;


jQuery(window).scroll(function(){

    if(cswap_debug) { console.log("DEBUG MODE ON - entering scroll function"); }

    //Get the "Loading spinner" to cover the top Login navbar before it loads, and all other slow JS Libs
    //$('<div class=loadingDiv>loading...</div>').prependTo(document.body);

    if(jQuery(window).scrollTop() === jQuery(document).height() - jQuery(window).height()){

        jQuery('div#loaded').html('Content being loaded...');

        jQuery('div#loadmoreajaxloader').show();

        if(cswap_pages > 0){

            if(cswap_pages > cswap_count){

                var scroll_url = cswap_url + "scroll.php?college=" + cswap_college + "&search=" + cswap_search +
                        "&sort=" + cswap_sort + "&first=" + cswap_first;
                if(cswap_debug) { console.log(scroll_url); }

                jQuery.ajax({
                    url: scroll_url,
                    success: function(html){

                        if(html){
                            jQuery("#postswrapper").append(html);
                            jQuery('div#loadmoreajaxloader').hide();
                            cswap_count++;
                        }else{
                            jQuery('div#loadmoreajaxloader').html('<center>No more posts to show.</center>');
                        }
                    }

                });
                cswap_first = cswap_first + 10;
                if(cswap_debug) {
                    console.log("CSWAP_FIRST = " + cswap_first);
                    console.log("CSWAP_COUNT = " + cswap_count);
                    console.log("CSWAP_PAGES = " + cswap_pages);
                }
            } else {
                    jQuery('div#loadmoreajaxloader').html('<center>No more posts to show.</center>');
            }
        } else {
                jQuery('div#loadmoreajaxloader').html('<center>No more posts to show.</center>');
        }
    }
});