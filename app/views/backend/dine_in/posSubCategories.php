<a class="sub-categories sub-category-0 sub-cat-active" onclick="loadCategoryItems('All','0','All','sub_cat');" href="javascript:void(0);" title="All">
    <img src="<?php echo base_url();?>uploads/no-image-mobile.png" title="All" style="width:90px;height:50px;" alt="..."> 
     <br>
    All
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
                $url = "uploads/lov_images/".$row['list_type_value_id'].".png";
                if(file_exists($url))
                {
                    ?>
                    <img src="<?php echo base_url().$url;?>" style="width:90px;height:50px;" alt="...">
                    <?php 
                }
            ?>
            <br>
            <?php echo ucfirst($row["list_value"]); ?>
        </a>
        <?php
        $j++;
    }
?>

