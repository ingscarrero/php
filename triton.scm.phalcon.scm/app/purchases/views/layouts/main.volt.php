


<script>
  <?php foreach ($js_general_parameters as $name => $value) { ?>
    var <?php echo $name; ?> = "<?php echo $value; ?>";
  <?php } ?>
</script>

<?php echo $this->tag->javascriptInclude('lib/jquery/jquery.min.js'); ?>
<?php echo $this->tag->javascriptInclude('lib/jquery-ui/jquery-ui.min.js'); ?>
<?php echo $this->tag->javascriptInclude('js/triton.js'); ?>
<?php echo $this->tag->javascriptInclude('js/menu.js'); ?>
<?php echo $this->tag->javascriptInclude('js/ui.js'); ?>
<?php echo $this->tag->javascriptInclude('js/purchases/autocomplete.js'); ?>


<link href='http://fonts.googleapis.com/css?family=Oxygen:400,700' rel='stylesheet' type='text/css'>
<?php echo $this->tag->stylesheetLink('lib/font-awesome/css/font-awesome.min.css'); ?>

<?php echo $this->tag->stylesheetLink('lib/jquery-ui/jquery-ui.min.css'); ?>
<?php echo $this->tag->stylesheetLink('lib/jquery-ui/jquery-ui.theme.min.css'); ?>
<?php echo $this->tag->stylesheetLink('css/styles.css'); ?>


<div id="main-menu-container">
  <div id="main-menu-header">
    <img class="arrow" src="<?php echo $this->url->get('img/arrow-big-left.png'); ?>"/>
  </div>
  <div id="main-menu">
    <ul class="module-menu-list">
      <?php echo $this->tag->linkTo(array('index', '<li><i class=\'fa fa-home fa-lg fa-fw\'></i><span>Dashboard</span></li>')); ?>
      <?php echo $this->tag->linkTo(array('index', '<li><i class=\'fa fa-folder fa-lg fa-fw\'></i><span>Presales</span></li>')); ?>
      <div class='main-menu-item purchases'>
        <?php echo $this->tag->linkTo(array('index', '<li><i class=\'fa fa-credit-card fa-lg fa-fw\'></i><span>Purchases</span></li>')); ?>
        <div class="float-menu-cont hidden">
          <div class="float-pointer"></div>
          <div class="menu-container">
            <div class="menu-container-left">
              <ul>
                <?php echo $this->tag->linkTo(array('company/list?type=supplier', 'Inventory Suppliers')); ?>
                <br>
                <div class="float-menu-separator"></div>
                <div class="menu-pending"><?php echo $this->tag->linkTo(array('po/search', 'Pre-purchase request')); ?></div>
                <div class="menu-pending"><?php echo $this->tag->linkTo(array('po/search', 'To-be Purchased')); ?></div>
                <div class="menu-pending"><?php echo $this->tag->linkTo(array('po/search', 'To-be Allocated')); ?></div>
                <div class="float-menu-separator"></div>
                <?php echo $this->tag->linkTo(array('po/search', 'All PO\'s')); ?>
                <br>
                <?php echo $this->tag->linkTo(array('po/search?scope=unapproved', 'UnApproved PO\'s')); ?>
                <br>
                <?php echo $this->tag->linkTo(array('po/search?scope=pending', 'Pending PO\'s')); ?>
              </ul>
            </div>
            <div class="menu-container-right">
              <ul>
                <?php echo $this->tag->linkTo(array('po/search', 'All Supplier Invoices')); ?>
                <br>
                <?php echo $this->tag->linkTo(array('po/search', 'Not Received')); ?>
                <div class="float-menu-separator"></div>
                <div class="menu-pending"><?php echo $this->tag->linkTo(array('po/search', 'Freight Bills')); ?></div>
                <?php echo $this->tag->linkTo(array('company/list?type=freight', 'Freight Vendors')); ?>
                <div class="float-menu-separator"></div>
                <?php echo $this->tag->linkTo(array('po/index?scope=global', 'New Global PO')); ?>
                <br>
                <?php echo $this->tag->linkTo(array('po/search?scope=global', 'Global PO\'s')); ?>
                <br>
                <?php echo $this->tag->linkTo(array('po/list?scope=global', 'Global PO\'s Search')); ?>
              </ul>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="search-bar-container">
            <?php echo $this->tag->form(array('po/search', 'method' => 'post', 'id' => 'search-bar-form')); ?>
              
              <?php echo $this->tag->textField(array('supplier_name', 'size' => 50, 'class' => 'triton-autocomplete', 'data-group' => 'supplier', 'data-local-group' => 'triton-autocomplete-supplier', 'placeholder' => 'Search Purchases')); ?>
              <?php echo $this->tag->hiddenField(array('supplier', 'class' => 'triton-autocomplete-supplier supplier')); ?>
            <?php echo $this->tag->endForm(); ?>
          </div>
        </div>
      </div>
      
      <div class='main-menu-item inventory'>
        <a href="/triton/client/#/inventory"><li><i class='fa fa-truck fa-lg fa-fw'></i><span>Inventory</span></li></a>
        <div class="float-menu-cont hidden">
          <div class="float-pointer"></div>
          <div class="menu-container">
            <div class="menu-container-left">
              <ul>
                <a href="/triton/client/#/inventory">All Products</a>
                <br>
                <a href="/triton/client/#/inventory/create">New Product</a>
                <br>
                <div class="float-menu-separator"></div>
                <div class="menu-pending">Stock Products</div>
                <div class="menu-pending">Non Stock Products</div>
                <div class="float-menu-separator"></div>
                <a href="/triton/client/#/inventory/items/add">Inventory Manual Submitting</a>
                <br>
                <div class="float-menu-separator"></div>
                <div class="menu-pending">In Transit</div>
                <div class="menu-pending">Calendar</div>
              </ul>
            </div>
            <div class="menu-container-right">
              <ul>
                <div class="menu-pending">Inventory Search</div>
                <div class="menu-pending">Inventory By Type</div>
                <div class="menu-pending">Inventory By Location</div>
                <div class="float-menu-separator"></div>
                <a href="/triton/client/#/inventory/returns/list">Inventory Returns</a>
                <br>
                <div class="float-menu-separator"></div>
                <a href="/triton/client/#/inventory/van/create">Vans Create</a>
                <br>
                <a href="/triton/client/#/inventory/van/inventory">Vans Inventory</a>
                <br>
                <div class="float-menu-separator"></div>
                <a href="/triton/client/#/inventory/consignment/list">Consignments</a>
                <br>
              </ul>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="search-bar-container">
            <input type="text" name="supplier_name" class="triton-autocomplete">
          </div>
        </div>
      </div>
      <?php echo $this->tag->linkTo(array('index', '<li><i class=\'fa fa-tag fa-lg fa-fw\'></i><span>Sales</span></li>')); ?>
      <?php echo $this->tag->linkTo(array('index', '<li><i class=\'fa fa-pencil fa-lg fa-fw\'></i><span>Accounting</span></li>')); ?>
      <?php echo $this->tag->linkTo(array('index', '<li><i class=\'fa fa-bar-chart fa-lg fa-fw\'></i><span>Reports</span></li>')); ?>
    </ul>
    <ul class="admin-menu-list">
      <?php echo $this->tag->linkTo(array('index', '<li><i class=\'fa fa-unlock fa-lg fa-fw\'></i><span>Admin</span></li>')); ?>
      <?php echo $this->tag->linkTo(array('index', '<li><i class=\'fa fa-cog fa-lg fa-fw\'></i><span>Setup</span></li>')); ?>
      <?php echo $this->tag->linkTo(array('index', '<li><i class=\'fa fa-wrench fa-lg fa-fw\'></i><span>Support</span></li>')); ?>
    </ul>
  </div>
