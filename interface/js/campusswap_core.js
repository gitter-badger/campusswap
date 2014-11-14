function likeItem(likeId){
    jQuery.ajax({
        url: cswap_url + "modules/like_item.php?id=" + likeId,
        success: function(html){
            jQuery('div#likeButton' + likeId).hide();
            jQuery('div#you_like_' + likeId).show();
        }
    });
    return false;
}

function viewItem(id){
    jQuery.ajax({
        url: cswap_url + "viewItem.php?id=" + id
    })
}

function validate(){
    var description=document.forms["post"]["description"].value.length;
    var title=document.forms["post"]["name"].value.length;
    var price=document.forms["post"]["price"].value;
    var disclaimer = document.forms["post"]["disclaimer"].checked;


    if(description > 2300){
        alert("Your description must be less than 2300 characters, you have " + description + " characters currently");
        return false;
    }

    if(title > 50){
        alert("Your title must be less than 50 characters, you have " + title + " characters currently");
        return false;
    }

    if(isNaN(price)){
        alert("The price you entered " + price + decodeURI(" is not a number, try again. (remove comma's and any sombols IE:$%#*&@)"));
        return false;
    }

    if(price.length > 6){
        alert("The price you entered (" + price + ") is too large, let's be realistic now");
        return false;
    }

    if(disclaimer == false){
        alert("You need to agree to the terms of use (displayed to the right), to post things on College Hustler");
        return false;
    }

}

function post(post){
    Effect.toggle('ROW-' + post, 'slide');
}

function setCookie(cookieName,cookieValue,nDays) {
    var today = new Date();
    var expire = new Date();
    if (nDays==null || nDays==0) nDays=1;
    expire.setTime(today.getTime() + 3600000*24*nDays);
    document.cookie = cookieName+"="+escape(cookieValue)
    + ";expires="+expire.toGMTString();
}