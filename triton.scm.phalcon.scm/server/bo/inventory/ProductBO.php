<?php
namespace Triton\Bo\Inventory;

/**
 * Business logic for the products in the inventory catalog.
 *
 * @author juangalf
 */
class ProductBO {
  
  /**
   * Returns the list of products.
   * 
   * @param object $app The Phalcon Micro application.
   * @param array $page int The page to show.
   * @param array $query Array of filters.
   * @return object The product detail.
   */
  public static function searchProducts($app, $page, $query) {
    try{
      // Query.
      $phql = "SELECT 
          pid
          ,sku
          ,p.name
          ,kind
          ,alternate_name
          ,type.name as type
          ,colors
          ,cat.name as category
          ,orig.country_name_eng as origin
          ,subcat.name as subcategory
          ,thickness
          ,tgroup.name as material_group
          ,series_name
          ,uom_group
          ,uom.name as units
          ,weight
          ,size
          ,is_indivisible
          ,is_manufactured
          ,price_1
          ,price_2
          ,price_3
          ,price_4
          ,price_range
          ,ilacc.name as inventory_account
          ,inacc.name as income_account
          ,cogsacc.name as cost_account
          ,safety_stock
          ,safety_stock_2
          ,reorder_quantity
          ,reorder_quantity_2
          ,lead_time
          ,bin
          ,preferred_supplier
          ,generic_name
          ,generic_sku
          ,alt_unit_1
          ,alt_equivalence_1
          ,alt_unit_2
          ,alt_equivalence_2
          ,alt_unit_3
          ,alt_equivalence_3
          ,alt_unit_4
          ,alt_equivalence_4
          ,alt_unit_5
          ,alt_equivalence_5
          ,alt_unit_6
          ,alt_equivalence_6
          ,is_minimum_unit
          ,is_new_arrival
          ,hide_on_website
          ,is_featured
          ,web_name
          ,description
          ,notes
          ,instructions
          ,disclaimer ";
      // Join with inventory if that the request.
      if ( $query['inventory'] ) {
        $phql .= " , inv.iid, inv.amount, inv.detail ";
      }
      $phql .= "
        FROM Triton\Models\Inventory\Product p
          LEFT JOIN Triton\Models\Taxonomy\TaxonomyTerm type ON type.tid = p.type
          LEFT JOIN Triton\Models\Taxonomy\TaxonomyTerm cat ON cat.tid = p.category
          LEFT JOIN Triton\Models\Geo\Countries orig ON orig.country_id = p.origin
          LEFT JOIN Triton\Models\Taxonomy\TaxonomyTerm ilacc ON ilacc.tid = p.inventory_account
          LEFT JOIN Triton\Models\Taxonomy\TaxonomyTerm inacc ON inacc.tid = p.income_account
          LEFT JOIN Triton\Models\Taxonomy\TaxonomyTerm cogsacc ON cogsacc.tid = p.cost_account
          LEFT JOIN Triton\Models\Inventory\Units uom ON uom.uid = p.units
          LEFT JOIN Triton\Models\Taxonomy\TaxonomyTerm subcat ON subcat.tid = p.subcategory
          LEFT JOIN Triton\Models\Taxonomy\TaxonomyTerm tgroup ON tgroup.tid = p.material_group
      ";
      // Join with inventory if that the request.
      if ( $query['inventory'] ) {
        $phql .= " JOIN Triton\Models\Inventory\Inventory inv ON inv.product = p.pid ";
      }
      $phql .= " WHERE 1=1 ";
      // Filters.
      $varsArray = array();
      if ( $query['q'] ) {
        $phql .= " AND p.name LIKE :q:";
        $varsArray['q'] = '%' . $query['q'] . '%';
      }
      if ( $query['letter'] ) {
        $phql .= " AND p.name LIKE :letter:";
        $varsArray['letter'] = $query['letter'] . '%';
      }
      if ( $query['type'] ) {
        $phql .= " AND p.type = :type:";
        $varsArray['type'] = $query['type'];
      }
      if ( $query['category'] ) {
        $phql .= " AND p.category = :category:";
        $varsArray['category'] = $query['category'];
      }
      if ( $query['group'] ) {
        $phql .= " AND p.material_group = :group:";
        $varsArray['group'] = $query['group'];
      }
      if ( $query['thickness'] ) {
        $phql .= " AND p.thickness = :thickness:";
        $varsArray['thickness'] = $query['thickness'];
      }
      if ( $query['kind'] ) {
        $phql .= " AND p.kind = :kind:";
        $varsArray['kind'] = $query['kind'];
      }
      $products = $app->modelsManager->executeQuery($phql, $varsArray);
      // Create a Model paginator, show 10 rows by page starting from $currentPage
      $paginator = new \Phalcon\Paginator\Adapter\Model(
          array(
              "data" => $products,
              "limit"=> 20,
              "page" => $page
          )
      );
      // Get the paginated results
      $page = $paginator->getPaginate();
      //
      $list = array();
      //foreach ($products as $product) {
      foreach ($page->items as $product) {
        // Add some complex fields.
        $arrtemp = $product->toArray();
        $arrtemp['imageUrl'] ="img/header-background.png";
        $arrtemp['inventory_availability'] ="IA";
        $arrtemp['status'] ="Stock";
        // Add it to the result.
        $list[] = $arrtemp;
      }
      // Prepare the return object.
      $data = array(
        "status" => "OK",
        "pager" => $page,
        "list" => $list,
      );
      // R.
      return $data;
    }
    catch (\Exception $e) {
      $app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
    }
    // Default return.
    return FALSE;
  }
  
