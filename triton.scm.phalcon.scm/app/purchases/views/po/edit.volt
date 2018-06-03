{# /app/purchases/views/po/edit.volt #}
{{ javascript_include("js/purchases/po.js") }}
{{ form('po/search', 'method': 'post', 'id': main_form_id) }}
  {# Left Column with general info #}
  {# ============================= #}
  <div class="left-column">
    <div class="form-field-group-container size-p-100">
      <div class="form-field-group">
        {{ hidden_field("poid", "value": po.poid) }}
        <h3>Supplier:</h3>
        <h2>{{ supplier.company_name }}</h2>
        <p>
          {{ supplier.address }} {{ supplier.address2 }}, {{ supplier.city }}, {{ supplier.state }} {{ supplier.zip_code }}
        </p>
        <br>
        <h3>Ship to:</h3>
        <h2>{{ ship_to.name }}</h2>
        <p>
          {{ ship_to.address }} {{ ship_to.address2 }}, {{ ship_to.city }}, {{ ship_to.state }} {{ ship_to.zip_code }}
        </p>
        <br>
        <h3>Purchase Location:</h3>
        <h2>{{ location.name }}</h2>
        <p>
          {{ location.address }} {{ location.address2 }}, {{ location.city }}, {{ location.state }} {{ location.zip_code }}
        </p>
      </div>
    </div>
    {% if po.approval_status != 'approved' %}
      <input type="button" value="Approve PO" id="approve-po-button" class="button-transparent">
    {% else %}
      <div class="button-tag-green">Approved</div>
    {% endif %}
    <input type="button" value="Send PO" id="send-po-button" class="button-transparent">
  </div>
  <div class="right-column">
    <div class="form-field-group-container size-p-100 last">
      <div class="form-field-group">
        <div class="title">Products</div>
        {# New Product Form #}
        {# ============================= #}
        <div class="form-field size-p-50">
          <label for="product">Product Name</label>
          {{ text_field("product", "size": 100, 'class': 'triton-autocomplete'
                        , 'data-group': 'product', 'data-local-group': 'triton-autocomplete-product') }}
          {{ hidden_field("product_id", "class": "triton-autocomplete-product product_id") }}
          {{ hidden_field("product_type", "class": "triton-autocomplete-product product_type") }}
        </div>
        <div class="form-field size-p-50">
          <label for="sku">Sku</label>
          {{ text_field("sku", "size": 100, "class": 
            "triton-autocomplete-product sku", 'readonly': '') }}
        </div>
        <div class="form-field size-p-10">
          <label for="so">S.O:</label>
          {{ text_field("so", "size": 100) }}
        </div>
        <div class="form-field size-p-90">
          <label for="description">Description</label>
          {{ text_field("description", "size": 100, "class": 
            "triton-autocomplete-product description", 'readonly': '') }}
        </div>
        <div class="form-field size-p-100">
          <label for="note">Supplier / Purchasing Note</label>
          {{ text_field("note", "size": 100) }}
        </div>
        <div class="form-field size-p-15">
          <label for="pack_quant">Pkg. Qty.</label>
          {{ text_field("pack_quant", "size": 100) }}
        </div>
        <div class="form-field size-p-15">
          <label for="pack_unit">Pkg. Unit</label>
          {{ select("pack_unit", units_list, 'using': ['uid', 'name'],
                    'useEmpty': true, 'emptyText': '...', 'emptyValue': '0') }}
        </div>
        <div class="form-field size-p-20">
          <label for="each_pack_unit">Eachs per Pkg. Unit</label>
          {{ text_field("each_pack_unit", "size": 100) }}
        </div>
        <div class="form-field size-p-15">
          <label for="quantity">Quantity</label>
          {{ text_field("quantity", "size": 100) }}
        </div>
        <div class="form-field size-p-15">
          <label for="units">Units</label>
          {{ select("units", units_list, 'using': ['uid', 'name'],
                    'useEmpty': true, 'emptyText': '...', 'emptyValue': '0') }}
        </div>
        <div class="form-field size-p-15">
          <label for="unit_price">Unit Price</label>
          {{ text_field("unit_price", "size": 100, "class": 
            "triton-autocomplete-product unit_cost") }}
        </div>
        <div class="form-field size-p-15">
          <label for="extended">Extended</label>
          {{ text_field("extended", "size": 100) }}
        </div>
        <div class="clearfix"></div>
        <div class="slab-details" id="slab-details-add">
          <p class="title">Slab Product Details</p>
          <div class="form-field size-p-15">
            <label for="slabs_per_bundle">Slabs / Bundle</label>
            {{ text_field("slabs_per_bundle", "size": 100) }}
          </div>
          <div class="form-field size-p-15">
            <label for="width">Width</label>
            {{ text_field("width", "size": 100) }}
          </div>
          <div class="form-field size-p-15">
            <label for="height">Height</label>
            {{ text_field("height", "size": 100) }}
          </div>
          <div class="form-field size-p-15">
            <label for="thickness">Thickness</label>
            {{ text_field("thickness", "size": 100) }}
          </div>
          <div class="form-field size-p-20">
            <label for="codebar">Barcodes From</label>
            {{ text_field("codebar", "size": 100) }}
          </div>
          <div class="button-container">
            <input type="button" value="Bundles Detail" id="bundles-detail-button" class="button-transparent">
          </div>
          <div class="slab-more-details hidden" id="slab-more-details-add">
          </div>
          {# Bundle Detail Prototype #}
          <div id="slab-bundle-details-prototype">
            <div class="slab-bundle-details">
              <p class="title">Bundle 1</p>
              <div class="form-field size-p-20">
                <label for="slabs_per_bundle">Slabs Amount</label>
                {{ text_field("slabs_per_bundle", "size": 100, "class":"bundle_slabs_per_bundle") }}
              </div>
              <div class="form-field size-p-20">
                <label for="width">Width</label>
                {{ text_field("width", "size": 100, "class":"bundle_width") }}
              </div>
              <div class="form-field size-p-20">
                <label for="height">Height</label>
                {{ text_field("height", "size": 100, "class":"bundle_height") }}
              </div>
              <div class="form-field size-p-20">
                <label for="thickness">Thickness</label>
                {{ text_field("thickness", "size": 100, "class":"bundle_thickness") }}
              </div>
              <div class="button-container">
                <input type="button" value="View Slabs" class="slabs-detail-button button-transparent">
              </div>
              <div class="slabs-list hidden">
              </div>
            </div>
          </div>
          {# Slab row prototype #}
          <div id="slab-row-prototype">
            <div class="slab-row">
              <div class="slab-label">Slab 1 - 1</div>
              <div class="form-field size-p-20">
                <label for="width">Width</label>
                {{ text_field("width", "size": 100, "class":"slab_width") }}
              </div>
              <div class="form-field size-p-20">
                <label for="height">Height</label>
                {{ text_field("height", "size": 100, "class":"slab_height") }}
              </div>
              <div class="form-field size-p-20">
                <label for="thickness">Thickness</label>
                {{ text_field("thickness", "size": 100, "class":"slab_thickness") }}
              </div>
              <div class="clearfix"></div>
            </div>
          </div>          
          <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        <div class="button-container">
          <input type="button" value="Add" id="add-product-button" class="button-orange">
        </div>
        {# Products List #}
        {# ============= #}
        <div class="list-table po-prods-list-table">
          <div class="list-table-header">
            <div class="list-field pop-list-field pop-list-field-pre">
              <div class="list-field-content">
              </div>
            </div>
            <div class="list-field pop-list-field pop-list-field-so">
              <div class="list-field-content">
                SO
              </div>
            </div>
            <div class="list-field pop-list-field pop-list-field-prod-sku">
              <div class="list-field-content">
                Product (SKU)
              </div>
            </div>
            <div class="list-field pop-list-field pop-list-field-desc">
              <div class="list-field-content">
                Description
              </div>
            </div>
            <div class="list-field pop-list-field pop-list-field-slabs">
              <div class="list-field-content">
                Slabs
              </div>
            </div>
            <div class="list-field pop-list-field pop-list-field-qty">
              <div class="list-field-content">
                Qty
              </div>
            </div>
            <div class="list-field pop-list-field pop-list-field-uom">
              <div class="list-field-content">
                UOM
              </div>
            </div>
            <div class="list-field pop-list-field pop-list-field-price">
              <div class="list-field-content">
                Unit Price
              </div>
            </div>
            <div class="list-field pop-list-field pop-list-field-extended">
              <div class="list-field-content">
                Extended
              </div>
            </div>
            <div class="list-field pop-list-field pop-list-field-actions">
              <div class="list-field-content">
                Fulfill
              </div>
            </div>
          </div>
          <div class="list-table-body">
            {% for index, item in products %}
              <div class="list-table-row">
                <div class="list-field pop-list-field pop-list-field-pre">
                  <div class="list-field-content">
                    <i class="fa fa-times" data-ppid="{{ item.ppid }}"></i>
                  </div>
                  {{ hidden_field("pid", "class": "pid", "value": item.pid) }}
                  {{ hidden_field("ppid", "class": "ppid", "value": item.ppid) }}
                  {{ hidden_field("type", "class": "type", "value": item.type) }}
                </div>
                <div class="list-field pop-list-field pop-list-field-so">
                  <div class="list-field-content">
                    {{ item.so }}
                  </div>
                </div>
                <div class="list-field pop-list-field pop-list-field-prod-sku">
                  <div class="list-field-content">
                    {{ item.name }} ({{ item.sku }})
                  </div>
                </div>
                <div class="list-field pop-list-field pop-list-field-desc">
                  <div class="list-field-content">
                    {{ item.description }}
                  </div>
                </div>
                <div class="list-field pop-list-field pop-list-field-slabs">
                  <div class="list-field-content">
                    {{ item.slabs_total }}
                  </div>
                  {{ hidden_field("slabs_data", "class": "slabs_data", "value": item.slabs_data) }}
                </div>
                <div class="list-field pop-list-field pop-list-field-qty">
                  <div class="list-field-content">
                    {{ item.quantity }}
                  </div>
                </div>
                <div class="list-field pop-list-field pop-list-field-uom">
                  <div class="list-field-content">
                    {{ item.units }}
                  </div>
                </div>
                <div class="list-field pop-list-field pop-list-field-price">
                  <div class="list-field-content">
                    {{ item.unit_price }}
                  </div>
                </div>
                <div class="list-field pop-list-field pop-list-field-extended">
                  <div class="list-field-content">
                    {{ item.extended }}
                  </div>
                </div>
                <div class="list-field pop-list-field pop-list-field-actions">
                  <div class="list-field-content">
                    <div class="form-field size-p-100">
                      <label for="fulfillTotal">Total</label>
                      {{ check_field("fulfill", "value": "total", "checked":"", "class":"check-fulfill check-fulfill-total") }}
                      {{ text_field("fulfill_amount_total", "size": "5", "value":
                          item.quantity, 'readonly': '', "class": "fulfill_amount_total") }}
                    </div>
                    <div class="form-field size-p-100">
                      <label for="fulfillPartial">Partial</label>
                      {% if item.type == 1 %}
                        <input type="button" value=">" class="button-small fulfill-slab-prod">
                      {% else %}
                        {{ check_field("fulfill", "value": "partial", "class":"check-fulfill check-fulfill-partial") }}
                      {% endif %}
                      {{ text_field("fulfill_amount_partial", "size": "5", "value":item.quantity
                          , "class": "fulfill_amount_partial") }}
                    </div>
                  </div>
                </div>
                {% if item.type == 1 %}
                  <div class="clearfix"></div>
                  <div class="list-table-row-detail hidden">
                    {% for i_bundle, bundle in item.bundles.bundles %}
                      <div class="bundle-text">Bundle {{i_bundle + 1}}</div>
                      <div class="bundle-controls">
                        <div class="form-field size-p-40">
                          <label for="bundle_barcode">Barcodes from</label>
                          {{ text_field("bundle_barcode", "size": "15", "class": "bundle_barcode") }}
                        </div>
                        <div class="form-field size-p-40">
                          <label for="picture" class="bundle-picture-label">Bundle Picture</label>
                          <div class="fileinputs">
                            <input type="file" class="file" />
                            <div class="fakefile">
                              <input type="button" value="Upload" />
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="clearfix"></div>
                      {% for i_slab, slab in bundle.slabs %}
                        <div class="slab-row">
                          <div class="slab-text">Slab {{i_slab + 1}}</div>
                          <div class="slab-dimensions-text">({{slab.width}}x{{slab.height}}x{{slab.thickness}})</div>
                          <div class="slab-actions">{{ check_field("fulfill_slab", "value": "partial", "class":"check-fulfill check-fulfill-single-slab", "checked":"") }}</div>
                        </div>
                        <div class="clearfix"></div>
                      {% endfor %}
                      <br>
                    {% endfor %}
                  </div>
                {% endif %}
              </div>
            {% endfor %}
            {# Prototype of a row in the table. #}
            <div class="list-table-row-prototype">
              <div class="list-field pop-list-field pop-list-field-pre">
                <div class="list-field-content">
                  <i class="fa fa-times" data-ppid=""></i>
                </div>
                {{ hidden_field("pid", "class": "pid") }}
                {{ hidden_field("ppid", "class": "ppid") }}
                  {{ hidden_field("type", "class": "type") }}
              </div>
              <div class="list-field pop-list-field pop-list-field-so">
                <div class="list-field-content"></div>
              </div>
              <div class="list-field pop-list-field pop-list-field-prod-sku">
                <div class="list-field-content"></div>
              </div>
              <div class="list-field pop-list-field pop-list-field-desc">
                <div class="list-field-content"></div>
              </div>
              <div class="list-field pop-list-field pop-list-field-slabs">
                <div class="list-field-content"></div>
                <input type="hidden" name="slabs_data" value="" class="slabs_data">
              </div>
              <div class="list-field pop-list-field pop-list-field-qty">
                <div class="list-field-content"></div>
              </div>
              <div class="list-field pop-list-field pop-list-field-uom">
                <div class="list-field-content"></div>
              </div>
              <div class="list-field pop-list-field pop-list-field-price">
                <div class="list-field-content">
                  {{ item.unit_price }}
                </div>
              </div>
              <div class="list-field pop-list-field pop-list-field-extended">
                <div class="list-field-content">
                  {{ item.extended }}
                </div>
              </div>
              <div class="list-field pop-list-field pop-list-field-actions">
                <div class="list-field-content">
                  <div class="form-field size-p-100">
                    <label for="fulfillTotal">Total</label>
                    {{ check_field("fulfill", "value": "total", "checked":"", "class":"check-fulfill check-fulfill-total") }}
                    {{ text_field("fulfill_amount_total", "size": "5", "value":
                      item.quantity, 'readonly': '', "class": "fulfill_amount_total") }}
                  </div>
                  <div class="form-field size-p-100">
                    <label for="fulfillPartial">Partial</label>
                    <input type="button" value=">" class="button-small fulfill-slab-prod">
                    {{ check_field("fulfill", "value": "partial", "class":"check-fulfill check-fulfill-partial") }}
                    {{ text_field("fulfill_amount_partial", "size": "5", "value":item.quantity
                      , "class": "fulfill_amount_partial") }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="fulfill-button-container">
          <input type="button" value="Received Items" id="fulfill-general-button" class="button-transparent">
        </div>
        {# Totals #}
        <div class="totals-container">
          <div class="subtotal">
            <div class="label">Subtotal:</div>
            <div class="value">${{ po.total }}</div>
          </div>
          <div class="tax">
            <div class="label">Tax:</div>
            <div class="value">$0.00</div>
          </div>
          <div class="total">
            <div class="label">Total:</div>
            <div class="value">${{ po.total }}</div>
          </div>
        </div>
      </div>
    </div>
    {# New Invoice Form #}
    {# ============================= #}
    <div class="form-field-group-container size-p-100 last">
      <div class="form-field-group">
        <div class="title">Invoices</div>
        {# New Invoice Form #}
        {# ============================= #}
        <div class="form-field size-p-30">
          <label for="transaction_name">Transaction</label>
          {{ text_field("transaction_name", "size": 100) }}
        </div>
        <div class="form-field size-p-15">
          <label for="number">Number</label>
          {{ text_field("number", "size": 100) }}
        </div>
        <div class="form-field size-p-15">
          <label for="container_number">Container #</label>
          {{ text_field("container_number", "size": 100) }}
        </div>
        <div class="form-field size-p-15">
          <label for="eta_date">ETA Date</label>
          {{ text_field("eta_date", "size": 50, 'class': 'triton-datepicker') }}
        </div>
        <div class="form-field size-p-15">
          <label for="received_date">Received Date</label>
          {{ text_field("received_date", "size": 50, 'class': 'triton-datepicker') }}
        </div>
        <div class="form-field size-p-15">
          <label for="total">Total</label>
          {{ text_field("total", "size": 100) }}
        </div>
        <div class="clearfix"></div>
        <div class="button-container">
          <input type="button" value="Add" id="add-invoice-button" class="button-orange">
        </div>
        {# Invoices List #}
        {# ============= #}
        <div class="list-table po-invoices-list-table">
          <div class="list-table-header">
            <div class="list-field poi-list-field poi-list-field-date">
              <div class="list-field-content">
                Date
              </div>
            </div>
            <div class="list-field poi-list-field poi-list-field-transaction">
              <div class="list-field-content">
                Transaction
              </div>
            </div>
            <div class="list-field poi-list-field poi-list-field-number">
              <div class="list-field-content">
                Invoice #
              </div>
            </div>
            <div class="list-field poi-list-field poi-list-field-total">
              <div class="list-field-content">
                Total
              </div>
            </div>
            <div class="list-field poi-list-field poi-list-field-eta">
              <div class="list-field-content">
                ETA Date
              </div>
            </div>
            <div class="list-field poi-list-field poi-list-field-received">
              <div class="list-field-content">
                Received
              </div>
            </div>
            <div class="list-field poi-list-field poi-list-field-container">
              <div class="list-field-content">
                Container #
              </div>
            </div>
          </div>
          <div class="list-table-body">
            {% for index, item in invoices %}
              <div class="list-table-row">
                <div class="list-field poi-list-field poi-list-field-date">
                  <div class="list-field-content">
                    {{ item.date }}
                  </div>
                </div>
                <div class="list-field poi-list-field poi-list-field-transaction">
                  <div class="list-field-content">
                    {{ item.description }}
                  </div>
                </div>
                <div class="list-field poi-list-field poi-list-field-number">
                  <div class="list-field-content">
                    {{ item.number }}
                  </div>
                </div>
                <div class="list-field poi-list-field poi-list-field-total">
                  <div class="list-field-content">
                    ${{ item.total }}
                  </div>
                </div>
                <div class="list-field poi-list-field poi-list-field-eta">
                  <div class="list-field-content">
                    {{ item.eta_date }}
                  </div>
                </div>
                <div class="list-field poi-list-field poi-list-field-received">
                  <div class="list-field-content">
                    {{ item.received_date }}
                  </div>
                </div>
                <div class="list-field poi-list-field poi-list-field-container">
                  <div class="list-field-content">
                    {{ item.container_number }}
                  </div>
                </div>
              </div>
            {% endfor %}
            {# Prototype of a row in the table. #}
            <div class="list-table-poi-row-prototype">
              <div class="list-field poi-list-field poi-list-field-date">
                <div class="list-field-content"></div>
              </div>
              <div class="list-field poi-list-field poi-list-field-transaction">
                <div class="list-field-content"></div>
              </div>
              <div class="list-field poi-list-field poi-list-field-number">
                <div class="list-field-content"></div>
              </div>
              <div class="list-field poi-list-field poi-list-field-total">
                <div class="list-field-content"></div>
              </div>
              <div class="list-field poi-list-field poi-list-field-eta">
                <div class="list-field-content"></div>
              </div>
              <div class="list-field poi-list-field poi-list-field-received">
                <div class="list-field-content"></div>
              </div>
              <div class="list-field poi-list-field poi-list-field-container">
                <div class="list-field-content"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
{{ end_form() }}