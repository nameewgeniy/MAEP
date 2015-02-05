<?php
    function viewForm($ids, $name, $data){?>

        <style>
            .width {width: 45%; float: left;}
            .height {width: 45%; float: right;}
            .keyword {clear: both; margin-top: 10px}
            label {padding-bottom: 15px}
            .select { padding: 5px 0}
        </style>
        <p>
            <label for="<?php echo $ids['id_cat'] ?>"><?php _e('Category ID:', 'banner'); ?></label>
            <input type="number" id="<?php echo $ids['id_cat'] ?>" name="<?php echo $name['id_cat'] ?>" value="<?php echo $data['id_cat']; ?>" style="width:100%;" />
            <a href="http://www.isoldwhat.com/getcats/fullcategorytree.php" target="_blank">View categories id</a>
        </p>
        <div class="width" >
            <label for="<?php echo $ids['width'] ?>"><?php _e('Width(px):', 'banner'); ?></label>
            <input id="<?php echo $ids['width'] ?>" type="number" name="<?php echo $name['width'] ?>" value="<?php echo $data['width']; ?>" style="width:100%;" />
        </div>
        <div class="height" >
            <label for="<?php echo $ids['height'] ?>"><?php _e('Height(px):', 'banner'); ?></label>
            <input id="<?php echo $ids['height'] ?>" type="number" name="<?php echo $name['height'] ?>" value="<?php echo $data['height']; ?>" style="width:100%;" />
        </div>
        <div style="clear: both" ></div>
        <div class="keyword">
            <label for="<?php echo $ids['keyword'] ?>"><?php _e('Keyword:', 'banner'); ?></label>
            <input id="<?php echo $ids['keyword'] ?>" type="text" name="<?php echo $name['keyword'] ?>" value="<?php echo $data['keyword']; ?>" style="width:100%;" />
        </div>
        <div class="select">
            <label for="<?php echo $ids['sort'] ?>"><?php _e('Sort by:', 'banner'); ?></label>
            <select name="<?php echo $name['sort'] ?>" id="<?php echo $ids['sort'] ?>" style="width: 100%; display: block">
                <option value="1" <?php selected($data['sort'], 1); ?> >Best Match</option>
                <option value="2" <?php selected($data['sort'], 2); ?>>Items Ending First</option>
                <option value="3" <?php selected($data['sort'], 3); ?>>Newly-Listed Items First</option>
                <option value="4" <?php selected($data['sort'], 4); ?>>Lowest Prices First</option>
                <option value="5" <?php selected($data['sort'], 5); ?>>Highest Prices First</option>
            </select>
        </div>
        <div class="select">
            <label for="<?php echo $ids['autoscroll'] ?>"><?php _e('Auto Scroll:', 'banner'); ?>
                <input type="hidden" name="<?php echo $name['autoscroll'] ?>" value="false"/>
                <input id="<?php echo $ids['autoscroll'] ?>" type="checkbox" <?php checked($data['autoscroll'], 'true'); ?> name="<?php echo $name['autoscroll'] ?>" value="true"  />
            </label>
        </div>
        <div class="select">
            <label for="<?php echo $ids['topseller'] ?>"><?php _e('Only Include Items from Top Rated Sellers  :', 'banner'); ?>
                <input type="hidden" name="<?php echo $name['topseller'] ?>" value="false"/>
                <input id="<?php echo $ids['topseller'] ?>" type="checkbox" name="<?php echo $name['topseller'] ?>" <?php checked($data['topseller'], 'true'); ?> value="false"  />
            </label>
        </div>


<?php }