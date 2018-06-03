
<?php echo $this->tag->form(array('po/search?scope=' . $scope, 'method' => 'post', 'id' => $main_form_id)); ?>
  
  
  <div class="form-field-group-container size-p-50 last">
    <div class="form-field-group">
      <div class="title">
        Search for <?php echo $scope; ?> Purchase Orders
      </div>
      <div class="form-field size-p-100">
        <label for="poid">P.O.#:</label>
        <?php echo $this->tag->textField(array('poid', 'size' => 50)); ?>
      </div>
      <div class="form-field size-p-100">
        <label for="supplier_name">Inventory Supplier</label>
        <?php echo $this->tag->textField(array('supplier_name', 'size' => 50, 'class' => 'triton-autocomplete', 'data-group' => 'supplier', 'data-local-group' => 'triton-autocomplete-supplier')); ?>
        <?php echo $this->tag->hiddenField(array('supplier', 'class' => 'triton-autocomplete-supplier supplier')); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="date_from">PO Date</label>
        <?php echo $this->tag->textField(array('date_from', 'size' => 50, 'class' => 'triton-datepicker')); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="date_to">To</label>
        <?php echo $this->tag->textField(array('date_to', 'size' => 50, 'class' => 'triton-datepicker')); ?>
      </div>
      <div class="form-field size-p-100">
        <?php echo $this->tag->checkField(array('unapproved')); ?>
        <label for="unapproved">Unapproved Purchase Orders</label>
      </div>
      <div class="form-field size-p-100">
        <?php echo $this->tag->checkField(array('consignment')); ?>
        <label for="consignment">Consignment Purchase Orders</label>
      </div>
      <div class="clearfix"></div>
      <div class="title">
        &nbsp
      </div>
      <input type="button" value="View All" id="cancel-top" data-exit="<?php echo $exit_to; ?>" class="button-top">
      <input type="button" value="Search" id="submit-top" data-main-form="<?php echo $main_form_id; ?>" class="button-top">
    </div>
  </div>
<?php echo $this->tag->endForm(); ?>