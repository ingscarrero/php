{# /app/purchases/views/po/search.volt #}
<div class="form-field-group-container size-p-100 last">
  <div class="list-table po-list-table">
    <div class="list-table-header">
      <div class="list-field po-list-field po-list-field-po">
        <div class="list-field-content">
          PO
        </div>
      </div>
      <div class="list-field po-list-field po-list-field-date">
        <div class="list-field-content">
          Date
        </div>
      </div>
      <div class="list-field po-list-field po-list-field-supplier">
        <div class="list-field-content">
          Inventory Supplier
        </div>
      </div>
      <div class="list-field po-list-field po-list-field-container">
        <div class="list-field-content">
          Container
        </div>
      </div>
      <div class="list-field po-list-field po-list-field-customer">
        <div class="list-field-content">
          Customer Ref
        </div>
      </div>
      <div class="list-field po-list-field po-list-field-status">
        <div class="list-field-content">
          Status
        </div>
      </div>
      <div class="list-field po-list-field po-list-field-total">
        <div class="list-field-content">
          Total
        </div>
      </div>
      <div class="list-field po-list-field po-list-field-approval">
        <div class="list-field-content">
          Approval
        </div>
      </div>
    </div>
    <div class="list-table-body">
      {% for index, item in page.items %}
        <div class="list-table-row">
          <div class="list-field po-list-field po-list-field-po">
            <div class="list-field-content">
              <a href="{{ url('po/edit') }}?po={{ item.poid }}">
                {{ item.poid }}
              </a>
            </div>
          </div>
          <div class="list-field po-list-field po-list-field-date">
            <div class="list-field-content">
              {{ item.date }}
            </div>
          </div>
          <div class="list-field po-list-field po-list-field-supplier">
            <div class="list-field-content">
              <a href="{{ url('company/edit') }}?type=supplier&cid={{ item.supplier }}">
                {{ item.supplier_name }}
              </a>
            </div>
          </div>
          <div class="list-field po-list-field po-list-field-container">
            <div class="list-field-content">
              {{ item.container_number }}
            </div>
          </div>
          <div class="list-field po-list-field po-list-field-customer">
            <div class="list-field-content">
              {{ item.supplier_so_number }}
            </div>
          </div>
          <div class="list-field po-list-field po-list-field-status">
            <div class="list-field-content {{ item.status }}">
              {{ item.status }}
            </div>
          </div>
          <div class="list-field po-list-field po-list-field-total">
            <div class="list-field-content">
              ${{ item.total }}
            </div>
          </div>
          <div class="list-field po-list-field po-list-field-approval">
            <div class="list-field-content ">
              {% if item.approval_status == "approved" %}
                <i class="fa fa-check-circle fa-2x"></i>
              {% else %}
                <i class="fa fa-info-circle fa-2x"></i>
              {% endif %}
              
            </div>
          </div>
        </div>
      {% endfor %}
    </div>
  </div>
</div>
{# Pager #}
<a href="/triton/po/search">First</a>
<a href="/triton/po/search?page={{ page.before }}">Previous</a>
<a href="/triton/po/search?page={{ page.next }}">Next</a>
<a href="/triton/po/search?page={{ page.last }}">Last</a>

<p>You are in page {{ page.current }} of {{ page.total_pages }}</p>
