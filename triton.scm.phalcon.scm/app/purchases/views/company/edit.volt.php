<?php echo $this->tag->form(array('company/save?type=' . $company_type, 'method' => 'post', 'id' => $main_form_id)); ?>
  
  
  <div class="form-field-group-container size-p-50 last">
    <div class="form-field-group">
      <div class="title">
        <?php echo $company_type; ?>
      </div>
      <div class="form-field size-p-100">
        <label for="company_name">Name:</label>
        <?php echo $this->tag->textField(array('company_name', 'size' => 100, 'value' => $company->company_name)); ?>
        <?php echo $this->tag->hiddenField(array('company_id', 'value' => $company->company_id)); ?>
      </div>
      <div class="form-field size-p-100">
        <label for="address">Address</label>
        <?php echo $this->tag->textField(array('address', 'size' => 100, 'value' => $company->address)); ?>
        <?php echo $this->tag->textField(array('address2', 'size' => 100, 'value' => $company->address2)); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="phones">Phones</label>
        <?php echo $this->tag->textField(array('phones', 'size' => 50, 'value' => $company->phones)); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="faxes">Faxes</label>
        <?php echo $this->tag->textField(array('faxes', 'size' => 50, 'value' => $company->faxes)); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="city">City</label>
        <?php echo $this->tag->textField(array('city', 'size' => 50, 'value' => $company->city)); ?>
      </div>
      <div class="form-field size-p-20">
        <label for="state">State</label>
        <?php echo $this->tag->textField(array('state', 'size' => 50, 'value' => $company->state)); ?>
      </div>
      <div class="form-field size-p-30">
        <label for="zip_code">Zip code</label>
        <?php echo $this->tag->textField(array('zip_code', 'size' => 50, 'value' => $company->zip_code)); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="webpage">Webpage</label>
        <?php echo $this->tag->textField(array('webpage', 'size' => 50, 'value' => $company->webpage)); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="email">Email</label>
        <?php echo $this->tag->textField(array('email', 'size' => 50, 'value' => $company->email)); ?>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
<?php echo $this->tag->endForm(); ?>