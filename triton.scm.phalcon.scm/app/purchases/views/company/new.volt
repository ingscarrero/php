{{ form('company/save?type=' ~ company_type, 'method': 'post', 'id': main_form_id) }}
  {# General #}
  {# ======================================================================== #}
  <div class="form-field-group-container size-p-50 last">
    <div class="form-field-group">
      <div class="title">
        Create New {{ company_type }}
      </div>
      <div class="form-field size-p-100">
        <label for="company_name">Name:</label>
        {{ text_field("company_name", "size": 100) }}
      </div>
      <div class="form-field size-p-100">
        <label for="address">Address</label>
        {{ text_field("address", "size": 100) }}
        {{ text_field("address2", "size": 100) }}
      </div>
      <div class="form-field size-p-50">
        <label for="phones">Phones</label>
        {{ text_field("phones", "size": 50) }}
      </div>
      <div class="form-field size-p-50">
        <label for="faxes">Faxes</label>
        {{ text_field("faxes", "size": 50) }}
      </div>
      <div class="form-field size-p-50">
        <label for="city">City</label>
        {{ text_field("city", "size": 50) }}
      </div>
      <div class="form-field size-p-20">
        <label for="state">State</label>
        {{ text_field("state", "size": 50) }}
      </div>
      <div class="form-field size-p-30">
        <label for="zip_code">Zip code</label>
        {{ text_field("zip_code", "size": 50) }}
      </div>
      <div class="form-field size-p-50">
        <label for="webpage">Webpage</label>
        {{ text_field("webpage", "size": 50) }}
      </div>
      <div class="form-field size-p-50">
        <label for="email">Email</label>
        {{ text_field("email", "size": 50) }}
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
{{ end_form() }}