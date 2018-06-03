
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
      <?php foreach ($page->items as $index => $item) { ?>
        <div class="list-table-row">
          <div class="list-field po-list-field po-list-field-po">
            <div class="list-field-content">
              <a href="<?php echo $this->url->get('po/edit'); ?>?po=<?php echo $item->poid; ?>">
                <?php echo $item->poid; ?>
              </a>
            </div>
          </div>
          <div class="list-field po-list-field po-list-field-date">
            <div class="list-field-content">
              <?php echo $item->date; ?>
            </div>
          </div>
          <div class="list-field po-list-field po-list-field-supplier">
            <div class="list-field-content">
              <a href="<?php echo $this->url->get('company/edit'); ?>?type=supplier&cid=<?php echo $item->supplier; ?>">
                <?php echo $item->supplier_name; ?>
              </a>
            </div>
          </div>
          <div class="list-field po-list-field po-list-field-container">
            <div class="list-field-content">
              <?php echo $item->container_number; ?>
            </div>
          </div>
          <div class="list-field po-list-field po-list-field-customer">
            <div class="list-field-content">
              <?php echo $item->supplier_so_number; ?>
            </div>
          </div>
          <div class="list-field po-list-field po-list-field-status">
            <div class="list-field-content <?php echo $item->status; ?>">
              <?php echo $item->status; ?>
            </div>
          </div>
          <div class="list-field po-list-field po-list-field-total">
            <div class="list-field-content">
              $<?php echo $item->total; ?>
            </div>
          </div>
          <div class="list-field po-list-field po-list-field-approval">
            <div class="list-field-content ">
              <?php if ($item->approval_status == 'approved') { ?>
                <i class="fa fa-check-circle fa-2x"></i>
              <?php } else { ?>
                <i class="fa fa-info-circle fa-2x"></i>
              <?php } ?>
              
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</div>

<a href="/triton/po/search">First</a>
<a href="/triton/po/search?page=<?php echo $page->before; ?>">Previous</a>
<a href="/triton/po/search?page=<?php echo $page->next; ?>">Next</a>
<a href="/triton/po/search?page=<?php echo $page->last; ?>">Last</a>

<p>You are in page <?php echo $page->current; ?> of <?php echo $page->total_pages; ?></p>
