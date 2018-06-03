<?php
//  namespace Triton\Purchases\Controllers;
  
  use Phalcon\Text;
  use Triton\Purchases\Models\AutocompleteGroup;
  
  /**
   * Used to handle the services for autocomplete fields.
   * 
   */
  class AutocompleteController extends ControllerBase{
    
    /**
     * @var string The namespace for the models where the data for the 
     * autocomplete is stored.
     */
    protected $models_namespace = 'Triton\Purchases\Models';
    
    /**
     * Returns the data for autocomplete an specifi field..
     * 
     * @param array $parameters The parameters passed from the view.
     * @return type
     */
    public function getAutocompleteDataAjax($parameters){
      try{
        // Parameters.
        $group_name = $parameters['group'];
        $word = $parameters['word'];
        // Get the group info to construct the query.
        $group = AutocompleteGroup::findFirst(array("name = '" . $group_name . "'"));
        if ( !$group ) {
          return null;
        }
        // Transform table to camelcase.
        $modelClass = Text::camelize($group->table);
        $mainField = $group->field;
        // Get the other fields as an array.
        $otherFields = json_decode($group->other_fields, TRUE);
        // Construct the columns string.
        $columns = $mainField;
        if ( $otherFields ) {
          $columns .= ',' . implode(',', array_keys($otherFields));
        }
        // Query DB.
        $rows = call_user_func(
          $this->models_namespace . '\\' . $modelClass . '::find'
          ,array(
            'columns' => $columns
            , $mainField . " LIKE '%" . $word . "%'"
            , "limit" => 50
          )
        );
        // Construct the results array
        $result = array();
        foreach ($rows as $row) {
          // Add the main elements.
          $objArray = array(
            'value' => $row->{$mainField}
            , 'label' => $row->{$mainField}
          );
          // Add the other fields.
          foreach ($otherFields as $table_column => $form_field) {
            $objArray[$form_field] = $row->{$table_column};
          }
          // Add object to array.
          $result[] = (object)$objArray;
        }
        // R.
        return $result;
      }
      catch (\Exception $e) {
        //return FALSE;
        return get_class($e) . ": [" . $e->getMessage() . '] >>>>> at ' . $e->getFile() . ', line (' . $e->getLine() . ')';
      }
    }
  }