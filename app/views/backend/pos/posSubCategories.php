<a class="sub-categories sub-category-0 sub-cat-active" onclick="loadCategoryItems('All','0','All','sub_cat');" href="javascript:void(0);" title="All">
    <span class="all-new1 m-0 p-0">All </span>
    <?php /* <img src="<?php echo base_url();?>uploads/no-image-mobile.png" title="All" style="width:90px;height:50px;visibility: hidden;text-align:center;" alt="...">  */ ?>
    
</a>
                                            
<?php
    $j=1;
    foreach($sub_category as $row)
    {
        #$categoryId = $row["list_type_value_id"];
        $categoryId = $row["category_id"];
        $categoryCode = $row["list_code"];
        ?>
        <a class="sub-categories sub-category-<?php echo $j; ?>" onclick="loadCategoryItems('<?php echo $categoryCode;?>','<?php echo $j;?>','<?php echo $categoryId;?>','sub_cat');" href="javascript:void(0);" title="<?php echo ucfirst($row["list_value"]); ?>">
            <?php 
                /* $url = "uploads/lov_images/".$row['list_type_value_id'].".png";
                if(file_exists($url))
                { */
                    ?>
                    <span class="all-new1 m-0 p-0"><?php echo ucfirst($row["list_value"]); ?></span>
                    <?php /* <img src="<?php echo base_url().$url;?>" style="width:90px;height:50px;visibility: hidden;text-align:center;" alt="..."> */ ?>
                    <?php 
                //}
            ?>
        </a>
        <?php
        $j++;
    }
?>

<style>
a.sub-categories {
    min-width: 100px;
    background: #dafcd2;
    margin-bottom: 10px;
    border: 1px solid #b7e1ae;
}
a.sub-categories.sub-cat-active {
    background: #00a651;
    color: #fff!important;
    border: 1px solid #00a651;
}
a.sub-categories.sub-cat-active span {
    color: #fff!important;
}
div.scrollmenu a:hover {
    background: #00a651;
    color: #fff!important;
}
</style>