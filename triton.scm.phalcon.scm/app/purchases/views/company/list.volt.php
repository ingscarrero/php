
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
      <?php foreach ($page->items as $index => $item) { ?>
        <div class="list-table-row">
          <div class="list-field po-list-field po-list-field-po">
            <div class="list-field-content">
              <a href="<?php echo $this->url->get('company/edit'); ?>?type=<?php echo $company_type; ?>&cid=<?php echo $item->company_id; ?>">
                <?php echo $item->company_id; ?>
              </a>
            </div>
          </div>
          <div class="list-field po-list-field po-list-field-supplier">
            <div class="list-field-content">
              <a href="<?php echo $this->url->get('company/edit'); ?>?type=<?php echo $company_type; ?>&cid=<?php echo $item->company_id; ?>">
                <?php echo $item->company_name; ?>
              </a>
            </div>
          </div>
          <div class="list-field po-list-field company-list-field-phone">
            <div class="list-field-content">
              <?php echo $item->phones; ?>
            </div>
          </div>
          <div class="list-field po-list-field company-list-field-address">
            <div class="list-field-content">
              <?php echo $item->address; ?> <?php echo $item->address2; ?>
            </div>
          </div>
          <div class="list-field po-list-field po-list-field-status">
            <div class="list-field-content <?php echo $item->company_name; ?>">
              <?php echo $item->city; ?>
            </div>
          </div>
          <div class="list-field po-list-field po-list-field-total">
            <div class="list-field-content">
              <?php echo $item->state; ?>
            </div>
          </div>
          <div class="list-field po-list-field po-list-field-approval">
            <div class="list-field-content ">
              <?php echo $item->zip_code; ?>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</div>

<a href="/triton/company/list?type=<?php echo $company_type; ?>">First</a>
<a href="/triton/company/list?type=<?php echo $company_type; ?>&page=<?php echo $page->before; ?>">Previous</a>
<a href="/triton/company/list?type=<?php echo $company_type; ?>&page=<?php echo $page->next; ?>">Next</a>
<a href="/triton/company/list?type=<?php echo $company_type; ?>&page=<?php echo $page->last; ?>">Last</a>

<p>You are in page <?php echo $page->current; ?> of <?php echo $page->total_pages; ?></p>
