{# /app/purchases/views/po/index.volt #}
{{ form('po/search?scope='~scope, 'method': 'post', 'id': main_form_id) }}
  {# General #}
  {# ======================================================================== #}
  <div class="form-field-group-container size-p-50 last">
    <div class="form-field-group">
      <div class="title">
        Search for {{ scope }} Purchase Orders
      </div>
      <div class="form-field size-p-100">
        <label for="poid">P.O.#:</label>
        {{ text_field("poid", "size": 50) }}
      </div>
      <div class="form-field size-p-100">
        <label for="supplier_name">Inventory Supplier</label>
        {{ text_field("supplier_name", "size": 50, 'class': 'triton-autocomplete'
                      , 'data-group': 'supplier', 'data-local-group': 'triton-autocomplete-supplier') }}
        {{ hidden_field("supplier", "class": "triton-autocomplete-supplier supplier") }}
      </div>
      <div class="form-field size-p-50">
        <label for="date_from">PO Date</label>
        {{ text_field("date_from", "size": 50, 'class': 'triton-datepicker') }}
      </div>
      <div class="form-field size-p-50">
        <label for="date_to">To</label>
        {{ text_field("date_to", "size": 50, 'class': 'triton-datepicker') }}
      </div>
      <div class="form-field size-p-100">
        {{ check_field("unapproved") }}
        <label for="unapproved">Unapproved Purchase Orders</label>
      </div>
      <div class="form-field size-p-100">
        {{ check_field("consignment") }}
        <label for="consignment">Consignment Purchase Orders</label>
      </div>
      <div class="clearfix"></div>
      <div class="title">
        &nbsp
      </div>
      <input type="button" value="View All" id="cancel-top" data-exit="{{ exit_to }}" class="button-top">
      <input type="button" value="Search" id="submit-top" data-main-form="{{ main_form_id }}" class="button-top">
    </div>
  </div>
{{ end_form() }}