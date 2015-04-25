<?php
	function maep_settings()
	{
		$option = new MaepCore();
		global $wpdb;
		$table_info = $wpdb->prefix . "maep_products_info";
		if (!get_option('appID'))
			$option->SetOption(array(
					'appID' => 'Evgeniy96-0bc9-460a-aa31-5441f6b3f69',
					'maep_lang' => '0',
					'count' => '20',
					'trackingId' => '5337570417',
					'DEVID' => 'a9f746ec-6af3-4f05-bde0-7a6c172c9871',
					'CertID' => '2e98ac27-4372-498e-ab64-efa617b03453',
					'auto_update_product' => 'check',
					'token' => 'AgAAAA**AQAAAA**aAAAAA**DuIfVA**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6AGmIOmDJOKogidj6x9nY+seQ**8nsCAA**AAMAAA**VnKIClAiKxUqDea8iJL4xNbLudCIHB21oJcIWi+qZ7Dq3r9CWx5dVMGImL0WGS3Kt7uXJIfji0+Eo69CPXTZoCNy4psp50nsWGiTvh9RBsdlIcHbynhyClPrjoB7FMukLhbDTmUHbzYVuyXK6boBZi++UG6iufszIsq4Cer3yGUehth2pR1s9nW+REJZvos5TVAWDqCPF42q9mc6EibjscdBkaksFngzaNNsEBv8V6/LRW9SsbYAPdugK9nAYIDueeoqDrWvxm0iL3gZ2eKiIEm6wjuKum6aDbomuK+I7tVzqT+6PzvddcPGW0QLlJuMuPLGToWQczVN5KSuMkVrq+RYQ76ophEJcVyXFwnD/ZZqrCZ9nfCd8fsZB+EoSu3JMLXaORNr+6wDBfPWKQRv0ST3KpOV/uZUq3NJzAwfBpxA00rsArTK0EadcIAyhDHnkAFgBQYeLOXH/LmbDtduAVnf4e3Ieea/rRBHLz/9WIPVcLCkjG7Mr4JV9wRLkJXlzTiRgtHs+NgvRK26eLcBVQdUCv7bbXWDHaToeNAWIPe5CLGA32OjhLZc4npM4P4fvxfHck3SiRB3CQNUyYY9+Jr+MlFwPRG7dT+J+HsMnwa978569sxS0hdh+sJDtuHK/k9BNhJflwrQ5rucs/mvdLYGzVWn5h/agG9KC1ebBMctyQG48PfFRO6zOiLBAqAaw3e6lvvyZltRmmpQyZrsUccyEOKulxqv1cr3T59rC54bEXDmgGIYbg5kBc07gF4f',
				));
        // сохранение настроек
		if (@$_POST['save_options'])
		{
			$links = $wpdb->get_results("SELECT id FROM {$table_info}");
			if (count($links) > 0 && $_POST['trackingId'] != get_option('trackingId') && get_option('trackingId') != '' && $_POST['trackingId'] != '')
			{
					$result_up_tr = $option::update_track_id(get_option('trackingId'), $_POST['trackingId']);
				
			}
			$option->SetOption(array(
					'appID' => trim($_POST['appID']),
					'maep_lang' => $_POST['lang'],
					'count' => 20 /*trim($_POST['count'])*/,
					'trackingId' => trim($_POST['trackingId']),
					'DEVID' => trim($_POST['DEVID']),
					'CertID' => trim($_POST['CertID']),
					'token' => trim($_POST['token']),
					'auto_update_product' => trim($_POST['auto_update']),
					'update_key' => trim($_POST['up_key']),
				));
			
		}

        // Загрузка новостей
        if (@$_POST['news'])
        {
            load_news();
        }
			
		$AppID = get_option('appID');
		//$count = get_option('count');
        $up_key = get_option('update_key');
		$trackingId = get_option('trackingId');
		$DEVID = get_option('DEVID');
		$CertID = get_option('CertID');
		$token = get_option('token');
		?>
		<div class="panel panel-default general-settings">
            <?php
                if (@$_POST['news'])
                    echo '<div class="alert alert-success" role="alert">All news has been uploaded sucessfully</div>';
                if (@$_POST['save_options'])
                    echo '<div class="alert alert-success" role="alert">Settings save</div>';
                if ($DEVID == '' || $trackingId == '' || $token == '' || $CertID == '' || $AppID == '')
                    echo '<div class="alert alert-warning" role="alert">All fields must be filled</div>';
                if ($result_up_tr)
                    echo '<div class="alert alert-success" role="alert">Tracking Id has been update: ' . $result_up_tr . ' - links</div>';
                elseif(isset($result_up_tr) && !$result_up_tr)
                    echo '<div class="alert alert-danger" role="alert">Tracking Id not has been updated</div>' ;
                var_dump($result_up_tr);
            ?>

			<div class="panel-heading"><h3 class="panel-title">General Settings</h3></div>
			<form method="post">
				<label><h3>Compaign Id</h3><input type="text" name="trackingId" placeholder="trackingId" value='<?php echo $trackingId; ?>' class="trackingId" /></label>
				</br>
				<label>
					<h3>eBay programs</h3>
					<select name="lang" class="lang_e">
						<option <?php selected(get_option('maep_lang'), '0' ); ?> value="0">eBay United States</option>
						<option <?php selected(get_option('maep_lang'), '77' ); ?> value="77">eBay Germany</option>
						<option <?php selected(get_option('maep_lang'), '3' ); ?> value="3">eBay UK</option>
						<option <?php selected(get_option('maep_lang'), '16' ); ?> value="16">eBay Austria</option>
						<option <?php selected(get_option('maep_lang'), '15' ); ?> value="15">eBay Australia</option>
						<option <?php selected(get_option('maep_lang'), '193' ); ?> value="193">eBay Switzerland</option>
						<option <?php selected(get_option('maep_lang'), '2' ); ?> value="2">eBay Canada (English)</option>
						<option <?php selected(get_option('maep_lang'), '186' ); ?> value="186">eBay Spain</option>
						<option <?php selected(get_option('maep_lang'), '71' ); ?> value="71">eBay France</option>
						<option <?php selected(get_option('maep_lang'), '23' ); ?> value="23">eBay Belgium (French)</option>
						<option <?php selected(get_option('maep_lang'), '210' ); ?> value="210">eBay Canada (French)</option>
						<option <?php selected(get_option('maep_lang'), '205' ); ?> value="205">eBay Ireland</option>
						<option <?php selected(get_option('maep_lang'), '203' ); ?> value="203">eBay India</option>
						<option <?php selected(get_option('maep_lang'), '101' ); ?> value="101">eBay Italy</option>
						<option <?php selected(get_option('maep_lang'), '100' ); ?> value="100">eBay Motors</option>
						<option <?php selected(get_option('maep_lang'), '207' ); ?> value="207">eBay Malaysia</option>
						<option <?php selected(get_option('maep_lang'), '146' ); ?> value="146">eBay Netherlands</option>
						<option <?php selected(get_option('maep_lang'), '123' ); ?> value="123">eBay Belgium (Dutch)</option>
						<option <?php selected(get_option('maep_lang'), '211' ); ?> value="211">eBay Philippines</option>
						<option <?php selected(get_option('maep_lang'), '212' ); ?> value="212">eBay Poland</option>
						<option <?php selected(get_option('maep_lang'), '216' ); ?> value="216">eBay Singapore</option>
						<option <?php selected(get_option('maep_lang'), '201' ); ?> value="201">eBay HK eBay Hong Kong</option>
					</select>
				</label>
				<!--<label><h3>Count products one category</h3><input type="text" name="count" placeholder="count" value='<?php// echo $count; ?>' class="count" /></label>-->
				</br>
				<input type="hidden" name="auto_update" />
				<label><h3><input type="checkbox" class="update-plug" name="auto_update" value="check" <?php checked( get_option('auto_update_product'), 'check'); ?>/>Enable auto update plugin</h3></label>
                <label><h3>Keywords for update</h3><input type="text" name="up_key" placeholder="keywords..." value='<?php echo $up_key; ?>' class="trackingId" /></label>
                <div style="clear: both; height: 20px; width: 100%"></div>

				<div class="panel-group" id="additional_settings">
				  <div class="panel panel-default">
				    <div class="panel-heading">
				      <h4 class="panel-title">
				        <a data-toggle="collapse" data-parent="#additional_settings" href="#collapseOne">
				          Additional settings
				        </a>
				      </h4>
				    </div>
				    <div id="collapseOne" class="panel-collapse collapse">

				      <div class="panel-body">
                        <span class="label label-success help-view"><a href="https://www.youtube.com/watch?v=SunHjvLZ3yo&list=PLclG44ANB4T3O8agAjXLNPZGSNejIfqIL&index=3" target="_blank">HELP</a></span>
				        <label><h3>DEVID</h3><input type="text" name="DEVID" placeholder="DEVID" value='<?php echo $DEVID; ?>' class="DEVID" /></label>
						<label><h3>AppID</h3><input type="text" name="appID" placeholder="appID" value='<?php echo $AppID; ?>' class="appId" /></label>
						<label><h3>CertID</h3><input type="text" name="CertID" placeholder="CertID" value='<?php echo $CertID; ?>' class="CertID" /></label>
						<label><h3>User Token</h3><textarea class="token" name="token" placeholder="Token"><?php echo $token; ?></textarea></label>
						
				      </div>
				    </div>
				  </div>
				</div>
                <label><input type="submit" value="Save settings" name="save_options" class="button button-primary button-large"/></label>
            </form>
				<div class="panel-group" id="action_plugin">
				  <div class="panel panel-default">
				    <div class="panel-heading">
				      <h4 class="panel-title">
				        <a data-toggle="collapse" data-parent="#action_plugin" href="#collapseAction">
				          Actions
				        </a>
				      </h4>
				    </div>
				    <div id="collapseAction" class="panel-collapse collapse">
				      <div class="panel-body">
                        <form action="" method="POST">
                            <h3>Upload news from eBay</h3>
                            <input class="button button-primary button-large" name="news"  type="submit" value="Upload news" id="load_news"  />
                            <h3>Delete <b>all</b> products</h3>
                            <input class="button button-primary button-large" type="button" value="Delete Products" id="delete_all_product" style="background: #F04438; color: #fff" />
                        </form>
						<div id="update-products" ></div>
						<div id="delete-products" ></div>
				      </div>
				    </div>
				  </div>
				</div>

		</div>

		
		
	<?}