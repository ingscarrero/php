<?php echo $this->tag->form(array('company/save?type=' . $company_type, 'method' => 'post', 'id' => $main_form_id)); ?>
  
  
  <div class="form-field-group-container size-p-50 last">
    <div class="form-field-group">
      <div class="title">
        Create New <?php echo $company_type; ?>
      </div>
      <div class="form-field size-p-100">
        <label for="company_name">Name:</label>
        <?php echo $this->tag->textField(array('company_name', 'size' => 100)); ?>
      </div>
      <div class="form-field size-p-100">
        <label for="address">Address</label>
        <?php echo $this->tag->textField(array('address', 'size' => 100)); ?>
        <?php echo $this->tag->textField(array('address2', 'size' => 100)); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="phones">Phones</label>
        <?php echo $this->tag->textField(array('phones', 'size' => 50)); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="faxes">Faxes</label>
        <?php echo $this->tag->textField(array('faxes', 'size' => 50)); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="city">City</label>
        <?php echo $this->tag->textField(array('city', 'size' => 50)); ?>
      </div>
      <div class="form-field size-p-20">
        <label for="state">State</label>
        <?php echo $this->tag->textField(array('state', 'size' => 50)); ?>
      </div>
      <div class="form-field size-p-30">
        <label for="zip_code">Zip code</label>
        <?php echo $this->tag->textField(array('zip_code', 'size' => 50)); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="webpage">Webpage</label>
        <?php echo $this->tag->textField(array('webpage', 'size' => 50)); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="email">Email</label>
        <?php echo $this->tag->textField(array('email', 'size' => 50)); ?>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
<?php echo $this->tag->endForm(); ?>