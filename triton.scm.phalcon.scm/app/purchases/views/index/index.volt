{# /app/purchases/views/index/index.volt #}
<div class="simple-message-center-cont">
  <div class="simple-message-center">
    <img class="flowchart" src="{{ url('img/landing-flowchart.png') }}"/>
    <a id="suppliers-list-a" class="flowchart-a" href="{{ url('company/list?type=supplier') }}">Suppliers</a>
    <a id="product-list-a" class="flowchart-a" href="#">Products</a>
    <a id="po-prepurchase-a" class="flowchart-a" href="{{ url('po/index') }}">Pre-Purchase Request</a>
    <a id="po-tbp-list-a" class="flowchart-a" href="{{ url('po/search') }}">To Be Purchased</a>
    <a id="po-tba-list-a" class="flowchart-a" href="{{ url('po/search') }}">To Be Allocated</a>
    <a id="po-list-a" class="flowchart-a" href="{{ url('po/search') }}">POs</a>
    <a id="freights-list-a" class="flowchart-a" href="{{ url('company/list?type=freight') }}">Freight</a>
    <a id="invoices-a" class="flowchart-a" href="{{ url('po/search') }}">Supplier Invoices</a>
    <a id="inventory-a" class="flowchart-a" href="/triton/client/#/inventory">Inventory</a>
    <a id="returns-list-a" class="flowchart-a" href="/triton/client/#/inventory/returns/list">Returns</a>
  </div>
</div>
