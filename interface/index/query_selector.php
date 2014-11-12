<div class="row box selector">
    <div class="col-lg-3" style="margin-right: 120px">
	<h4 class="muted"><i class="fa fa-university fa-lg"></i>&nbsp;Select a University</h4>
	<form name="domainForm">
            <?php $selectName = '<select multiple="multiple" width="350" style="width: 350px" size=6 name="domain" OnChange="location.href=domainForm.domain.options[selectedIndex].value">'; ?>
            <?= $selectName ?>
            <option value="">Select a College</option>
            
            <?php
            $base_url = URL . 'index.php?';
            
            $all_college_entity =  'college=all';
            if($search){
                $search_entity = '&search=' . urlencode($search);
            } else {
                $search_entity = '';
            }
            if($college=='all'){ $all_selected = 'SELECTED'; } else { $all_selected = ''; }
            ?>
            
            <option <?= $all_selected ?> value="<?= $base_url . htmlentities($all_college_entity . $search_entity) ?>">All Colleges</option>
            <?php 
            $counter = 1;

            foreach($all_domains as $domain_rows){
                $domain = $domain_rows->getDomain();
                $college_name = $domain_rows->getName();
                
                if($domain == $college){
                        $selected = 'SELECTED';
                } else {
                        $selected = '';
                }
                $domain_entity = 'college=' . urlencode($domain);
                $domain_url = $base_url . htmlentities($domain_entity . $search_entity);
                echo "<option " . $selected . " value='" . $domain_url . "'>";
                echo $college_name . " (" . $domain  . ")</option>";

                $counter++;
            } ?>

            </select>
	</form>
    </div>

    <?php

    //TODO: Clean this shit up and break it out to a function or class

    if($sort=='none'){
        $sortNone = 'SELECTED';
    } else {
        $sortNone = '';
    }

    if($sort=='priceLowHigh'){
            $lowHigh = 'SELECTED';
    } else {
            $lowHigh = '';
    }

    if($sort=='priceHighLow'){
            $highLow = 'SELECTED';
    } else {
            $highLow = '';
    }

    if($sort=='hitsAsc'){
            $hitsAsc = 'SELECTED';
    } else {
            $hitsAsc = '';
    }

    if($sort=='hitsDesc'){
            $hitsDesc = 'SELECTED';
    } else {
            $hitsDesc = '';
    }

    if($sort=='dateDesc'){
        $dateDesc = 'SELECTED';
    } else {
        $dateDesc = '';
    }

    if($sort=='dateAsc'){
        $dateAsc = 'SELECTED';
    } else {
        $dateAsc = '';
    }

    $college_base_url = $base_url . 'college=' . urlencode($college) . $search_entity;
    
    ?>
    <div class="col-lg-3 searchSectionLeftBar">
        <form name="sortForm">
        <h4 class="muted"><i class="fa fa-sort-amount-asc fa-lg"></i>&nbsp;Sort By..</h4>
        <select multiple="multiple" size="6" name="sort"  width="150" style="width: 150px" OnChange="location.href=sortForm.sort.options[selectedIndex].value">
            <option <?php if(!$sort){ echo 'SELECTED'; } ?> value="">Sort by..</option>
            <option <?= $sortNone ?> value="<?= $college_base_url . '&sort=none' ?>">None</option>
            <option <?= $lowHigh ?> value="<?= $college_base_url . '&sort=priceLowHigh' ?>">Price Low to High</option>
            <option <?=  $highLow ?> value="<?= $college_base_url . '&sort=priceHighLow' ?>">Price High to Low</option>
            <option <?=  $hitsAsc ?> value="<?= $college_base_url . '&sort=hitsAsc' ?>">Likes Low to High</option>
            <option <?=  $hitsDesc ?> value="<?= $college_base_url . '&sort=hitsDesc' ?>">Likes High to Low</option>
            <option <?=  $dateDesc ?> value="<?= $college_base_url . '&sort=dateDesc' ?>">Date Descending</option>
            <option <?=  $dateAsc ?> value="<?= $college_base_url . '&sort=dateAsc' ?>">Date Ascending</option>
        </select>
        </form>
    </div>

    <div class="col-lg-3 searchSectionLeftBar">
        <h4 class="muted"><i class="fa fa-search fa-lg"></i>&nbsp;Item Search</h4>
        <form name="searchForm" class="row form-search" action="index.php" method="get">
            <?php if($search){ ?>
                <b>Search "<?= $search ?>"</b
                <a href="<?= $college_base_url ?>'"> <small>(clear search)</small></a><br />
            <?php } ?>
            <input class="search-query input-large" placeholder=".input-medium" type="text" name="search" value="search">
            <input type="hidden" name="college" value="<?php echo $college; ?>">
            <input type="submit" class="btn btn-small" value="Search" />
        </form>
        <br /><br /><br /><br />
    </div>

    
</div>