  /**
   * Returns the detail of an specific product.
   * 
   * @param object $app The Phalcon Micro application.
   * @param int $id The ID of the product to query.
   * @return object The product detail.
   */
  public static function getDetail($app, $id) {
    try{
      // Query.
      $phql = "SELECT 
          pid
          ,sku
          ,p.name
          ,kind
          ,alternate_name
          ,type.name as type
          ,colors
          ,cat.name as category
          ,orig.country_name_eng as origin
          ,subcategory
          ,thickness
          ,material_group
          ,series_name
          ,uom_group
          ,uom.name as units
          ,weight
          ,size
          ,is_indivisible
          ,is_manufactured
          ,price_1
          ,price_2
          ,price_3
          ,price_4
          ,price_range
          ,ilacc.name as inventory_account
          ,inacc.name as income_account
          ,cogsacc.name as cost_account
          ,safety_stock
          ,safety_stock_2
          ,reorder_quantity
          ,reorder_quantity_2
          ,lead_time
          ,bin
          ,preferred_supplier
          ,generic_name
          ,generic_sku
          ,alt_unit_1
          ,alt_equivalence_1
          ,alt_unit_2
          ,alt_equivalence_2
          ,alt_unit_3
          ,alt_equivalence_3
          ,alt_unit_4
          ,alt_equivalence_4
          ,alt_unit_5
          ,alt_equivalence_5
          ,alt_unit_6
          ,alt_equivalence_6
          ,is_minimum_unit
          ,is_new_arrival
          ,hide_on_website
          ,is_featured
          ,web_name
          ,description
          ,notes
          ,instructions
          ,disclaimer
        FROM Triton\Models\Inventory\Product p
          LEFT JOIN Triton\Models\Taxonomy\TaxonomyTerm type ON type.tid = p.type
          LEFT JOIN Triton\Models\Taxonomy\TaxonomyTerm cat ON cat.tid = p.category
          LEFT JOIN Triton\Models\Geo\Countries orig ON orig.country_id = p.origin
          LEFT JOIN Triton\Models\Taxonomy\TaxonomyTerm ilacc ON ilacc.tid = p.inventory_account
          LEFT JOIN Triton\Models\Taxonomy\TaxonomyTerm inacc ON inacc.tid = p.income_account
          LEFT JOIN Triton\Models\Taxonomy\TaxonomyTerm cogsacc ON cogsacc.tid = p.cost_account
          LEFT JOIN Triton\Models\Inventory\Units uom ON uom.uid = p.units
        WHERE pid = :id:
      ";
      $product = $app->modelsManager->executeQuery($phql, array(
          'id' => $id
      ))->getFirst();
      // Inventory Query.
      $phql = "SELECT i.amount
          , i.detail
          , i.iid
        FROM Triton\Models\Inventory\Inventory i
        WHERE i.product = :pid:
      ";
      $inventory = $app->modelsManager->executeQuery($phql, array(
          'pid' => $id
      ))->getFirst();
      // Add the info to the return object.
      $inventory_detail = array();
      if ( $inventory ) {
        $product->inventory_in_stock = $inventory->amount;
        $product->inventory_available = $inventory->amount;
        $product->slabs_data = $inventory->detail;
        // Slabs case.
        if ( $product->type == 'SLAB' ) {
          // Transform the slabs data.
          $bundles = json_decode($inventory->detail);
          foreach ($bundles as $bkey => $bundle) {
            foreach ($bundle->slabs as $skey => $slab) {
              $inventory_row = array(
                'id' => 'BS' . $inventory->iid . '-0' . $bkey . $skey,
                'barcode' => 'TF77733'  . $bkey . $skey,
                'bundle' => 'Bundle ' . $bkey,
                'supplier_reference' => $slab->width . ' x ' . $slab->height . ' x ' . $slab->thickness,
                // @todo
                'location' => 'Triton of Fort Myers',
                'bin' => $product->bin,
                'quantity' => 1,
                'available' => 1,
              );
              $inventory_detail[] = $inventory_row;
            }
          }
        }
        // Regular products.
        else {
          $inventory_row = array(
            'id' => 'IID0' . $inventory->iid,
            'barcode' => 'TF777337',
            'bundle' => '',
            'supplier_reference' => $product->alternate_name,
            // @todo
            'location' => 'Triton of Fort Myers',
            'bin' => '',
            'quantity' => $inventory->amount,
            'available' => $inventory->amount,
          );
          $inventory_detail[] = $inventory_row;
        }
        // Add it to the returned object.
        $product->inventory_detail = $inventory_detail;
      }
      else {
        $product->inventory_in_stock = 0;
        $product->inventory_available = 0;
        $product->slabs_data = "";
        $product->inventory_detail = array();
      }
      // R.
      return $product;
    }
    catch (\Exception $e) {
      $app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
    }
    // Default return.
    return FALSE;
  }
  
