<?php 
    if(isset($item_list) && count($item_list) > 0)
    {
        foreach($item_list as $row)
        {
            ?>
            <div class="col-6 col-md-2 col-sm-6 col-xs-2 col-lg-2 pr-0">
               <a href="javascript:void(0);" onclick="selectPosItems('<?php echo $row['item_id'];?>',1);" title="<?php echo ucfirst($row["item_name"]); ?>">
                    <div class="pos-items">
                        <div class="text-center">  
                            <?php
                                $url = "uploads/products/".$row["item_id"].".png";
                                if(file_exists($url))
                                {
                                    ?>
                                    <img class="card-img-top img-responsive pro-image-pos" src="<?php echo base_url().$url;?>" alt="<?php echo ucfirst($row["item_name"]); ?>"> 
                                    <?php 
                                }
                                else
                                {
                                    ?>
                                    <img class="card-img-top img-responsive pro-image-pos" style="height:80% !important;width:75% !important;" src="<?php echo base_url();?>uploads/no-image-mobile.png" alt="<?php echo ucfirst($row["item_name"]); ?>"> 
                                    <?php 
                                }
                            ?>
                            <p class="card-title py-1 pos-item-name"><?php echo ucfirst($row["item_name"]); ?></p> 
                        </div>
                    </div> 
                </a> 
            </div>
            <?php 
        } 
    }
    else
    {
        ?>
        <span class="no-item">
            <img src="<?php echo base_url();?>uploads/no_items.jpg" style="width:400px;height:300px;">
        </span>
        <?php
    }
?>  