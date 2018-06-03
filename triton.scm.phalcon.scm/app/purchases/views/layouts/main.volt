{# /app/purchases/views/layouts/main.volt #}
{# Script to include all the variables passed from the controller. #}
{# @view Triton\Purchases\Controllers.ControllerBase.initialize() #}
<script>
  {% for name, value in js_general_parameters %}
    var {{ name }} = "{{ value }}";
  {% endfor %}
</script>
{# Include main JS libraries that are used all across the module. #}
{{ javascript_include("lib/jquery/jquery.min.js") }}
{{ javascript_include("lib/jquery-ui/jquery-ui.min.js") }}
{{ javascript_include("js/triton.js") }}
{{ javascript_include("js/menu.js") }}
{{ javascript_include("js/ui.js") }}
{{ javascript_include("js/purchases/autocomplete.js") }}
{#{ javascript_include("lib/bootstrap/js/dropdown.js") }#}
{# Include main styles that are used all across the module. #}
<link href='http://fonts.googleapis.com/css?family=Oxygen:400,700' rel='stylesheet' type='text/css'>
{{ stylesheet_link('lib/font-awesome/css/font-awesome.min.css') }}
{#{ stylesheet_link('lib/bootstrap/dist/css/bootstrap.css') }#}
{{ stylesheet_link('lib/jquery-ui/jquery-ui.min.css') }}
{{ stylesheet_link('lib/jquery-ui/jquery-ui.theme.min.css') }}
{{ stylesheet_link('css/styles.css') }}
{# Main Menu. #}
{# ========================================================================== #}
<div id="main-menu-container">
  <div id="main-menu-header">
    <img class="arrow" src="{{ url('img/arrow-big-left.png') }}"/>
  </div>
  <div id="main-menu">
    <ul class="module-menu-list">
      {{ link_to("index", "<li><i class='fa fa-home fa-lg fa-fw'></i><span>Dashboard</span></li>") }}
      {{ link_to("index", "<li><i class='fa fa-folder fa-lg fa-fw'></i><span>Presales</span></li>") }}
      <div class='main-menu-item purchases'>
        {{ link_to("index", "<li><i class='fa fa-credit-card fa-lg fa-fw'></i><span>Purchases</span></li>") }}
        <div class="float-menu-cont hidden">
          <div class="float-pointer"></div>
          <div class="menu-container">
            <div class="menu-container-left">
              <ul>
                {{ link_to("company/list?type=supplier", "Inventory Suppliers") }}
                <br>
                <div class="float-menu-separator"></div>
                <div class="menu-pending">{{ link_to("po/search", "Pre-purchase request") }}</div>
                <div class="menu-pending">{{ link_to("po/search", "To-be Purchased") }}</div>
                <div class="menu-pending">{{ link_to("po/search", "To-be Allocated") }}</div>
                <div class="float-menu-separator"></div>
                {{ link_to("po/search", "All PO's") }}
                <br>
                {{ link_to("po/search?scope=unapproved", "UnApproved PO's") }}
                <br>
                {{ link_to("po/search?scope=pending", "Pending PO's") }}
              </ul>
            </div>
            <div class="menu-container-right">
              <ul>
                {{ link_to("po/search", "All Supplier Invoices") }}
                <br>
                {{ link_to("po/search", "Not Received") }}
                <div class="float-menu-separator"></div>
                <div class="menu-pending">{{ link_to("po/search", "Freight Bills") }}</div>
                {{ link_to("company/list?type=freight", "Freight Vendors") }}
                <div class="float-menu-separator"></div>
                {{ link_to("po/index?scope=global", "New Global PO") }}
                <br>
                {{ link_to("po/search?scope=global", "Global PO's") }}
                <br>
                {{ link_to("po/list?scope=global", "Global PO's Search") }}
              </ul>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="search-bar-container">
            {{ form('po/search', 'method': 'post', 'id': 'search-bar-form') }}
              {#{ text_field("pur-menu-search", "size": 100, "placeholder": "Search Purchases") }#}
              {{ text_field("supplier_name", "size": 50, 'class': 'triton-autocomplete'
                        , 'data-group': 'supplier', 'data-local-group': 
                        'triton-autocomplete-supplier', "placeholder": "Search Purchases") }}
              {{ hidden_field("supplier", "class": "triton-autocomplete-supplier supplier") }}
            {{ end_form() }}
          </div>
        </div>
      </div>
      {#{ link_to("index", "<li><i class='fa fa-truck fa-lg fa-fw'></i><span>Inventory</span></li>") }#}
      <div class='main-menu-item inventory'>
        <a href="/triton/client/#/inventory"><li><i class='fa fa-truck fa-lg fa-fw'></i><span>Inventory</span></li></a>
        <div class="float-menu-cont hidden">
          <div class="float-pointer"></div>
          <div class="menu-container">
            <div class="menu-container-left">
              <ul>
                <a href="/triton/client/#/inventory">All Products</a>
                <br>
                <a href="/triton/client/#/inventory/create">New Product</a>
                <br>
                <div class="float-menu-separator"></div>
                <div class="menu-pending">Stock Products</div>
                <div class="menu-pending">Non Stock Products</div>
                <div class="float-menu-separator"></div>
                <a href="/triton/client/#/inventory/items/add">Inventory Manual Submitting</a>
                <br>
                <div class="float-menu-separator"></div>
                <div class="menu-pending">In Transit</div>
                <div class="menu-pending">Calendar</div>
              </ul>
            </div>
            <div class="menu-container-right">
              <ul>
                <div class="menu-pending">Inventory Search</div>
                <div class="menu-pending">Inventory By Type</div>
                <div class="menu-pending">Inventory By Location</div>
                <div class="float-menu-separator"></div>
                <a href="/triton/client/#/inventory/returns/list">Inventory Returns</a>
                <br>
                <div class="float-menu-separator"></div>
                <a href="/triton/client/#/inventory/van/create">Vans Create</a>
                <br>
                <a href="/triton/client/#/inventory/van/inventory">Vans Inventory</a>
                <br>
                <div class="float-menu-separator"></div>
                <a href="/triton/client/#/inventory/consignment/list">Consignments</a>
                <br>
              </ul>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="search-bar-container">
            <input type="text" name="supplier_name" class="triton-autocomplete">
          </div>
        </div>
      </div>
      {{ link_to("index", "<li><i class='fa fa-tag fa-lg fa-fw'></i><span>Sales</span></li>") }}
      {{ link_to("index", "<li><i class='fa fa-pencil fa-lg fa-fw'></i><span>Accounting</span></li>") }}
      {{ link_to("index", "<li><i class='fa fa-bar-chart fa-lg fa-fw'></i><span>Reports</span></li>") }}
    </ul>
    <ul class="admin-menu-list">
      {{ link_to("index", "<li><i class='fa fa-unlock fa-lg fa-fw'></i><span>Admin</span></li>") }}
      {{ link_to("index", "<li><i class='fa fa-cog fa-lg fa-fw'></i><span>Setup</span></li>") }}
      {{ link_to("index", "<li><i class='fa fa-wrench fa-lg fa-fw'></i><span>Support</span></li>") }}
    </ul>
  </div>
</div>
<div id="master-container">
  {# Header 1. #}
  {# ======================================================================== #}
  <div id="header-container">
    <div id="logo-container">
      <a href="{{ url('index') }}">
        <img src="{{ url('img/logo-top.png') }}"/>
      </a>
		</div>
    <div id="header-right-container">
      <div id="header-search">
        {{ form("search") }}
          <input id="s" class="triton-autocomplete" type="text" name="s" 
                value="" placeholder="Quick Search" data-group="search">
        </form>
      </div>
      <div id="header-right-icons">
        <img src="{{ url('img/prototype/header-right-icons.png') }}"/>
      </div>
      <div id="header-user-menu-button">
        <div class="name-text">Josh Kessler</div>
        <img class="arrow" src="{{ url('img/arrow-down.png') }}"/>
      </div>
    </div>
    <div class="clearfix"></div>
  </div>
  {# Header 2. #}
  {# ======================================================================== #}
  <div id="secondary-navigation-container">
    <div class="secondary-texts">
      <span class="big-title">{{ title }}</span>
      <span class="subtitle">{{ subtitle }}</span>
    </div>
    <div class="secondary-controls">
      {% if show_cancel %}
        <input type="button" value="{{ cancel_text }}" id="cancel-top" data-exit="{{ exit_to }}" class="button-top">
      {% endif %}
      {% if show_submit %}
        <input type="button" value="{{ submit_text }}" id="submit-top" data-main-form="{{ main_form_id }}"  data-route="{{ route_to }}" class="button-top">
      {% endif %}
    </div>
  </div>
  {# Content. #}
  {# ======================================================================== #}
  <div class="main-content-container {{ page_id }}">
    {{ content() }}
  </div>
  {# Footer. #}
  {# ======================================================================== #}
	<div id="footer">
	</div>
</div>