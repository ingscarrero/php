<?php echo $this->tag->form(array('company/list?type=' . $company_type, 'method' => 'post', 'id' => $main_form_id)); ?>
  
  
  <div class="form-field-group-container size-p-50 last">
    <div class="form-field-group">
      <div class="title">
        Search for a <?php echo $company_type; ?>
      </div>
      <div class="form-field size-p-100">
        <label for="name">Name:</label>
        <?php echo $this->tag->textField(array('name', 'size' => 50)); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="city">City</label>
        <?php echo $this->tag->textField(array('city', 'size' => 50)); ?>
        
      </div>
      <div class="form-field size-p-50">
        <label for="state">State</label>
        <?php echo $this->tag->textField(array('state', 'size' => 50)); ?>
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