  /**
   * Creates a new product.
   * 
   * @param object $app The Phalcon Micro application.
   * @param object $material The material object with all the data.
   * @return object The response of the query execution $app->modelsManager->executeQuery.
   */
  public static function create($app, $material) {
    try{
      // Insert.
      $phql = "INSERT INTO Triton\Models\Inventory\Product ( 
          sku
          , name
          , kind
          , alternate_name
          , type
          , colors
          , category
          , origin
          , subcategory
          , thickness
          , material_group
          , series_name
          , uom_group
          , units
          , weight
          , size
          , is_indivisible
          , is_manufactured
          , price_1
          , price_2
          , price_3
          , price_4
          , price_range
          , inventory_account
          , income_account
          , cost_account
          , safety_stock
          , safety_stock_2
          , reorder_quantity
          , reorder_quantity_2
          , lead_time
          , bin
          , preferred_supplier
          , generic_name
          , generic_sku
          , alt_unit_1
          , alt_equivalence_1
          , alt_unit_2
          , alt_equivalence_2
          , alt_unit_3
          , alt_equivalence_3
          , alt_unit_4
          , alt_equivalence_4
          , alt_unit_5
          , alt_equivalence_5
          , alt_unit_6
          , alt_equivalence_6
          , is_minimum_unit
          , is_new_arrival
          , hide_on_website
          , is_featured
          , web_name
          , description
          , notes
          , instructions
          , disclaimer
        )
        VALUES (
          :sku:
          , :name:
          , :kind:
          , :alternate_name:
          , :type:
          , :colors:
          , :category:
          , :origin:
          , :subcategory:
          , :thickness:
          , :group:
          , :series_name:
          , :uom_group:
          , :units:
          , :weight:
          , :size:
          , :is_indivisible:
          , :is_manufactured:
          , :price_1:
          , :price_2:
          , :price_3:
          , :price_4:
          , :price_range:
          , :inventory_account:
          , :income_account:
          , :cost_account:
          , :safety_stock:
          , :safety_stock_2:
          , :reorder_quantity:
          , :reorder_quantity_2:
          , :lead_time:
          , :bin:
          , :preferred_supplier:
          , :generic_name:
          , :generic_sku:
          , :alt_unit_1:
          , :alt_equivalence_1:
          , :alt_unit_2:
          , :alt_equivalence_2:
          , :alt_unit_3:
          , :alt_equivalence_3:
          , :alt_unit_4:
          , :alt_equivalence_4:
          , :alt_unit_5:
          , :alt_equivalence_5:
          , :alt_unit_6:
          , :alt_equivalence_6:
          , :is_minimum_unit:
          , :is_new_arrival:
          , :hide_on_website:
          , :is_featured:
          , :web_name:
          , :description:
          , :notes:
          , :instructions:
          , :disclaimer:
        )
      ";
      $status = $app->modelsManager->executeQuery($phql, array(
        'sku' => isset($material->sku)? $material->sku: NULL
        ,'name' => $material->name
        ,'kind' => isset($material->kind)? $material->kind: NULL
        ,'alternate_name' => isset($material->altName)? $material->altName: NULL
        ,'type' => isset($material->type)? $material->type: NULL
        ,'colors' => isset($material->baseColors)? $material->baseColors: NULL
        ,'category' => isset($material->category)? $material->category: NULL
        ,'origin' => isset($material->origin)? $material->origin: NULL
        ,'subcategory' => isset($material->subcategory)? $material->subcategory: NULL
        ,'thickness' => isset($material->thickness)? $material->thickness: NULL
        ,'group' => isset($material->group)? $material->group: NULL
        ,'series_name' => isset($material->series)? $material->series: NULL
        ,'uom_group' => isset($material->uomGroup)? $material->uomGroup: NULL
        ,'units' => isset($material->units)? $material->units: NULL
        ,'weight' => isset($material->weight)? $material->weight: NULL
        ,'size' => isset($material->size)? $material->size: NULL
        ,'is_indivisible' => isset($material->isIndivisible)? $material->isIndivisible: NULL
        ,'is_manufactured' => isset($material->isManufactured)? $material->isManufactured: NULL
        ,'price_1' => isset($material->price1)? $material->price1: NULL
        ,'price_2' => isset($material->price2)? $material->price2: NULL
        ,'price_3' => isset($material->price3)? $material->price3: NULL
        ,'price_4' => isset($material->price4)? $material->price4: NULL
        ,'price_range' => isset($material->priceRange)? $material->priceRange: NULL
        ,'inventory_account' => isset($material->invAccount)? $material->invAccount: NULL
        ,'income_account' => isset($material->incomeAccount)? $material->incomeAccount: NULL
        ,'cost_account' => isset($material->costAccount)? $material->costAccount: NULL
        ,'safety_stock' => isset($material->safetyStock)? $material->safetyStock: NULL
        ,'safety_stock_2' => isset($material->safetyStock2)? $material->safetyStock2: NULL
        ,'reorder_quantity' => isset($material->reorderQty)? $material->reorderQty: NULL
        ,'reorder_quantity_2' => isset($material->reorderQty2)? $material->reorderQty2: NULL
        ,'lead_time' => isset($material->leadTime)? $material->leadTime: NULL
        ,'bin' => isset($material->bin)? $material->bin: NULL
        ,'preferred_supplier' => isset($material->preferredSupplier)? $material->preferredSupplier: NULL
        ,'generic_name' => isset($material->genericName)? $material->genericName: NULL
        ,'generic_sku' => isset($material->genericSku)? $material->genericSku: NULL
        ,'alt_unit_1' => isset($material->unit1)? $material->unit1: NULL
        ,'alt_equivalence_1' => isset($material->equiv1)? $material->equiv1: NULL
        ,'alt_unit_2' => isset($material->unit2)? $material->unit2: NULL
        ,'alt_equivalence_2' => isset($material->equiv2)? $material->equiv2: NULL
        ,'alt_unit_3' => isset($material->unit3)? $material->unit3: NULL
        ,'alt_equivalence_3' => isset($material->equiv3)? $material->equiv3: NULL
        ,'alt_unit_4' => isset($material->unit4)? $material->unit4: NULL
        ,'alt_equivalence_4' => isset($material->equiv4)? $material->equiv4: NULL
        ,'alt_unit_5' => isset($material->unit5)? $material->unit5: NULL
        ,'alt_equivalence_5' => isset($material->equiv5)? $material->equiv5: NULL
        ,'alt_unit_6' => isset($material->unit6)? $material->unit6: NULL
        ,'alt_equivalence_6' => isset($material->equiv6)? $material->equiv6: NULL
        ,'is_minimum_unit' => isset($material->isMinimumUnit)? $material->isMinimumUnit: NULL
        ,'is_new_arrival' => isset($material->isNewArrival)? $material->isNewArrival: NULL
        ,'hide_on_website' => isset($material->hideOnWebsite)? $material->hideOnWebsite: NULL
        ,'is_featured' => isset($material->isFeatureProduct)? $material->isFeatureProduct: NULL
        ,'web_name' => isset($material->webUse)? $material->webUse: NULL
        ,'description' => isset($material->description)? $material->description: NULL
        ,'notes' => isset($material->notes)? $material->notes: NULL
        ,'instructions' => isset($material->instructions)? $material->instructions: NULL
        ,'disclaimer' => isset($material->disclaimer)? $material->disclaimer: NULL
      ));
      // R.
      return $status;
    }
    catch (\Exception $e) {
      $app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
    }
    // Default return.
    return FALSE;
  }
  
  
  /**
   * Returns the list of products.
   * 
   * @param object $app The Phalcon Micro application.
   * @return list of product
   */
  public static function getAllProducts($app) {
    try{
      // Query.
      $phql = "SELECT  pid as value ,sku  ,p.name as label FROM Triton\Models\Inventory\Product p";
     // Filters.
      $varsArray = array();
     
      $products = $app->modelsManager->executeQuery($phql, $varsArray);
      $list = array();
      foreach ($products as $product) {
        // Add it to the result.
        $list[] = $product;
      }
      // R.
      return $list;
    }
    catch (\Exception $e) {
      $app->logger->log(get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')');
    }
    // Default return.
    return FALSE;
  }
}

?>