</div>
<div id="master-container">
  
  
  <div id="header-container">
    <div id="logo-container">
      <a href="<?php echo $this->url->get('index'); ?>">
        <img src="<?php echo $this->url->get('img/logo-top.png'); ?>"/>
      </a>
		</div>
    <div id="header-right-container">
      <div id="header-search">
        <?php echo $this->tag->form(array('search')); ?>
          <input id="s" class="triton-autocomplete" type="text" name="s" 
                value="" placeholder="Quick Search" data-group="search">
        </form>
      </div>
      <div id="header-right-icons">
        <img src="<?php echo $this->url->get('img/prototype/header-right-icons.png'); ?>"/>
      </div>
      <div id="header-user-menu-button">
        <div class="name-text">Josh Kessler</div>
        <img class="arrow" src="<?php echo $this->url->get('img/arrow-down.png'); ?>"/>
      </div>
    </div>
    <div class="clearfix"></div>
  </div>
  
  
  <div id="secondary-navigation-container">
    <div class="secondary-texts">
      <span class="big-title"><?php echo $title; ?></span>
      <span class="subtitle"><?php echo $subtitle; ?></span>
    </div>
    <div class="secondary-controls">
      <?php if ($show_cancel) { ?>
        <input type="button" value="<?php echo $cancel_text; ?>" id="cancel-top" data-exit="<?php echo $exit_to; ?>" class="button-top">
      <?php } ?>
      <?php if ($show_submit) { ?>
        <input type="button" value="<?php echo $submit_text; ?>" id="submit-top" data-main-form="<?php echo $main_form_id; ?>"  data-route="<?php echo $route_to; ?>" class="button-top">
      <?php } ?>
    </div>
  </div>
  
  
  <div class="main-content-container <?php echo $page_id; ?>">
    <?php echo $this->getContent(); ?>
  </div>
  
  
	<div id="footer">
	</div>
</div>