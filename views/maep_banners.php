<?php
/**
 * Created by PhpStorm.
 * User: Евгений Работа
 * Date: 09.11.14
 * Time: 14:52
 */

function maep_banners()
{
    if (isset($_REQUEST['save_set_ban']))
    {
        $option = new MaepCore();
        $option->SetOption(array(
            'b_id_cat' => trim($_REQUEST['id_cat']),
            'b_key' => trim($_REQUEST['keywords']),
            'b_width' => trim($_REQUEST['width']),
            'b_height' => trim($_REQUEST['height']),
            'scroll' => trim($_REQUEST['scroll'])
            )
        );
    }

    ?>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 settings-banner">
        <form method="post">
            <label><h3>Id Category</h3><input type="text" class="form-control" name="id_cat" value="<?php echo get_option('b_id_cat') ?>" placeholder="Id categories"></label>
            <label><h3>Keywords</h3><input type="text" class="form-control" name="keywords" value="<?php echo get_option('b_key') ?>" placeholder="Keywords"></label>
            <label><h3>Width</h3><input type="text" class="form-control" name="width" value="<?php echo get_option('b_width') ?>" placeholder="Width"></label>
            <label><h3>Height</h3><input type="text" class="form-control" name="height" value="<?php echo get_option('b_height') ?>" placeholder="Height"></label>
            <label><h3>Auto Scroll <input type="checkbox" class="form-control" <?php checked( get_option('scroll'), 'y'); ?> value="y" name="scroll" placeholder="Auto Scroll"></h3></label>
            <button type="submit" class="btn btn-primary save_set_ban"  name="save_set_ban">Generate</button>

         </form>
        <p><a id="view_ids">View id categories</a></p>
        <div id="cat_result"></div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 window-ban">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">View Banner</h3>
            </div>
            <div class="panel-body">

               <?php

               Banners(get_option('b_id_cat'), get_option('b_key'), get_option('b_width'), get_option('b_height'), get_option('scroll') );
               ?>

            </div>
        </div>
        <h3>Code banner</h3>
        <textarea  id="code_banner"><?php  Banners(get_option('b_id_cat'), get_option('b_key'), get_option('b_width'), get_option('b_height'), get_option('scroll') );  ?></textarea>
    </div>
<?}
