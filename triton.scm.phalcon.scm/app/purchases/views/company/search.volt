{{ form('company/list?type=' ~ company_type, 'method': 'post', 'id': main_form_id) }}
  {# General #}
  {# ======================================================================== #}
  <div class="form-field-group-container size-p-50 last">
    <div class="form-field-group">
      <div class="title">
        Search for a {{ company_type }}
      </div>
      <div class="form-field size-p-100">
        <label for="name">Name:</label>
        {{ text_field("name", "size": 50) }}
      </div>
      <div class="form-field size-p-50">
        <label for="city">City</label>
        {{ text_field("city", "size": 50) }}
        {#{ text_field("city", "size": 50, 'class': 'triton-autocomplete'
                      , 'data-group': 'supplier', 'data-local-group': 'triton-autocomplete-supplier') }#}
      </div>
      <div class="form-field size-p-50">
        <label for="state">State</label>
        {{ text_field("state", "size": 50) }}
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