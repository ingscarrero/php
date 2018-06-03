{# /app/purchases/views/po/index.volt #}
{{ form('po/new?scope='~scope, 'method': 'post', 'id': main_form_id) }}
  {# General #}
  {# ======================================================================== #}
  {% if scope == 'global' %}
    {{ hidden_field("global", "value": "yes") }}
  {% else %}
    {{ hidden_field("global", "value": "no") }}
  {% endif %}
  <div class="form-field-group-container size-p-100 last">
    <div class="form-field-group">
      <div class="form-field size-p-15">
        <label for="po_number">P.O.#:</label>
        {{ text_field("po_number", "size": 50, 'readonly': '') }}
      </div>
      <div class="form-field size-p-15">
        <label for="date">P.O. Date:</label>
        {{ text_field("date", "size": 50, "value": now, 'readonly': '') }}
      </div>
      <div class="form-field size-p-15">
        <label for="supplier_so_number">Supplier SO #:</label>
        {{ text_field("supplier_so_number", "size": 50) }}
      </div>
      <div class="form-field size-p-15">
        <label for="shipment_terms">Shipment Terms:</label>
        {{ select("shipment_terms", shipment_terms_list, 'using': ['stid', 'name'],
                  'useEmpty': true, 'emptyText': 'Please, choose one...', 'emptyValue': '0') }}
      </div>
      <div class="form-field size-p-15">
        <label for="container_number">Container #:</label>
        {{ text_field("container_number", "size": 50) }}
      </div>
      <div class="form-field size-p-15">
        <label for="delivery_type">Delivery Type:</label>
        {{ select("delivery_type", delivery_type_list, 'using': ['dtid', 'name'],
                  'useEmpty': true, 'emptyText': 'Please, choose one...', 'emptyValue': '0') }}
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
  {# Supplier #}
  {# ======================================================================== #}
  <div class="form-field-group-container size-p-33">
    <div class="form-field-group">
      <div class="title">
        Supplier
      </div>
      <div class="form-field size-p-100">
        <label for="supplier_name">Supplier</label>
        {{ text_field("supplier-name", "size": 50, 'class': 'triton-autocomplete'
                      , 'data-group': 'supplier', 'data-local-group': 'triton-autocomplete-supplier') }}
        {{ hidden_field("supplier", "class": "triton-autocomplete-supplier supplier") }}
      </div>
      <div class="form-field size-p-100">
        <label for="address">Address</label>
        {{ text_field("supplier-address", "size": 100, "class": 
          "triton-autocomplete-supplier supplier-address", 'readonly': '') }}
        {{ text_field("supplier-address-line-2", "size": 100, "class": 
          "triton-autocomplete-supplier supplier-address-line-2", 'readonly': '') }}
      </div>
      <div class="form-field size-p-50">
        <label for="city">City</label>
        {{ text_field("supplier-city", "size": 100, "class": 
          "triton-autocomplete-supplier supplier-city", 'readonly': '') }}
      </div>
      <div class="form-field size-p-20">
        <label for="state">State</label>
        {{ text_field("supplier-state", "size": 100, "class": 
          "triton-autocomplete-supplier supplier-state", 'readonly': '') }}
      </div>
      <div class="form-field size-p-30">
        <label for="zip">Zip</label>
        {{ text_field("supplier-zip", "size": 10, "class": 
          "triton-autocomplete-supplier supplier-zip", 'readonly': '') }}
      </div>
      <div class="form-field size-p-50">
        <label for="country">Country</label>
        {{ text_field("supplier-country", "size": 10, "class": 
          "triton-autocomplete-supplier supplier-country", 'readonly': '') }}
      </div>
      <div class="form-field size-p-50">
        <label for="supplier_payment_terms">Payment Terms</label>
        {{ select("supplier_payment_terms", payment_terms_list, 'using': ['ptid', 'name'],
                  'useEmpty': true, 'emptyText': 'Please, choose one...', 'emptyValue': '0') }}
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
  {# Purchase Location #}
  {# ======================================================================== #}
  <div class="form-field-group-container size-p-33">
    <div class="form-field-group">
      <div class="title">
        Purchase Location
      </div>
      <div class="form-field size-p-100">
        <label for="location">Location</label>
        {{ text_field("location", "size": 50, 'class': 'triton-autocomplete'
          , 'data-group': 'location', 'data-local-group': 'triton-autocomplete-location') }}
        {{ hidden_field("purchase_location", "class": "triton-autocomplete-location location-id") }}
      </div>
      <div class="form-field size-p-100">
        <label for="address">Address</label>
        {{ text_field("purchase_address", "size": 100, "class": 
          "triton-autocomplete-location location-address", 'readonly': '') }}
        {{ text_field("purchase_address_line_2", "size": 100, "class": 
          "triton-autocomplete-location location-address2", 'readonly': '') }}
      </div>
      <div class="form-field size-p-50">
        <label for="city">City</label>
        {{ text_field("purchase_city", "size": 100, "class": 
          "triton-autocomplete-location location-city", 'readonly': '') }}
      </div>
      <div class="form-field size-p-20">
        <label for="state">State</label>
        {{ text_field("purchase_state", "size": 100, "class": 
          "triton-autocomplete-location location-state", 'readonly': '') }}
      </div>
      <div class="form-field size-p-30">
        <label for="zip">Zip</label>
        {{ text_field("purchase_zip", "size": 10, "class": 
          "triton-autocomplete-location location-zip", 'readonly': '') }}
      </div>
      <div class="form-field size-p-50">
        <label for="country">Country</label>
        {{ text_field("purchase_country", "size": 10, "class": 
          "triton-autocomplete-location location-country", 'readonly': '') }}
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
  {# Ship To Location #}
  {# ======================================================================== #}
  <div class="form-field-group-container size-p-33 last">
    <div class="form-field-group">
      <div class="title">
        Ship To Location
      </div>
      <div class="form-field size-p-100">
        <label for="ship_to_location">Ship To Location</label>
        {{ text_field("ship_to_location", "size": 50, 'class': 'triton-autocomplete'
          , 'data-group': 'location', 'data-local-group': 'triton-autocomplete-ship') }}
        {{ hidden_field("ship_to_location", "class": "triton-autocomplete-ship location-id") }}
      </div>
      <div class="form-field size-p-100">
        <label for="address">Address</label>
        {{ text_field("address", "size": 100, "class": 
          "triton-autocomplete-ship location-address", 'readonly': '') }}
        {{ text_field("address_line_2", "size": 100, "class": 
          "triton-autocomplete-ship location-address2", 'readonly': '') }}
      </div>
      <div class="form-field size-p-50">
        <label for="city">City</label>
        {{ text_field("city", "size": 100, "class": 
          "triton-autocomplete-ship location-city", 'readonly': '') }}
      </div>
      <div class="form-field size-p-20">
        <label for="state">State</label>
        {{ text_field("state", "size": 100, "class": 
          "triton-autocomplete-ship location-state", 'readonly': '') }}
      </div>
      <div class="form-field size-p-30">
        <label for="zip">Zip</label>
        {{ text_field("zip", "size": 10, "class": 
          "triton-autocomplete-ship location-zip", 'readonly': '') }}
      </div>
      <div class="form-field size-p-50">
        <label for="country">Country</label>
        {{ text_field("country", "size": 10, "class": 
          "triton-autocomplete-ship location-country", 'readonly': '') }}
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
  {# Additional Info #}
  {# ======================================================================== #}
  <div class="form-field-group-container size-p-33">
    <div class="form-field-group">
      <div class="title">
        Additional Info
      </div>
      <div class="form-field size-p-100">
        <label for="freight_forwarder">Freight Forwarder</label>
        {{ select("freight_forwarder", freight_forwarders_list, 'using': ['ffid', 'name'],
                  'useEmpty': true, 'emptyText': 'Please, choose one...', 'emptyValue': '0') }}
      </div>
      <div class="form-field size-p-50">
        <label for="vessel">Vessel</label>
        {{ text_field("vessel", "size": 10) }}
      </div>
      <div class="form-field size-p-50">
        <label for="air_bill_number">Airbill #</label>
        {{ text_field("air_bill_number", "size": 50) }}
      </div>
      <div class="form-field size-p-50">
        <label for="planned_ex_factory_date">Planned EFD</label>
        {{ text_field("planned_ex_factory_date", "size": 50, 'class': 'triton-datepicker') }}
      </div>
      <div class="form-field size-p-50">
        <label for="ex_factory_date">Ex Factory Date</label>
        {{ text_field("ex_factory_date", "size": 50, 'class': 'triton-datepicker') }}
      </div>
      <div class="form-field size-p-50">
        <label for="departure_port_name">Departure Port</label>
        {{ text_field("departure_port_name", "size": 10, 'class': 'triton-autocomplete'
          , 'data-group': 'port', 'data-local-group': 'triton-autocomplete-dep-port') }}
        {{ hidden_field("departure_port", "class": "triton-autocomplete-dep-port port-id") }}
      </div>
      <div class="form-field size-p-50">
        <label for="etd_port">ETD Port</label>
        {{ text_field("etd_port", "size": 50, 'class': 'triton-datepicker') }}
      </div>
      <div class="form-field size-p-50">
        <label for="arrival_port">Arrival Port</label>
        {{ text_field("arrival_port_name", "size": 10, 'class': 'triton-autocomplete'
          , 'data-group': 'port', 'data-local-group': 'triton-autocomplete-arr-port') }}
        {{ hidden_field("arrival_port", "class": "triton-autocomplete-arr-port port-id") }}
      </div>
      <div class="form-field size-p-50">
        <label for="eta_Port">ETA Port</label>
        {{ text_field("eta_port", "size": 50, 'class': 'triton-datepicker') }}
      </div>
      <div class="form-field size-p-50">
        <label for="discharge_port_name">Discharge Port</label>
        {{ text_field("discharge_port_name", "size": 10, 'class': 'triton-autocomplete'
          , 'data-group': 'port', 'data-local-group': 'triton-autocomplete-discharge-port') }}
        {{ hidden_field("discharge_port", "class": "triton-autocomplete-discharge-port port-id") }}
      </div>
      <div class="form-field size-p-50">
        <label for="wiring_instructions">Wiring Instruct.</label>
        {{ select("wiring_instructions", wiring_instructions_list, 'using': ['wiid', 'name'],
                  'useEmpty': true, 'emptyText': 'Please, choose one...', 'emptyValue': '0') }}
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
  {# Notes #}
  {# ======================================================================== #}
  <div class="form-field-group-container size-p-33">
    <div class="form-field-group">
      <div class="title">
        Notes
      </div>
      <div class="form-field size-p-100">
        <label for="printed_notes">Printed Notes</label>
        {{ text_area("printed_notes", "", "cols": "43", "rows": 11) }}
      </div>
      <div class="form-field size-p-100">
        <label for="internal_notes">Internal Notes</label>
        {{ text_area("internal_notes", "", "cols": "43", "rows": 11) }}
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
  {# P.O. Terms #}
  {# ======================================================================== #}
  <div class="form-field-group-container size-p-33 last">
    <div class="form-field-group">
      <div class="title">
        P.O. Terms
      </div>
      <div class="form-field size-p-100">
        <label for="terms"></label>
        {{ text_area("terms", "", "cols": "43", "rows": 25) }}
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
  <div class="clearfix"></div>
  {# Buttons #}
  {# ======================================================================== #}
  {# { submit_button('Cancel') }}
  {{ submit_button('Go To Next Step To Add Products') } #}
{{ end_form() }}