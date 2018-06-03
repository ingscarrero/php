{# /app/purchases/views/company/list.volt #}
<div class="form-field-group-container size-p-100 last">
  <div class="list-table po-list-table">
    <div class="list-table-header">
      <div class="list-field po-list-field po-list-field-po">
        <div class="list-field-content">
          ID
        </div>
      </div>
      <div class="list-field po-list-field po-list-field-supplier">
        <div class="list-field-content">
          Name
        </div>
      </div>
      <div class="list-field po-list-field company-list-field-phone">
        <div class="list-field-content">
          Phone
        </div>
      </div>
      <div class="list-field po-list-field company-list-field-address">
        <div class="list-field-content">
          Address
        </div>
      </div>
      <div class="list-field po-list-field po-list-field-status">
        <div class="list-field-content">
          City
        </div>
      </div>
      <div class="list-field po-list-field po-list-field-total">
        <div class="list-field-content">
          State
        </div>
      </div>
      <div class="list-field po-list-field po-list-field-approval">
        <div class="list-field-content">
          Zip Code
        </div>
      </div>
    </div>
    <div class="list-table-body">
      {% for index, item in page.items %}
        <div class="list-table-row">
          <div class="list-field po-list-field po-list-field-po">
            <div class="list-field-content">
              <a href="{{ url('company/edit') }}?type={{ company_type }}&cid={{ item.company_id }}">
                {{ item.company_id }}
              </a>
            </div>
          </div>
          <div class="list-field po-list-field po-list-field-supplier">
            <div class="list-field-content">
              <a href="{{ url('company/edit') }}?type={{ company_type }}&cid={{ item.company_id }}">
                {{ item.company_name }}
              </a>
            </div>
          </div>
          <div class="list-field po-list-field company-list-field-phone">
            <div class="list-field-content">
              {{ item.phones }}
            </div>
          </div>
          <div class="list-field po-list-field company-list-field-address">
            <div class="list-field-content">
              {{ item.address }} {{ item.address2 }}
            </div>
          </div>
          <div class="list-field po-list-field po-list-field-status">
            <div class="list-field-content {{ item.company_name }}">
              {{ item.city }}
            </div>
          </div>
          <div class="list-field po-list-field po-list-field-total">
            <div class="list-field-content">
              {{ item.state }}
            </div>
          </div>
          <div class="list-field po-list-field po-list-field-approval">
            <div class="list-field-content ">
              {{ item.zip_code }}
            </div>
          </div>
        </div>
      {% endfor %}
    </div>
  </div>
</div>
{# Pager #}
<a href="/triton/company/list?type={{ company_type }}">First</a>
<a href="/triton/company/list?type={{ company_type }}&page={{ page.before }}">Previous</a>
<a href="/triton/company/list?type={{ company_type }}&page={{ page.next }}">Next</a>
<a href="/triton/company/list?type={{ company_type }}&page={{ page.last }}">Last</a>

<p>You are in page {{ page.current }} of {{ page.total_pages }}</p>
