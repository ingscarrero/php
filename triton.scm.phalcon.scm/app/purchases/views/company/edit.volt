{{ form('company/save?type=' ~ company_type, 'method': 'post', 'id': main_form_id) }}
  {# General #}
  {# ======================================================================== #}
  <div class="form-field-group-container size-p-50 last">
    <div class="form-field-group">
      <div class="title">
        {{ company_type }}
      </div>
      <div class="form-field size-p-100">
        <label for="company_name">Name:</label>
        {{ text_field("company_name", "size": 100, "value": company.company_name) }}
        {{ hidden_field("company_id", "value": company.company_id) }}
      </div>
      <div class="form-field size-p-100">
        <label for="address">Address</label>
        {{ text_field("address", "size": 100, "value": company.address) }}
        {{ text_field("address2", "size": 100, "value": company.address2) }}
      </div>
      <div class="form-field size-p-50">
        <label for="phones">Phones</label>
        {{ text_field("phones", "size": 50, "value": company.phones) }}
      </div>
      <div class="form-field size-p-50">
        <label for="faxes">Faxes</label>
        {{ text_field("faxes", "size": 50, "value": company.faxes) }}
      </div>
      <div class="form-field size-p-50">
        <label for="city">City</label>
        {{ text_field("city", "size": 50, "value": company.city) }}
      </div>
      <div class="form-field size-p-20">
        <label for="state">State</label>
        {{ text_field("state", "size": 50, "value": company.state) }}
      </div>
      <div class="form-field size-p-30">
        <label for="zip_code">Zip code</label>
        {{ text_field("zip_code", "size": 50, "value": company.zip_code) }}
      </div>
      <div class="form-field size-p-50">
        <label for="webpage">Webpage</label>
        {{ text_field("webpage", "size": 50, "value": company.webpage) }}
      </div>
      <div class="form-field size-p-50">
        <label for="email">Email</label>
        {{ text_field("email", "size": 50, "value": company.email) }}
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
{{ end_form() }}