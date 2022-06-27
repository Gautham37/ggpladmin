<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductDetails;
use App\Models\QualityGrade;
use App\Models\ValueAddedServiceAffiliated;
use App\Models\Departments;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\ProductSeasons;
use App\Models\ProductColors;
use App\Models\ProductNutritions;
use App\Models\ProductNutritionDetail;
use App\Models\ProductTastes;
use App\Models\StockStatus;
use App\Models\Uom;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class ProductsImport implements ToModel, WithValidation, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {   

        //Grade Validate
        if($row['quality_grade']!='') :    
            $quality_grade          = QualityGrade::firstOrCreate(['name'=>$row['quality_grade']]);
            $row['quality_grade']   = $quality_grade->id;
        endif;

        //Stock Status Validate  
        if($row['stock_status']!='') :  
            $stock_status           = StockStatus::firstOrCreate(['status'=>$row['stock_status']]);
            $row['stock_status']    = $stock_status->id;    
        endif;
            
        //Department Validate
        if($row['department']!='') :    
            $department             = Departments::firstOrCreate(['name'=>$row['department']]);
            $row['department']      = $department->id;
        endif;    

        //Category Validate
        if($row['category']!='' && $row['category_short']!='') :
            $category               = Category::firstOrCreate([
                'name'          => $row['category'],
                'short_name'    => $row['category_short'], 
                'department_id' => $department->id
            ]);
            $row['category']        = $category->id;
        endif;
            
        //Subcategory Validate
        if($row['subcategory']!='' && $row['subcategory_short']!='') :
            $subcategory            = Subcategory::firstOrCreate([
                'name'          => $row['subcategory'],
                'short_name'    => $row['subcategory_short'],
                'category_id'   => $category->id, 
                'department_id' => $department->id
            ]);
            $row['subcategory']     = $subcategory->id;
        endif;
        
        //Season Validate
        if($row['season']!='') :    
            $season                 = ProductSeasons::firstOrCreate(['name'=>$row['season']]);
            $row['season']          = $season->id;
        endif;

        //Color Validate
        if($row['color']!='') :
            $color                  = ProductColors::firstOrCreate(['name'=>$row['color']]);
            $row['color']           = $color->id;
        endif;
            
        //Nutrition Validate
        if($row['nutrition']!='') :
            $nutritions = explode(',',$row['nutrition']);
            if(count($nutritions) > 0) {
                foreach($nutritions as $key => $value) {
                    $nutrition = ProductNutritions::firstOrCreate(['name'=>$value]);
                    $row['nutrition_list'][]= $nutrition->id;
                }
            }
        endif;

        //Nutrition Validate
        /*if($row['nutrition']!='') :
            $nutrition              = ProductNutritions::firstOrCreate(['name'=>$row['nutrition']]);
            $row['nutrition']       = $nutrition->id;
        endif;*/
            
        //Taste Validate
        if($row['taste']!='') :
            $taste                  = ProductTastes::firstOrCreate(['name'=>$row['taste']]);
            $row['taste']           = $taste->id;
        endif;    

        if($row['unit']!='') :
            $primary_unit           = Uom::where('name',$row['unit'])->first();
            $row['unit']            = $primary_unit->id;   
        endif;
            
            if($row['secondary_unit']!='' && $row['secondary_unit_quantity'] > 0) {
                $secondary_unit     = Uom::where('name',$row['secondary_unit'])->first();
                if($secondary_unit) {
                    $row['secondary_unit'] = $secondary_unit->id;
                } else {
                    $row['secondary_unit']           = null;
                    $row['secondary_unit_quantity']  = null;    
                }
            } else {
                $row['secondary_unit']           = null;
                $row['secondary_unit_quantity']  = null;
            }


            if($row['tertiary_unit']!='' && $row['tertiary_unit_quantity'] > 0) {
                $tertiary_unit     = Uom::where('name',$row['tertiary_unit'])->first();
                if($tertiary_unit) {
                    $row['tertiary_unit'] = $tertiary_unit->id;
                } else {
                    $row['tertiary_unit']           = null;
                    $row['tertiary_unit_quantity']  = null;    
                }
            } else {
                $row['tertiary_unit']           = null;
                $row['tertiary_unit_quantity']  = null;
            }


            if($row['custom_unit']!='' && $row['custom_unit_quantity'] > 0) {
                $custom_unit     = Uom::where('name',$row['custom_unit'])->first();
                if($custom_unit) {
                    $row['custom_unit'] = $custom_unit->id;
                } else {
                    $row['custom_unit']           = null;
                    $row['custom_unit_quantity']  = null;    
                }
            } else {
                $row['custom_unit']           = null;
                $row['custom_unit_quantity']  = null;
            }


            if($row['bulk_buy_unit']!='' && $row['bulk_buy_unit_quantity'] > 0) {
                $bulk_buy_unit     = Uom::where('name',$row['bulk_buy_unit'])->first();
                if($bulk_buy_unit) {
                    $row['bulk_buy_unit'] = $bulk_buy_unit->id;
                } else {
                    $row['bulk_buy_unit']           = null;
                    $row['bulk_buy_unit_quantity']  = null;    
                }
            } else {
                $row['bulk_buy_unit']           = null;
                $row['bulk_buy_unit_quantity']  = null;
            }

            if($row['wholesale_purchase_unit']!='' && $row['wholesale_purchase_unit_quantity'] > 0) {
                $wholesale_purchase_unit     = Uom::where('name',$row['wholesale_purchase_unit'])->first();
                if($wholesale_purchase_unit) {
                    $row['wholesale_purchase_unit'] = $wholesale_purchase_unit->id;
                } else {
                    $row['wholesale_purchase_unit']           = null;
                    $row['wholesale_purchase_unit_quantity']  = null;    
                }
            } else {
                $row['wholesale_purchase_unit']           = null;
                $row['wholesale_purchase_unit_quantity']  = null;
            }

            if($row['pack_weight_unit']!='' && $row['pack_weight_unit_quantity'] > 0) {
                $pack_weight_unit     = Uom::where('name',$row['pack_weight_unit'])->first();
                if($pack_weight_unit) {
                    $row['pack_weight_unit'] = $pack_weight_unit->id;
                } else {
                    $row['pack_weight_unit']           = null;
                    $row['pack_weight_unit_quantity']  = null;    
                }
            } else {
                $row['pack_weight_unit']           = null;
                $row['pack_weight_unit_quantity']  = null;
            }

            $row['con']                 = $row['product_code_short'].$row['product_varient_number'].$row['category_short'].$row['subcategory_short'];
            $row['product_code']        = $row['category_short'].'-'.$row['subcategory_short'].'-'.$row['product_code_short'].'-'.$row['product_varient_number'];
            $row['short_product_code']  = $row['alpha'].$row['product_varient_number'];

        //Value Added Services Validate    
            /*$value_added_service_affiliated          = ValueAddedServiceAffiliated::firstOrCreate(['name'=>$row['value_added_service_affiliated']]);
            $row['value_added_service_affiliated']   = $value_added_service_affiliated->id;*/        
                    
        $product = Product::create([

            'name'                                  => $row['name'],
            'area'                                  => $row['area'],
            'quality_grade'                         => $row['quality_grade'],
            'product_status'                        => $row['product_status'],
            'stock_status'                          => $row['stock_status'],
            'name_lang_1'                           => $row['name_lang_1'],
            'name_lang_2'                           => $row['name_lang_2'],
            'category_id'                           => $row['category'],
            'subcategory_id'                        => $row['subcategory'],
            'department_id'                         => $row['department'],
            'season_id'                             => $row['season'],
            'color_id'                              => $row['color'],
            'nutrition_id'                          => $row['nutrition'],
            'taste_id'                              => $row['taste'],
            'alpha'                                 => $row['alpha'],
            'product_code_short'                    => $row['product_code_short'],
            'product_varient'                       => $row['product_varient'],
            'product_varient_number'                => $row['product_varient_number'],
            'con'                                   => $row['con'],
            'product_code'                          => $row['product_code'],
            'short_product_code'                    => $row['short_product_code'],
            'price'                                 => $row['price'],
            'purchase_price'                        => $row['purchase_price'],
            'discount_price'                        => $row['discount_price'],
            'hsn_code'                              => $row['hsn_code'],
            'tax'                                   => $row['tax'],
            'description'                           => $row['description'],
            'unit'                                  => $row['unit'],
            'stock'                                 => $row['stock'],
            'alternative_unit'                      => $row['alternative_unit'],
            'secondary_unit'                        => $row['secondary_unit'],
            'secondary_unit_quantity'               => $row['secondary_unit_quantity'],
            'tertiary_unit'                         => $row['tertiary_unit'],
            'tertiary_unit_quantity'                => $row['tertiary_unit_quantity'],
            'custom_unit'                           => $row['custom_unit'],
            'custom_unit_quantity'                  => $row['custom_unit_quantity'],
            'bulk_buy_unit'                         => $row['bulk_buy_unit'],
            'bulk_buy_unit_quantity'                => $row['bulk_buy_unit_quantity'],
            'wholesale_purchase_unit'               => $row['wholesale_purchase_unit'],
            'wholesale_purchase_unit_quantity'      => $row['wholesale_purchase_unit_quantity'],
            'pack_weight_unit'                      => $row['pack_weight_unit'],
            'pack_weight_unit_quantity'             => $row['pack_weight_unit_quantity'],
            'product_size'                          => $row['product_size'],
            'spare'                                 => $row['spare'],
            'spare_2'                               => $row['spare_2'],
            'ave_weight_if_known'                   => $row['ave_weight_if_known'],
            'ave_p_u_1_weight'                      => $row['ave_p_u_1_weight'],
            'featured'                              => $row['featured'],
            'deliverable'                           => $row['deliverable'],
            'online_store'                          => $row['online_store'],
            'low_stock_warning'                     => $row['low_stock_warning'],
            'sugar_level'                           => $row['sugar_level'],
            'weight_loss'                           => $row['weight_loss'],
            'freeze_well'                           => $row['freeze_well'],
            'grows_on_tree'                         => $row['grows_on_tree'],
            'salad_vegetable'                       => $row['salad_vegetable'],
            'low_stock_unit'                        => $row['low_stock_unit'],
            'nutrition_benefit'                     => $row['nutrition_benefit'],
            'health_benefit'                        => $row['health_benefit'],
            'product_life'                          => $row['product_life'],
            'ambient_temprature'                    => $row['ambient_temprature'],
            'storage_type'                          => $row['storage_type'],
            'storage_method'                        => $row['storage_method'],
            'range_standard'                        => $row['range_standard'],
            'short_description_product_code'        => $row['short_description_product_code'],
            'stock_level'                           => $row['stock_level'],
            'stock_purchased_date'                  => $row['stock_purchased_date'],
            'alternate_weight_kg'                   => $row['alternate_weight_kg'],
            'other_key_search_words'                => $row['other_key_search_words'],
            'source_confirm'                        => $row['source_confirm'],
            'reason_discontinuation'                => $row['reason_discontinuation'],
            'created_by'                            => auth()->user()->id
        ]);

        if($product) {

            $inventory = $product->productInventory()->create([
                'product_id' => $product->id,
                'category'   => 'opening',
                'type'       => 'add',
                'date'       => Carbon::now()->format('Y-m-d H:i:s'),
                'quantity'   => $product->stock,
                'unit'       => $product->unit,
                'created_by' => auth()->user()->id 
            ]);

            if(isset($row['nutrition_list']) && count($row['nutrition_list']) > 0) {
                foreach($row['nutrition_list'] as $key => $value) {
                    $product_nutrition = ProductNutritionDetail::create([
                        'product_id'            => $product->id,
                        'product_nutrition_id'  => $value
                    ]);
                }
            }

        }
    
        return $product;
    
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'name_lang_1' => 'required|string',
            'name_lang_2' => 'required|string',
            'category' => 'required|string',
            'category_short' => 'required|string',
            'subcategory' => 'required|string',
            'subcategory_short' => 'required|string',
            'department' => 'required|string',
            'alpha' => 'required|string',
            'product_code_short' => 'required|string',
            'product_varient' => 'required|string',
            'product_varient_number' => 'required',
            'con' => 'required|string',
            'product_code' => 'required|string',
            'short_product_code' => 'required|string',
            'price' => 'required',
            'purchase_price' => 'required',
            'hsn_code' => 'required',
            'description' => 'required|string',
            'unit' => 'required|string',
            'stock' => 'required',
        ];
    }

}
