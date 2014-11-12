<b><a href="<?= URL ?>">Home</a></b>

<?php
//TODO: Implement buttons here, with the post count badges and x buttons to close.
// A new button for each query entity: search, college and sort
// http://getbootstrap.com/components/#badges
if($college == 'all'){
    echo '<b> > <a href="' . Config::get('url') . '"> All Colleges</a></b>';
    $title = 'All Colleges - <font class="muted">Most Recent</font>';
    if($search){
        echo '<b> > Search "' . $search . '"</b>';
        echo  '<a href="' . URL . '"> <small>(clear search)</small></a>';
        $title = $title . '<font class="muted"> - Search: "' . $search . '"</font>';
    }
} else {
    echo '<b> > <a href="' . URL . '?college=' . $college . '">' . DomainsDAO::getCollegeName($college) . '</a> </b>';
    $title = DomainsDAO::getCollegeName($college);

    if(!Parser::isFalse($search)){
        echo '<b> > Search "' . $search . '"</b>';
        echo  '<b><a href="' . URL . '?college=' . $college . '"> <small>(clear search)</small></a></b>';
        $title = $title . '<font class="muted"> - Search: "' . $search . '"</font>';
    }
    if(!Parser::isFalse($sort)){
        echo '<b>';
        $navSort = $PostsDAO->getSortText($sort);
        $navSort = ' > ' . $navSort;

        echo $navSort;

        $title = $title . ' <font class="muted">' . $navSort . '</font>';
        echo '</b>';
    }
}

if($item){
    echo '<b> > Item id ' . $item . '</b>';
} else if($total_count > 0){
    echo '<b> > ' . $total_count . ' items found</b>';
} else {
    echo '<b> > 0 items found </b>';
}
?>