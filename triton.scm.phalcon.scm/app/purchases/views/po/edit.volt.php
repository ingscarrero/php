
<?php echo $this->tag->javascriptInclude('js/purchases/po.js'); ?>
<?php echo $this->tag->form(array('po/search', 'method' => 'post', 'id' => $main_form_id)); ?>
  
  
  <div class="left-column">
    <div class="form-field-group-container size-p-100">
      <div class="form-field-group">
        <?php echo $this->tag->hiddenField(array('poid', 'value' => $po->poid)); ?>
        <h3>Supplier:</h3>
        <h2><?php echo $supplier->company_name; ?></h2>
        <p>
          <?php echo $supplier->address; ?> <?php echo $supplier->address2; ?>, <?php echo $supplier->city; ?>, <?php echo $supplier->state; ?> <?php echo $supplier->zip_code; ?>
        </p>
        <br>
        <h3>Ship to:</h3>
        <h2><?php echo $ship_to->name; ?></h2>
        <p>
          <?php echo $ship_to->address; ?> <?php echo $ship_to->address2; ?>, <?php echo $ship_to->city; ?>, <?php echo $ship_to->state; ?> <?php echo $ship_to->zip_code; ?>
        </p>
        <br>
        <h3>Purchase Location:</h3>
        <h2><?php echo $location->name; ?></h2>
        <p>
          <?php echo $location->address; ?> <?php echo $location->address2; ?>, <?php echo $location->city; ?>, <?php echo $location->state; ?> <?php echo $location->zip_code; ?>
        </p>
      </div>
    </div>
    <?php if ($po->approval_status != 'approved') { ?>
      <input type="button" value="Approve PO" id="approve-po-button" class="button-transparent">
    <?php } else { ?>
      <div class="button-tag-green">Approved</div>
    <?php } ?>
    <input type="button" value="Send PO" id="send-po-button" class="button-transparent">
  </div>
  <div class="right-column">
    <div class="form-field-group-container size-p-100 last">
      <div class="form-field-group">
        <div class="title">Products</div>
        
        
        <div class="form-field size-p-50">
          <label for="product">Product Name</label>
          <?php echo $this->tag->textField(array('product', 'size' => 100, 'class' => 'triton-autocomplete', 'data-group' => 'product', 'data-local-group' => 'triton-autocomplete-product')); ?>
          <?php echo $this->tag->hiddenField(array('product_id', 'class' => 'triton-autocomplete-product product_id')); ?>
          <?php echo $this->tag->hiddenField(array('product_type', 'class' => 'triton-autocomplete-product product_type')); ?>
        </div>
        <div class="form-field size-p-50">
          <label for="sku">Sku</label>
          <?php echo $this->tag->textField(array('sku', 'size' => 100, 'class' => 'triton-autocomplete-product sku', 'readonly' => '')); ?>
        </div>
        <div class="form-field size-p-10">
          <label for="so">S.O:</label>
          <?php echo $this->tag->textField(array('so', 'size' => 100)); ?>
        </div>
        <div class="form-field size-p-90">
          <label for="description">Description</label>
          <?php echo $this->tag->textField(array('description', 'size' => 100, 'class' => 'triton-autocomplete-product description', 'readonly' => '')); ?>
        </div>
        <div class="form-field size-p-100">
          <label for="note">Supplier / Purchasing Note</label>
          <?php echo $this->tag->textField(array('note', 'size' => 100)); ?>
        </div>
        <div class="form-field size-p-15">
          <label for="pack_quant">Pkg. Qty.</label>
          <?php echo $this->tag->textField(array('pack_quant', 'size' => 100)); ?>
        </div>
        <div class="form-field size-p-15">
          <label for="pack_unit">Pkg. Unit</label>
          <?php echo $this->tag->select(array('pack_unit', $units_list, 'using' => array('uid', 'name'), 'useEmpty' => true, 'emptyText' => '...', 'emptyValue' => '0')); ?>
        </div>
        <div class="form-field size-p-20">
          <label for="each_pack_unit">Eachs per Pkg. Unit</label>
          <?php echo $this->tag->textField(array('each_pack_unit', 'size' => 100)); ?>
        </div>
        <div class="form-field size-p-15">
          <label for="quantity">Quantity</label>
          <?php echo $this->tag->textField(array('quantity', 'size' => 100)); ?>
        </div>
        <div class="form-field size-p-15">
          <label for="units">Units</label>
          <?php echo $this->tag->select(array('units', $units_list, 'using' => array('uid', 'name'), 'useEmpty' => true, 'emptyText' => '...', 'emptyValue' => '0')); ?>
        </div>
        <div class="form-field size-p-15">
          <label for="unit_price">Unit Price</label>
          <?php echo $this->tag->textField(array('unit_price', 'size' => 100, 'class' => 'triton-autocomplete-product unit_cost')); ?>
        </div>
        <div class="form-field size-p-15">
          <label for="extended">Extended</label>
          <?php echo $this->tag->textField(array('extended', 'size' => 100)); ?>
        </div>
        <div class="clearfix"></div>
        <div class="slab-details" id="slab-details-add">
          <p class="title">Slab Product Details</p>
          <div class="form-field size-p-15">
            <label for="slabs_per_bundle">Slabs / Bundle</label>
            <?php echo $this->tag->textField(array('slabs_per_bundle', 'size' => 100)); ?>
          </div>
          <div class="form-field size-p-15">
            <label for="width">Width</label>
            <?php echo $this->tag->textField(array('width', 'size' => 100)); ?>
          </div>
          <div class="form-field size-p-15">
            <label for="height">Height</label>
            <?php echo $this->tag->textField(array('height', 'size' => 100)); ?>
          </div>
          <div class="form-field size-p-15">
            <label for="thickness">Thickness</label>
            <?php echo $this->tag->textField(array('thickness', 'size' => 100)); ?>
          </div>
          <div class="form-field size-p-20">
            <label for="codebar">Barcodes From</label>
            <?php echo $this->tag->textField(array('codebar', 'size' => 100)); ?>
          </div>
          <div class="button-container">
            <input type="button" value="Bundles Detail" id="bundles-detail-button" class="button-transparent">
          </div>
          <div class="slab-more-details hidden" id="slab-more-details-add">
          </div>
          
          <div id="slab-bundle-details-prototype">
            <div class="slab-bundle-details">
              <p class="title">Bundle 1</p>
              <div class="form-field size-p-20">
                <label for="slabs_per_bundle">Slabs Amount</label>
                <?php echo $this->tag->textField(array('slabs_per_bundle', 'size' => 100, 'class' => 'bundle_slabs_per_bundle')); ?>
              </div>
              <div class="form-field size-p-20">
                <label for="width">Width</label>
                <?php echo $this->tag->textField(array('width', 'size' => 100, 'class' => 'bundle_width')); ?>
              </div>
              <div class="form-field size-p-20">
                <label for="height">Height</label>
                <?php echo $this->tag->textField(array('height', 'size' => 100, 'class' => 'bundle_height')); ?>
              </div>
              <div class="form-field size-p-20">
                <label for="thickness">Thickness</label>
                <?php echo $this->tag->textField(array('thickness', 'size' => 100, 'class' => 'bundle_thickness')); ?>
              </div>
              <div class="button-container">
                <input type="button" value="View Slabs" class="slabs-detail-button button-transparent">
              </div>
              <div class="slabs-list hidden">
              </div>
            </div>
          </div>
          
          <div id="slab-row-prototype">
            <div class="slab-row">
              <div class="slab-label">Slab 1 - 1</div>
              <div class="form-field size-p-20">
                <label for="width">Width</label>
                <?php echo $this->tag->textField(array('width', 'size' => 100, 'class' => 'slab_width')); ?>
              </div>
              <div class="form-field size-p-20">
                <label for="height">Height</label>
                <?php echo $this->tag->textField(array('height', 'size' => 100, 'class' => 'slab_height')); ?>
              </div>
              <div class="form-field size-p-20">
                <label for="thickness">Thickness</label>
                <?php echo $this->tag->textField(array('thickness', 'size' => 100, 'class' => 'slab_thickness')); ?>
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
            <?php foreach ($products as $index => $item) { ?>
              <div class="list-table-row">
                <div class="list-field pop-list-field pop-list-field-pre">
                  <div class="list-field-content">
                    <i class="fa fa-times" data-ppid="<?php echo $item->ppid; ?>"></i>
                  </div>
                  <?php echo $this->tag->hiddenField(array('pid', 'class' => 'pid', 'value' => $item->pid)); ?>
                  <?php echo $this->tag->hiddenField(array('ppid', 'class' => 'ppid', 'value' => $item->ppid)); ?>
                  <?php echo $this->tag->hiddenField(array('type', 'class' => 'type', 'value' => $item->type)); ?>
                </div>
                <div class="list-field pop-list-field pop-list-field-so">
                  <div class="list-field-content">
                    <?php echo $item->so; ?>
                  </div>
                </div>
                <div class="list-field pop-list-field pop-list-field-prod-sku">
                  <div class="list-field-content">
                    <?php echo $item->name; ?> (<?php echo $item->sku; ?>)
                  </div>
                </div>
                <div class="list-field pop-list-field pop-list-field-desc">
                  <div class="list-field-content">
                    <?php echo $item->description; ?>
                  </div>
                </div>
                <div class="list-field pop-list-field pop-list-field-slabs">
                  <div class="list-field-content">
                    <?php echo $item->slabs_total; ?>
                  </div>
                  <?php echo $this->tag->hiddenField(array('slabs_data', 'class' => 'slabs_data', 'value' => $item->slabs_data)); ?>
                </div>
                <div class="list-field pop-list-field pop-list-field-qty">
                  <div class="list-field-content">
                    <?php echo $item->quantity; ?>
                  </div>
                </div>
                <div class="list-field pop-list-field pop-list-field-uom">
                  <div class="list-field-content">
                    <?php echo $item->units; ?>
                  </div>
                </div>
                <div class="list-field pop-list-field pop-list-field-price">
                  <div class="list-field-content">
                    <?php echo $item->unit_price; ?>
                  </div>
                </div>
                <div class="list-field pop-list-field pop-list-field-extended">
                  <div class="list-field-content">
                    <?php echo $item->extended; ?>
                  </div>
                </div>
                <div class="list-field pop-list-field pop-list-field-actions">
                  <div class="list-field-content">
                    <div class="form-field size-p-100">
                      <label for="fulfillTotal">Total</label>
                      <?php echo $this->tag->checkField(array('fulfill', 'value' => 'total', 'checked' => '', 'class' => 'check-fulfill check-fulfill-total')); ?>
                      <?php echo $this->tag->textField(array('fulfill_amount_total', 'size' => '5', 'value' => $item->quantity, 'readonly' => '', 'class' => 'fulfill_amount_total')); ?>
                    </div>
                    <div class="form-field size-p-100">
                      <label for="fulfillPartial">Partial</label>
                      <?php if ($item->type == 1) { ?>
                        <input type="button" value=">" class="button-small fulfill-slab-prod">
                      <?php } else { ?>
                        <?php echo $this->tag->checkField(array('fulfill', 'value' => 'partial', 'class' => 'check-fulfill check-fulfill-partial')); ?>
                      <?php } ?>
                      <?php echo $this->tag->textField(array('fulfill_amount_partial', 'size' => '5', 'value' => $item->quantity, 'class' => 'fulfill_amount_partial')); ?>
                    </div>
                  </div>
                </div>
                <?php if ($item->type == 1) { ?>
                  <div class="clearfix"></div>
                  <div class="list-table-row-detail hidden">
                    <?php foreach ($item->bundles->bundles as $i_bundle => $bundle) { ?>
                      <div class="bundle-text">Bundle <?php echo $i_bundle + 1; ?></div>
                      <div class="bundle-controls">
                        <div class="form-field size-p-40">
                          <label for="bundle_barcode">Barcodes from</label>
                          <?php echo $this->tag->textField(array('bundle_barcode', 'size' => '15', 'class' => 'bundle_barcode')); ?>
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
                      <?php foreach ($bundle->slabs as $i_slab => $slab) { ?>
                        <div class="slab-row">
                          <div class="slab-text">Slab <?php echo $i_slab + 1; ?></div>
                          <div class="slab-dimensions-text">(<?php echo $slab->width; ?>x<?php echo $slab->height; ?>x<?php echo $slab->thickness; ?>)</div>
                          <div class="slab-actions"><?php echo $this->tag->checkField(array('fulfill_slab', 'value' => 'partial', 'class' => 'check-fulfill check-fulfill-single-slab', 'checked' => '')); ?></div>
                        </div>
                        <div class="clearfix"></div>
                      <?php } ?>
                      <br>
                    <?php } ?>
                  </div>
                <?php } ?>
              </div>
            <?php } ?>
            
            <div class="list-table-row-prototype">
              <div class="list-field pop-list-field pop-list-field-pre">
                <div class="list-field-content">
                  <i class="fa fa-times" data-ppid=""></i>
                </div>
                <?php echo $this->tag->hiddenField(array('pid', 'class' => 'pid')); ?>
                <?php echo $this->tag->hiddenField(array('ppid', 'class' => 'ppid')); ?>
                  <?php echo $this->tag->hiddenField(array('type', 'class' => 'type')); ?>
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
                  <?php echo $item->unit_price; ?>
                </div>
              </div>
              <div class="list-field pop-list-field pop-list-field-extended">
                <div class="list-field-content">
                  <?php echo $item->extended; ?>
                </div>
              </div>
              <div class="list-field pop-list-field pop-list-field-actions">
                <div class="list-field-content">
                  <div class="form-field size-p-100">
                    <label for="fulfillTotal">Total</label>
                    <?php echo $this->tag->checkField(array('fulfill', 'value' => 'total', 'checked' => '', 'class' => 'check-fulfill check-fulfill-total')); ?>
                    <?php echo $this->tag->textField(array('fulfill_amount_total', 'size' => '5', 'value' => $item->quantity, 'readonly' => '', 'class' => 'fulfill_amount_total')); ?>
                  </div>
                  <div class="form-field size-p-100">
                    <label for="fulfillPartial">Partial</label>
                    <input type="button" value=">" class="button-small fulfill-slab-prod">
                    <?php echo $this->tag->checkField(array('fulfill', 'value' => 'partial', 'class' => 'check-fulfill check-fulfill-partial')); ?>
                    <?php echo $this->tag->textField(array('fulfill_amount_partial', 'size' => '5', 'value' => $item->quantity, 'class' => 'fulfill_amount_partial')); ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="fulfill-button-container">
          <input type="button" value="Received Items" id="fulfill-general-button" class="button-transparent">
        </div>
        
        <div class="totals-container">
          <div class="subtotal">
            <div class="label">Subtotal:</div>
            <div class="value">$<?php echo $po->total; ?></div>
          </div>
          <div class="tax">
            <div class="label">Tax:</div>
            <div class="value">$0.00</div>
          </div>
          <div class="total">
            <div class="label">Total:</div>
            <div class="value">$<?php echo $po->total; ?></div>
          </div>
        </div>
      </div>
    </div>
    
    
    <div class="form-field-group-container size-p-100 last">
      <div class="form-field-group">
        <div class="title">Invoices</div>
        
        
        <div class="form-field size-p-30">
          <label for="transaction_name">Transaction</label>
          <?php echo $this->tag->textField(array('transaction_name', 'size' => 100)); ?>
        </div>
        <div class="form-field size-p-15">
          <label for="number">Number</label>
          <?php echo $this->tag->textField(array('number', 'size' => 100)); ?>
        </div>
        <div class="form-field size-p-15">
          <label for="container_number">Container #</label>
          <?php echo $this->tag->textField(array('container_number', 'size' => 100)); ?>
        </div>
        <div class="form-field size-p-15">
          <label for="eta_date">ETA Date</label>
          <?php echo $this->tag->textField(array('eta_date', 'size' => 50, 'class' => 'triton-datepicker')); ?>
        </div>
        <div class="form-field size-p-15">
          <label for="received_date">Received Date</label>
          <?php echo $this->tag->textField(array('received_date', 'size' => 50, 'class' => 'triton-datepicker')); ?>
        </div>
        <div class="form-field size-p-15">
          <label for="total">Total</label>
          <?php echo $this->tag->textField(array('total', 'size' => 100)); ?>
        </div>
        <div class="clearfix"></div>
        <div class="button-container">
          <input type="button" value="Add" id="add-invoice-button" class="button-orange">
        </div>
        
        
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
            <?php foreach ($invoices as $index => $item) { ?>
              <div class="list-table-row">
                <div class="list-field poi-list-field poi-list-field-date">
                  <div class="list-field-content">
                    <?php echo $item->date; ?>
                  </div>
                </div>
                <div class="list-field poi-list-field poi-list-field-transaction">
                  <div class="list-field-content">
                    <?php echo $item->description; ?>
                  </div>
                </div>
                <div class="list-field poi-list-field poi-list-field-number">
                  <div class="list-field-content">
                    <?php echo $item->number; ?>
                  </div>
                </div>
                <div class="list-field poi-list-field poi-list-field-total">
                  <div class="list-field-content">
                    $<?php echo $item->total; ?>
                  </div>
                </div>
                <div class="list-field poi-list-field poi-list-field-eta">
                  <div class="list-field-content">
                    <?php echo $item->eta_date; ?>
                  </div>
                </div>
                <div class="list-field poi-list-field poi-list-field-received">
                  <div class="list-field-content">
                    <?php echo $item->received_date; ?>
                  </div>
                </div>
                <div class="list-field poi-list-field poi-list-field-container">
                  <div class="list-field-content">
                    <?php echo $item->container_number; ?>
                  </div>
                </div>
              </div>
            <?php } ?>
            
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
<?php echo $this->tag->endForm(); ?>