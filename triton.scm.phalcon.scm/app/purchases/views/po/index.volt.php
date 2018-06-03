
<?php echo $this->tag->form(array('po/new?scope=' . $scope, 'method' => 'post', 'id' => $main_form_id)); ?>
  
  
  <?php if ($scope == 'global') { ?>
    <?php echo $this->tag->hiddenField(array('global', 'value' => 'yes')); ?>
  <?php } else { ?>
    <?php echo $this->tag->hiddenField(array('global', 'value' => 'no')); ?>
  <?php } ?>
  <div class="form-field-group-container size-p-100 last">
    <div class="form-field-group">
      <div class="form-field size-p-15">
        <label for="po_number">P.O.#:</label>
        <?php echo $this->tag->textField(array('po_number', 'size' => 50, 'readonly' => '')); ?>
      </div>
      <div class="form-field size-p-15">
        <label for="date">P.O. Date:</label>
        <?php echo $this->tag->textField(array('date', 'size' => 50, 'value' => $now, 'readonly' => '')); ?>
      </div>
      <div class="form-field size-p-15">
        <label for="supplier_so_number">Supplier SO #:</label>
        <?php echo $this->tag->textField(array('supplier_so_number', 'size' => 50)); ?>
      </div>
      <div class="form-field size-p-15">
        <label for="shipment_terms">Shipment Terms:</label>
        <?php echo $this->tag->select(array('shipment_terms', $shipment_terms_list, 'using' => array('stid', 'name'), 'useEmpty' => true, 'emptyText' => 'Please, choose one...', 'emptyValue' => '0')); ?>
      </div>
      <div class="form-field size-p-15">
        <label for="container_number">Container #:</label>
        <?php echo $this->tag->textField(array('container_number', 'size' => 50)); ?>
      </div>
      <div class="form-field size-p-15">
        <label for="delivery_type">Delivery Type:</label>
        <?php echo $this->tag->select(array('delivery_type', $delivery_type_list, 'using' => array('dtid', 'name'), 'useEmpty' => true, 'emptyText' => 'Please, choose one...', 'emptyValue' => '0')); ?>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
  
  
  <div class="form-field-group-container size-p-33">
    <div class="form-field-group">
      <div class="title">
        Supplier
      </div>
      <div class="form-field size-p-100">
        <label for="supplier_name">Supplier</label>
        <?php echo $this->tag->textField(array('supplier-name', 'size' => 50, 'class' => 'triton-autocomplete', 'data-group' => 'supplier', 'data-local-group' => 'triton-autocomplete-supplier')); ?>
        <?php echo $this->tag->hiddenField(array('supplier', 'class' => 'triton-autocomplete-supplier supplier')); ?>
      </div>
      <div class="form-field size-p-100">
        <label for="address">Address</label>
        <?php echo $this->tag->textField(array('supplier-address', 'size' => 100, 'class' => 'triton-autocomplete-supplier supplier-address', 'readonly' => '')); ?>
        <?php echo $this->tag->textField(array('supplier-address-line-2', 'size' => 100, 'class' => 'triton-autocomplete-supplier supplier-address-line-2', 'readonly' => '')); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="city">City</label>
        <?php echo $this->tag->textField(array('supplier-city', 'size' => 100, 'class' => 'triton-autocomplete-supplier supplier-city', 'readonly' => '')); ?>
      </div>
      <div class="form-field size-p-20">
        <label for="state">State</label>
        <?php echo $this->tag->textField(array('supplier-state', 'size' => 100, 'class' => 'triton-autocomplete-supplier supplier-state', 'readonly' => '')); ?>
      </div>
      <div class="form-field size-p-30">
        <label for="zip">Zip</label>
        <?php echo $this->tag->textField(array('supplier-zip', 'size' => 10, 'class' => 'triton-autocomplete-supplier supplier-zip', 'readonly' => '')); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="country">Country</label>
        <?php echo $this->tag->textField(array('supplier-country', 'size' => 10, 'class' => 'triton-autocomplete-supplier supplier-country', 'readonly' => '')); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="supplier_payment_terms">Payment Terms</label>
        <?php echo $this->tag->select(array('supplier_payment_terms', $payment_terms_list, 'using' => array('ptid', 'name'), 'useEmpty' => true, 'emptyText' => 'Please, choose one...', 'emptyValue' => '0')); ?>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
  
  
  <div class="form-field-group-container size-p-33">
    <div class="form-field-group">
      <div class="title">
        Purchase Location
      </div>
      <div class="form-field size-p-100">
        <label for="location">Location</label>
        <?php echo $this->tag->textField(array('location', 'size' => 50, 'class' => 'triton-autocomplete', 'data-group' => 'location', 'data-local-group' => 'triton-autocomplete-location')); ?>
        <?php echo $this->tag->hiddenField(array('purchase_location', 'class' => 'triton-autocomplete-location location-id')); ?>
      </div>
      <div class="form-field size-p-100">
        <label for="address">Address</label>
        <?php echo $this->tag->textField(array('purchase_address', 'size' => 100, 'class' => 'triton-autocomplete-location location-address', 'readonly' => '')); ?>
        <?php echo $this->tag->textField(array('purchase_address_line_2', 'size' => 100, 'class' => 'triton-autocomplete-location location-address2', 'readonly' => '')); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="city">City</label>
        <?php echo $this->tag->textField(array('purchase_city', 'size' => 100, 'class' => 'triton-autocomplete-location location-city', 'readonly' => '')); ?>
      </div>
      <div class="form-field size-p-20">
        <label for="state">State</label>
        <?php echo $this->tag->textField(array('purchase_state', 'size' => 100, 'class' => 'triton-autocomplete-location location-state', 'readonly' => '')); ?>
      </div>
      <div class="form-field size-p-30">
        <label for="zip">Zip</label>
        <?php echo $this->tag->textField(array('purchase_zip', 'size' => 10, 'class' => 'triton-autocomplete-location location-zip', 'readonly' => '')); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="country">Country</label>
        <?php echo $this->tag->textField(array('purchase_country', 'size' => 10, 'class' => 'triton-autocomplete-location location-country', 'readonly' => '')); ?>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
  
  
  <div class="form-field-group-container size-p-33 last">
    <div class="form-field-group">
      <div class="title">
        Ship To Location
      </div>
      <div class="form-field size-p-100">
        <label for="ship_to_location">Ship To Location</label>
        <?php echo $this->tag->textField(array('ship_to_location', 'size' => 50, 'class' => 'triton-autocomplete', 'data-group' => 'location', 'data-local-group' => 'triton-autocomplete-ship')); ?>
        <?php echo $this->tag->hiddenField(array('ship_to_location', 'class' => 'triton-autocomplete-ship location-id')); ?>
      </div>
      <div class="form-field size-p-100">
        <label for="address">Address</label>
        <?php echo $this->tag->textField(array('address', 'size' => 100, 'class' => 'triton-autocomplete-ship location-address', 'readonly' => '')); ?>
        <?php echo $this->tag->textField(array('address_line_2', 'size' => 100, 'class' => 'triton-autocomplete-ship location-address2', 'readonly' => '')); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="city">City</label>
        <?php echo $this->tag->textField(array('city', 'size' => 100, 'class' => 'triton-autocomplete-ship location-city', 'readonly' => '')); ?>
      </div>
      <div class="form-field size-p-20">
        <label for="state">State</label>
        <?php echo $this->tag->textField(array('state', 'size' => 100, 'class' => 'triton-autocomplete-ship location-state', 'readonly' => '')); ?>
      </div>
      <div class="form-field size-p-30">
        <label for="zip">Zip</label>
        <?php echo $this->tag->textField(array('zip', 'size' => 10, 'class' => 'triton-autocomplete-ship location-zip', 'readonly' => '')); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="country">Country</label>
        <?php echo $this->tag->textField(array('country', 'size' => 10, 'class' => 'triton-autocomplete-ship location-country', 'readonly' => '')); ?>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
  
  
  <div class="form-field-group-container size-p-33">
    <div class="form-field-group">
      <div class="title">
        Additional Info
      </div>
      <div class="form-field size-p-100">
        <label for="freight_forwarder">Freight Forwarder</label>
        <?php echo $this->tag->select(array('freight_forwarder', $freight_forwarders_list, 'using' => array('ffid', 'name'), 'useEmpty' => true, 'emptyText' => 'Please, choose one...', 'emptyValue' => '0')); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="vessel">Vessel</label>
        <?php echo $this->tag->textField(array('vessel', 'size' => 10)); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="air_bill_number">Airbill #</label>
        <?php echo $this->tag->textField(array('air_bill_number', 'size' => 50)); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="planned_ex_factory_date">Planned EFD</label>
        <?php echo $this->tag->textField(array('planned_ex_factory_date', 'size' => 50, 'class' => 'triton-datepicker')); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="ex_factory_date">Ex Factory Date</label>
        <?php echo $this->tag->textField(array('ex_factory_date', 'size' => 50, 'class' => 'triton-datepicker')); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="departure_port_name">Departure Port</label>
        <?php echo $this->tag->textField(array('departure_port_name', 'size' => 10, 'class' => 'triton-autocomplete', 'data-group' => 'port', 'data-local-group' => 'triton-autocomplete-dep-port')); ?>
        <?php echo $this->tag->hiddenField(array('departure_port', 'class' => 'triton-autocomplete-dep-port port-id')); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="etd_port">ETD Port</label>
        <?php echo $this->tag->textField(array('etd_port', 'size' => 50, 'class' => 'triton-datepicker')); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="arrival_port">Arrival Port</label>
        <?php echo $this->tag->textField(array('arrival_port_name', 'size' => 10, 'class' => 'triton-autocomplete', 'data-group' => 'port', 'data-local-group' => 'triton-autocomplete-arr-port')); ?>
        <?php echo $this->tag->hiddenField(array('arrival_port', 'class' => 'triton-autocomplete-arr-port port-id')); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="eta_Port">ETA Port</label>
        <?php echo $this->tag->textField(array('eta_port', 'size' => 50, 'class' => 'triton-datepicker')); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="discharge_port_name">Discharge Port</label>
        <?php echo $this->tag->textField(array('discharge_port_name', 'size' => 10, 'class' => 'triton-autocomplete', 'data-group' => 'port', 'data-local-group' => 'triton-autocomplete-discharge-port')); ?>
        <?php echo $this->tag->hiddenField(array('discharge_port', 'class' => 'triton-autocomplete-discharge-port port-id')); ?>
      </div>
      <div class="form-field size-p-50">
        <label for="wiring_instructions">Wiring Instruct.</label>
        <?php echo $this->tag->select(array('wiring_instructions', $wiring_instructions_list, 'using' => array('wiid', 'name'), 'useEmpty' => true, 'emptyText' => 'Please, choose one...', 'emptyValue' => '0')); ?>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
  
  
  <div class="form-field-group-container size-p-33">
    <div class="form-field-group">
      <div class="title">
        Notes
      </div>
      <div class="form-field size-p-100">
        <label for="printed_notes">Printed Notes</label>
        <?php echo $this->tag->textArea(array('printed_notes', '', 'cols' => '43', 'rows' => 11)); ?>
      </div>
      <div class="form-field size-p-100">
        <label for="internal_notes">Internal Notes</label>
        <?php echo $this->tag->textArea(array('internal_notes', '', 'cols' => '43', 'rows' => 11)); ?>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
  
  
  <div class="form-field-group-container size-p-33 last">
    <div class="form-field-group">
      <div class="title">
        P.O. Terms
      </div>
      <div class="form-field size-p-100">
        <label for="terms"></label>
        <?php echo $this->tag->textArea(array('terms', '', 'cols' => '43', 'rows' => 25)); ?>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
  <div class="clearfix"></div>
  
  
  
<?php echo $this->tag->endForm(); ?>