{{ javascript_include("js/purchases/signup.js") }}
<h1>{{ title }}</h1>
{{ form('signup/register', 'method': 'post') }}
  <div class="form-field">
    <label for="name">Name</label>
    {{ text_field("name", "size": 32) }}
  </div>
  <div class="form-field">
    <label for="email">E-mail</label>
    {{ text_field("email", "size": 32) }}
  </div>
  {{ submit_button('Send') }}
{{ end_form() }}