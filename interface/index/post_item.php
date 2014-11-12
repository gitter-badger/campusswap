<div class="box">

    <a href="#" onclick="Effect.toggle('postSomething', 'blind'); return false;">
        <i class="fa fa-shopping-cart fa-lg"></i>
        <b>Post An Item</b>
    </a>
</div>

<div class="postSomething row-fluid" id="postSomething" style="display:none">
    <div class="span4">
        <form enctype="multipart/form-data" name="post" onsubmit="return validate()" action="<?= URL ?>modules/post_item.php" method="post">
            <input class="tall_text_box input-group input-group-md" type="text" name="name" value="Item Name" /><br />
            <input class="tall_text_box input-group input-group-md" type="text" name="price" value="Price $$" /><br />
            <textarea class="form-control" name="description" rows="7" class="span12">Item Description, 2300 characters maximum</textarea><br />

            <b>Picture:</b>
            <input type="file" name="file" id="file" />
            <small>
                - No Larger than 1MB<br />
                - Must be .jpg, .jpeg, .png or .gif only
            </small>

            <div class="checkbox">
                <label>
                  <input type="checkbox" name="disclaimer" /><small>I have read and agree to the terms of use (to the right)</small><br />
                </label>
            </div>

            <input type="hidden" name="postItem" value="TRUE">
            <input type="hidden" name="user" value="<?= $liUser ?>">
            <input type="hidden" name="domain" value="<?= $liDomain ?>">
            <input class="btn btn-success" type="submit" value="Post Item" />
        </form>
    </div>
    <div class="span2">
        <iframe src="<?= URL ?>interface/index/post_disclaimer.php" frameborder="0" width="750" height="300">
              <p>Your browser does not support iframes.</p>
        </iframe>
    </div>
</div>