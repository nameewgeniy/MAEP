<?php 
	function maep_load_products()
	{
		$ebay = new MaepCore;
		$result = $ebay->ChildCategoriesByID(-1);?>
		<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist" id="products_main">
			  <li class="active"><a href="#home" role="tab" data-toggle="tab" style="font-size: 20px;">Load Categories</a></li>
			  <li><a href="#one_product" role="tab" data-toggle="tab" style="font-size: 20px;">Load Product</a></li>
			  <li><a href="#review" role="tab" data-toggle="tab" style="font-size: 20px;">Load Reviews And Guides</a></li>
			</ul>
			<!-- Tab panes -->
			<div class="tab-content">
			  <div class="tab-pane active" id="home">
			  		 <div class='cat_select'>
				  			<h2>Select main category</h2>
					  		<select id='maep_categories'>
					  		<option selected="selected" data-id='0'>Select Category</option>
					  	<?php

					  		foreach ($result->CategoryArray->Category as $category) {
									if ($category->CategoryID != '-1')
										echo "<option data-id=" . $category->CategoryID . ">" . $category->CategoryName . "</option>";
								}
					  	 ?>
					  	 	</select>
                            <h2>Or enter category id</h2>
                             <div class="input-group search-custom">
                                 <input type="text" id="custom_id" name="custom_id" placaholder="id category..." class="form-control search-product-input" />
                                      <span class="input-group-btn">
                                        <button class="btn btn-default" type="button" id="search_custom">Search</button>
                                      </span>
                             </div>
                            <h2>Enter Keywords</h2>
                            <p>You can also use keywords to find and import products from the list of available categories. In this case only products with selected keywords will be posted on your store.</p>
                             <div class="input-group keywords_cat_id_div">
                                 <input type="text" id="keywords_cat_id" name="keywords_cat" placaholder="keywords..." class="form-control search-product-input" />
                             </div>
                             <h2>PerPage</h2>
                             <input type="number" value="2" class="count-search" id="count-per-page"/>
					  	 	<form method='post' id='form_cat'>
						  	 	<input type='button' name='publish' id='publish_maep' value='Load products' class="btn btn-primary"/>
					  	 	</form>

                            <div class="popular-search">

                                <?php


                                ?>

                            </div>
				  	 </div>
				  	 <div class="info_load">
                         <h2 class="name_main_cat"></h2>
                         <div id='cat_result' ></div>
				  	 </div>
			  	 	
			  </div>
			  <div class="tab-pane" id="one_product">
					 <div class="search-product">
					 		<h2>Enter Keywords</h2>
							<p style="clear:both">You can also use keywords to find and import products</p>
							<div class="input-group">
								  <input type="text" id="input-keywords" class="form-control search-product-input">
                                  <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" id="search_key">Search</button>
                                  </span>
							</div>
							<h2>PerPage</h2>
							<input type="number" value="10" class="count-search" id="count_search"/>
							<div >
								<input name="publish" id="publish_maep_key" value="Load products" class="btn btn-primary" type="button">								
							</div>
				    </div>
				    <div class="info_load" id="list_item">
				    	<div style="width: 200px; margin: 0 auto;"><div class="loader " id="load_main">Loading...</div></div>
			    	</div>
					  	
			  </div>
              <div class="tab-pane" id="review">
                  <div class="search-product">
                      <h2>Enter category ID</h2>
                      <div class="input-group">
                          <input type="text" id="input-id-review" class="form-control search-product-input">
                                  <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" id="search_key">Check</button>
                                  </span>
                      </div>
                      <h2>PerPage</h2>
                      <input type="number" value="1" class="count-search" id="check_review"/>
                      <div >
                          <input name="publish" id="load_review" value="Load review" class="btn btn-primary" type="button">
                      </div>
                  </div>
                  <div class="settings-banner" >
                      <h3><a id="view_ids">View id categories</a></h3>
                      <div id="rev_ids"></div>
                  </div>


              </div>
			</div>
	<?
	
	}
	
 