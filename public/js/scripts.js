/*
 * File name: scripts.js
 * Last modified: 2020.04.30 at 08:21:08
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

$(document).ready(function () {
    let select2;
    let options;
    if ($('.icheck input').length > 0) {
        $('.icheck input').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_flat-blue',
            increaseArea: '20%' // optional
        });
    }
    if ($('textarea').length > 0) {
        $('textarea').summernote({
            height: 200
        });
    }
    if ($('.datepicker').length > 0) {
        $('.datepicker').datepicker({
            format: "yyyy-mm-dd",
            maxViewMode: 2,
            todayHighlight: true
        });
    }
    if ($('select.select2').length > 0) {
        options = {};
        select2 = $('select.select2');

        if(select2.data('tags')){
            options.tags = select2.data('tags');
        }
        select2.select2(options);
    }
    if ($('select.select2.not-required').length > 0) {
        options = {};
        select2 = $('select.select2.not-required');
        $.each(select2, function (i, element) {
            options.placeholder = $(element).data('empty');
            options.allowClear = true;
            $(element).select2(options);
            options = {};
        });
    }

    $('[data-toggle=tooltip]').tooltip();

    $('.main-sidebar .sidebar').slimScroll({
        position: 'right',
        height: '92vh',
        color: '#fff',
        railVisible: true,
    });
})

function render(props) {
    return function (tok, i) {
        return (i % 2) ? props[tok] : tok;
    };
}

function dzComplete(_this, file, mockFile = '', mediaMockFile = '') {
    if (mockFile !== '') {
        _this.removeFile(mockFile);
        mockFile = '';
    }
    if (mediaMockFile !== '' && _this.element.id === mediaMockFile.collection_name) {
        _this.removeFile(mediaMockFile);
        mediaMockFile = '';
    }
    if (file._removeLink) {
        file._removeLink.textContent = _this.options.dictRemoveFile;
    }
    if (file.previewElement) {
        return file.previewElement.classList.add("dz-complete");
    }
}

function dzCompleteMultiple(_this, file) {
    if (file._removeLink) {
        file._removeLink.textContent = _this.options.dictRemoveFile;
    }
    if (file.previewElement) {
        return file.previewElement.classList.add("dz-complete");
    }
}

function dzRemoveFile(file, mockFile = '', existRemoveUrl = '', collection, modelId, newRemoveUrl, csrf) {
    if (file.previewElement != null && file.previewElement.parentNode != null) {
        file.previewElement.parentNode.removeChild(file.previewElement);
    }
    //if(file.status === 'success'){
    if (mockFile !== '') {
        mockFile = '';
        $.post(existRemoveUrl,
            {
                _token: csrf,
                id: modelId,
                collection: collection,
            });
    } /*else {
        $.post(newRemoveUrl,
            {
                _token: csrf,
                uuid: file.upload.uuid
            });
    }*/
    //}
}

function dzRemoveFileMultiple(file, mockFile = [], existRemoveUrl = '', collection, modelId, newRemoveUrl, csrf) {
    if (file.previewElement != null && file.previewElement.parentNode != null) {
        file.previewElement.parentNode.removeChild(file.previewElement);
    }

    if (mockFile.length !== 0) {
        mockFile = [];
        $.post(existRemoveUrl,
            {
                _token: csrf,
                id: modelId,
                collection: collection,
                uuid:file.uuid,
            });
    }
    if(file.upload != null){
        $('input#'+file.upload.uuid).remove();
    }
}

function dzSending(_this, file, formData, csrf) {
    _this.element.children[0].value = file.upload.uuid;
    formData.append('_token', csrf);
    formData.append('field', _this.element.dataset.field);
    formData.append('uuid', file.upload.uuid);
}

function dzSendingMultiple(_this, file, formData, csrf) {
    $(_this.element).prepend('<input type="hidden" name="image[]">');
    _this.element.children[0].value = file.upload.uuid;
    _this.element.children[0].id = file.upload.uuid;
    formData.append('_token', csrf);
    formData.append('field', _this.element.dataset.field);
    formData.append('uuid', file.upload.uuid);
}

function dzMaxfile(_this, file) {
    _this.removeAllFiles();
    _this.addFile(file);
}

function dzInit(_this,mockFile,thumb) {
    _this.options.addedfile.call(_this, mockFile);
    _this.options.thumbnail.call(_this, mockFile, thumb);
    mockFile.previewElement.classList.add('dz-success');
    mockFile.previewElement.classList.add('dz-complete');
}

function dzAccept(file, done, dzElement = '.dropzone', iconBaseUrl) {
    var ext = file.name.split('.').pop().toLowerCase();
    if(['jpg','png','gif','jpeg','bmp'].indexOf(ext) === -1){
        var thumbnail = $(dzElement).find('.dz-preview.dz-file-preview .dz-image:last');
        var icon = iconBaseUrl+"/"+ext+".png";
        thumbnail.css('background-image', 'url('+icon+')');
        thumbnail.css('background-size', 'contain');
    }
    done();
}

// New Scripts

function addExpenseRow() {
    $('#expenseTable tr:last').after('<tr><input type="hidden" name="expense_detail_id[]" value="0"><td><input type="text" name="item[]" class="form-control"></td><td><input type="number" name="quantity[]" class="form-control quantity"></td><td><input type="text" name="rate[]" class="form-control price"></td><td><input type="text" name="amount[]" class="form-control subtotal"></td><td><a onclick="removeExpenseRow(this);" title="Remove"><i class="fa fa-trash"></i></a></td></tr>');
}

function removeExpenseRow(exRow) {
    exRow.closest('tr').remove();
}

function totalIt() {
  var total = 0;
  $(".subtotal").each(function() {
    var val = this.value;
    total += val == "" || isNaN(val) ? 0 : parseInt(val);
  });
  $("#total").val(total);
}

$(function() {

  $(document).on("keyup", ".quantity, .price", function() {
    var $row = $(this).closest("tr"),
      prce = parseInt($row.find('.price').val()),
      qnty = parseInt($row.find('.quantity').val()),
      subTotal = prce * qnty;
    $row.find('.subtotal').val(isNaN(subTotal) ? 0 : subTotal);
    totalIt()
  });

});


function addBasket(item) {
    $('.item'+item.id).html('<input type="hidden" name="itemId[]" value="'+item.value+'" /><input type="number" style="width:100%;" name="itemQuantity[]" value="1" min= />');
}

function removePurchaseeRow(exRow) {
    exRow.closest('tr').remove();
    totalProducts();
}

function removedeliveryeRow(exRow) {
    exRow.closest('tr').remove();
    totalProducts();
}

function removesaleseRow(exRow) {
    exRow.closest('tr').remove();
    totalProducts();
}

function totalProducts() {

      
      
      //Caculate Total Amount
      var total = 0;
      $(".product_subtotal").each(function() {
        var val = this.value;
        total += val == "" || isNaN(val) ? 0 : parseFloat(val);
      });
      //Caculate Total Amount
      //Caculate Discount Amount
      var discount = 0;
      $(".product_discount_price").each(function() {
        var dis_val = this.value;
        discount += dis_val == "" || isNaN(dis_val) ? 0 : parseFloat(dis_val);
      });
      //Caculate Discount Amount
      //Caculate Tax Amount
      var tax = 0;
      $(".product_tax").each(function() {
        var tax_val = this.value;
        tax += tax_val == "" || isNaN(tax_val) ? 0 : parseFloat(tax_val);
      });
      //Caculate Tax Amount

      //Caculate SGST Amount
      var sgst_price = 0;
      $(".product_sgst_pri").each(function() {
        var sgst_pri = this.value;
        sgst_price += sgst_pri == "" || isNaN(sgst_pri) ? 0 : parseFloat(sgst_pri);
      });
      //Caculate SGST Amount

      //Caculate CGST Amount
      var cgst_price = 0;
      $(".product_cgst_pri").each(function() {
        var cgst_pri = this.value;
        cgst_price += cgst_pri == "" || isNaN(cgst_pri) ? 0 : parseFloat(cgst_pri);
      });
      //Caculate CGST Amount

      $('.total_discount').val((discount).toFixed(2));
      $('.total_tax').val(((sgst_price + cgst_price)).toFixed(2));
      $('.total_amount').val((total).toFixed(2));
      $('.taxable_amount').val(((total)-(sgst_price + cgst_price)).toFixed(2));

      $('.sgst_amount').val((sgst_price).toFixed(2));
      $('.cgst_amount').val((cgst_price).toFixed(2));

     var additional_charges    = $('#additional_charges').val();
     var cash_paid             = $('#cash_paid').val();
     if(additional_charges>0) {
        total_amount = parseFloat(total) + parseFloat(additional_charges); 
     } else {
        total_amount = total;
     }
     var balance_amount = total_amount - cash_paid;
     $('#total_purchase_amount').val((total_amount).toFixed(2)); 
     $('#balance_amount').val((balance_amount).toFixed(2));
      
     if(balance_amount <= 0) {
        $('#mark_as_paid').prop('checked', true);
     } else {
        $('#mark_as_paid').prop('checked', false);
     }
}


$(document).on("keyup", ".product_quantity, .product_price, .product_discount_percent, .product_discount_price, .additional_charges, .cash_paid", function() {
    var $row = $(this).closest("tr"),     
      disper = parseFloat($row.find('.product_discount_percent').val()), 
      dispri = parseFloat($row.find('.product_discount_price').val()), 
      mrp = parseFloat($row.find('.product_mrp').val()),      
      prce = parseFloat($row.find('.product_price').val()),      
      qnty = parseFloat($row.find('.product_quantity').val()),
      tax = parseFloat($row.find('.product_tax').val());

      product_id = parseFloat($row.find('.product_id').val());  
      $.ajax({
          url: "../products/getPriceVariations",
          type: 'post',
          data: {
            '_token' : $('input[name="_token"]').attr('value'),
            'product_id'  : product_id
          }
      })
      .done(function(response) {
        var res_data = JSON.parse(response);
        if(res_data.status==='success') {
            $.each(res_data.result_data, function (keys,vals) {
                if(parseFloat(qnty) >== parseFloat(vals.purchase_quantity)) {
                  console.log(vals);
                  var calc = parseFloat(mrp) * parseFloat(vals.price_multiplier) / 100;
                  prce = $row.find('.product_price').val(calc);

                }
            });
        }

      })
      .fail(function(response) {
          console.log(response);
      });

      //alert(product_id);

      if(disper>=0) {
        if(disper>=0) {
            var dis_per_price = prce * qnty / 100;
            var total_discount = dis_per_price * disper;
            $row.find('.product_discount_price').val(isNaN(total_discount) ? 0 : total_discount);
            subTotal = prce * qnty - total_discount;  
        }

      } else {
        subTotal = parseFloat(prce) * parseFloat(qnty);
      }
      console.log(prce);
      if(tax>=0) {
        tax_amount = subTotal / 100 * tax;
        subTotal = subTotal + tax_amount;

        $row.find('.product_cgst_per').val((tax/2).toFixed(2));
        $row.find('.product_sgst_per').val((tax/2).toFixed(2));
        $row.find('.product_cgst_pri').val((tax_amount/2).toFixed(2));
        $row.find('.product_sgst_pri').val((tax_amount/2).toFixed(2));
      }      
    $row.find('.product_subtotal').val(isNaN(subTotal) ? 0 : (subTotal).toFixed(2));
    totalProducts();
});

    
$(document).on("change", ".product_tax", function() {
    var $row = $(this).closest("tr"),     
      disper = parseFloat($row.find('.product_discount_percent').val()), 
      dispri = parseFloat($row.find('.product_discount_price').val()), 
      prce = parseFloat($row.find('.product_price').val()),      
      qnty = parseFloat($row.find('.product_quantity').val()),
      tax = parseFloat($row.find('.product_tax').val());
      if(disper>=0) {
        if(disper>=0) {
            var dis_per_price = prce * qnty / 100;
            var total_discount = dis_per_price * disper;
            $row.find('.product_discount_price').val(isNaN(total_discount) ? 0 : total_discount);
            subTotal = prce * qnty - total_discount;  
        }

      } else {
        subTotal = parseFloat(prce) * parseFloat(qnty);
      }
      console.log(prce);
      if(tax>=0) {
        tax_amount = subTotal / 100 * tax;
        subTotal = subTotal + tax_amount;

        $row.find('.product_cgst_per').val((tax/2).toFixed(2));
        $row.find('.product_sgst_per').val((tax/2).toFixed(2));
        $row.find('.product_cgst_pri').val((tax_amount/2).toFixed(2));
        $row.find('.product_sgst_pri').val((tax_amount/2).toFixed(2));
      }      
    $row.find('.product_subtotal').val(isNaN(subTotal) ? 0 : (subTotal).toFixed(2));
    totalProducts();
});


$('#mark_as_paid').click(function(){
    if($(this).is(':checked')){
        var total = $('#total_purchase_amount').val();
        $('input[name="cash_paid"]').val(total);
        totalProducts();
    } else {
        $('input[name="cash_paid"]').val('');
        totalProducts();
    }
});

function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("productTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}

$('#purchaseOrderForm').validate({ // initialize the plugin
    rules: {
        purchase_code: {
            required: true
        },
        purchase_date: {
            required: true
        },
        valid_date: {
            required: true
        },
        purchase_party: {
            required: true,
            min:1
        },
        taxable_amount : {
            required: true
        },
        sgst_amount : {
            required: true
        },
        cgst_amount : {
            required: true
        },
        total_purchase_amount : {
            required: true
        },
        description : {
            required: true
        },
        cash_paid : {
            required: true
        },
        balance_amount : {
            required: true
        },
        total_discount : {
          required: true
        },
        total_tax : {
          required: true
        },
        total_amount : {
          required: true
        },
        "product_name[]" : {
          required:true
        },
        "product_hsn[]" : {
          required:true
        },
        "product_mrp[]" : {
          required:true,
          min:1
        },
        "product_price[]" : {
          required:true,
          min:1
        },
        "product_quantity[]" : {
          required:true,
          number: true,
          min:1
        },
        "amount[]" : {
          required:true,
          number: true,
          min:1
        }

    },
    errorElement: "div",
    errorPlacement: function(error, element) {
    }

});


$('#purchaseInvoiceForm').validate({ // initialize the plugin
    rules: {
        purchase_code: {
            required: true
        },
        purchase_date: {
            required: true
        },
        valid_date: {
            required: true
        },
        purchase_party: {
            required: true,
            min:1
        },
        taxable_amount : {
            required: true
        },
        sgst_amount : {
            required: true
        },
        cgst_amount : {
            required: true
        },
        total_purchase_amount : {
            required: true
        },
        description : {
            required: true
        },
        cash_paid : {
            required: true
        },
        balance_amount : {
            required: true
        },
        total_discount : {
          required: true
        },
        total_tax : {
          required: true
        },
        total_amount : {
          required: true
        },
        "product_name[]" : {
          required:true
        },
        "product_hsn[]" : {
          required:true
        },
        "product_mrp[]" : {
          required:true,
          min:1
        },
        "product_price[]" : {
          required:true,
          min:1
        },
        "product_quantity[]" : {
          required:true,
          number: true,
          min:1
        },
        "amount[]" : {
          required:true,
          number: true,
          min:1
        }

    },
    errorElement: "div",
    errorPlacement: function(error, element) {
    }

});


$('#purchaseReturnForm').validate({ // initialize the plugin
    rules: {
        purchase_code: {
            required: true
        },
        purchase_date: {
            required: true
        },
        valid_date: {
            required: true
        },
        purchase_party: {
            required: true,
            min:1
        },
        taxable_amount : {
            required: true
        },
        sgst_amount : {
            required: true
        },
        cgst_amount : {
            required: true
        },
        total_purchase_amount : {
            required: true
        },
        description : {
            required: true
        },
        cash_paid : {
            required: true
        },
        balance_amount : {
            required: true
        },
        total_discount : {
          required: true
        },
        total_tax : {
          required: true
        },
        total_amount : {
          required: true
        },
        "product_name[]" : {
          required:true
        },
        "product_hsn[]" : {
          required:true
        },
        "product_mrp[]" : {
          required:true,
          min:1
        },
        "product_price[]" : {
          required:true,
          min:1
        },
        "product_quantity[]" : {
          required:true,
          number: true,
          min:1
        },
        "amount[]" : {
          required:true,
          number: true,
          min:1
        }

    },
    errorElement: "div",
    errorPlacement: function(error, element) {
    }

});

$('#deliveryChallanForm').validate({ // initialize the plugin
    rules: {
        delivery_code: {
            required: true
        },
        delivery_date: {
            required: true
        },
        valid_date: {
            required: true
        },
        delivery_party: {
            required: true,
            min:1
        },
        taxable_amount : {
            required: true
        },
        sgst_amount : {
            required: true
        },
        cgst_amount : {
            required: true
        },
        total_purchase_amount : {
            required: true
        },
        description : {
            required: true
        },
        cash_paid : {
            required: true
        },
        balance_amount : {
            required: true
        },
        total_discount : {
          required: true
        },
        total_tax : {
          required: true
        },
        total_amount : {
          required: true
        },
        "product_name[]" : {
          required:true
        },
        "product_hsn[]" : {
          required:true
        },
        "product_mrp[]" : {
          required:true,
          min:1
        },
        "product_price[]" : {
          required:true,
          min:1
        },
        "product_quantity[]" : {
          required:true,
          number: true,
          min:1
        },
        "amount[]" : {
          required:true,
          number: true,
          min:1
        }

    },
    errorElement: "div",
    errorPlacement: function(error, element) {
    }

});


$('#salesInvoiceForm').validate({ // initialize the plugin
    rules: {
        sales_code: {
            required: true
        },
        sales_date: {
            required: true
        },
        valid_date: {
            required: true
        },
        sales_party: {
            required: true,
            min:1
        },
        taxable_amount : {
            required: true
        },
        sgst_amount : {
            required: true
        },
        cgst_amount : {
            required: true
        },
        total_purchase_amount : {
            required: true
        },
        description : {
            required: true
        },
        cash_paid : {
            required: true
        },
        balance_amount : {
            required: true
        },
        total_discount : {
          required: true
        },
        total_tax : {
          required: true
        },
        total_amount : {
          required: true
        },
        "product_name[]" : {
          required:true
        },
        "product_hsn[]" : {
          required:true
        },
        "product_mrp[]" : {
          required:true,
          min:1
        },
        "product_price[]" : {
          required:true,
          min:1
        },
        "product_quantity[]" : {
          required:true,
          number: true,
          min:0.1
        },
        "amount[]" : {
          required:true,
          number: true,
          min:1
        }

    },
    errorElement: "div",
    errorPlacement: function(error, element) {
    }

});

$('#salesReturnForm').validate({ // initialize the plugin
    rules: {
        sales_code: {
            required: true
        },
        sales_date: {
            required: true
        },
        valid_date: {
            required: true
        },
        sales_party: {
            required: true,
            min:1
        },
        taxable_amount : {
            required: true
        },
        sgst_amount : {
            required: true
        },
        cgst_amount : {
            required: true
        },
        total_purchase_amount : {
            required: true
        },
        description : {
            required: true
        },
        cash_paid : {
            required: true
        },
        balance_amount : {
            required: true
        },
        total_discount : {
          required: true
        },
        total_tax : {
          required: true
        },
        total_amount : {
          required: true
        },
        "product_name[]" : {
          required:true
        },
        "product_hsn[]" : {
          required:true
        },
        "product_mrp[]" : {
          required:true,
          min:1
        },
        "product_price[]" : {
          required:true,
          min:1
        },
        "product_quantity[]" : {
          required:true,
          number: true,
          min:1
        },
        "amount[]" : {
          required:true,
          number: true,
          min:1
        }

    },
    errorElement: "div",
    errorPlacement: function(error, element) {
    }

});


$('#expensesForm').validate({ // initialize the plugin
    rules: {
        expense_category: {
            required: true,
            min:1
        },
        expense_payment_mode: {
            required: true,
            min:1
        },
        expense_date: {
            required: true
        },
        expense_total_amount: {
            required: true,
            min:1
        },
        "item[]" : {
          required:true
        },
        "quantity[]" : {
          required:true,
          number: true,
          min:1
        },
        "product_mrp[]" : {
          required:true,
          min:1
        },
        "rate[]" : {
          required:true,
          min:1
        },
        "amount[]" : {
          required:true,
          min:1
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) {
    }

});


$('#productStockForm').validate({ // initialize the plugin
    rules: {
        stock_type: {
            required: true
        },
        quantity: {
            required: true,
            min:1
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) {
    }

});  


$(".invoice_color_picker").click(function() {

    var id = $(this).data("id");
    var col = $(this).data("col");

    $(".invoice_color_picker").css("border", 'none');
    $(".invoice_color_picker").removeClass("active");
    $(".invoice_button" + id).css("border", '3px solid #007bff');
    $(".invoice_button" + id).addClass("active");
    $(".pdf_div").css("background-color", col);
    $(".pdf_border").css("background-color", col);

    $(".pdf_table").css("border-top", "3px solid" + col);
    $(".pdf_table").css("border-bottom", "3px solid" + col);

});

$(".invoice_button").click(function() {

    var id = $(this).data("id");

    $(".invoice_button").css("border-color", 'none');
    $(".invoice_button").css("background-color", '#fff');
    $(".invoice_button").css("color", '#28a745');
    $(".invoice_button").removeClass("active");
    $("#invoice_theme_" + id).css("color", '#ffffff');
    $("#invoice_theme_" + id).css("background-color", '#28a745');
    $("#invoice_theme_" + id).css("border-color", '#28a745');
    $("#invoice_theme_" + id).addClass("active");
    if (id == 1) {
        $('.stylish').show();
        $('.simple').hide();
    } else {
        $('.simple').show();
        $('.stylish').hide();
    }

});

$(".save_settings").click(function() {

    var invoice_color = $(".invoice_color_picker.active").attr('data-col');
    var invoice_themes = $(".invoice_button.active").attr('data-id');
    $.ajax({
        type: "POST",
        url: 'update', // This is what I have updated
        data: {
            "_method": "PATCH",
            "_token": $('input[name="_token"]').attr('value'),
            app_invoice_color: invoice_color,
            app_invoice_theme: invoice_themes
        }
    }).done(function(msg) {
        window.location.reload();
    });

});


/*thermal print size choose*/
$(".thermal_button").click(function(){

 var id = $(this).data("thermal") ;

     $(".thermal_button").css("border-color", 'none');
     $(".thermal_button").css("background-color", '#fff');
     $(".thermal_button").css("color", '#28a745');
     $(".thermal_button").removeClass("active");
     $("#thermal_invoice"+id).css("color", '#ffffff');
     $("#thermal_invoice"+id).css("background-color", '#28a745');
     $("#thermal_invoice"+id).css("border-color", '#28a745');
     $("#thermal_invoice"+id).addClass("active");
    if(id==1){
        $('.thermal_2inch').show();
        $('.thermal_3inch').hide();
     }else{
        $('.thermal_3inch').show(); 
        $('.thermal_2inch').hide();   
     }
});


$(".save_thermal_settings").click(function() {

    var thermal_print = $(".thermal_button.active").attr('data-thermal');
    $.ajax({
        type: "POST",
        url: 'update', // This is what I have updated
        data: {
            "_method": "PATCH",
            "_token": $('input[name="_token"]').attr('value'),
            app_thermal_print: thermal_print,
        }
    }).done(function(msg) {
        window.location.reload();
    });

});

$(document).ready(function(){
    $('.thermal_prints').printPage();
});


function adjustStock(elem) {
  var product_id = $(elem).data('id');
  $.ajax({
      type: "POST",
      url: 'products/getProductDetailsbyID', // This is what I have updated
      dataType:'json',
      data: {
          "_method": "POST",
          "_token": $('input[name="_token"]').attr('value'),
          "product_id": product_id
      }
  }).done(function(msg) {
      $('#product_name').val(msg.name);
      $('#product_id').val(msg.id);
      $('#stock_level').val(msg.stock);
  });
}

$(document).ready(function(){
  $(".tabs li a").click(function() {
    
    // Active state for tabs
    $(".tabs li a").removeClass("active");
    $(this).addClass("active");
    
    // Active state for Tabs Content
    $(".tab_content_container > .tab_content_active").removeClass("tab_content_active").fadeOut(200);
    $(this.rel).fadeIn(500).addClass("tab_content_active");
    
  }); 

});


//Key Codes

    $(document).keyup(function(e){

      var keycode=e.keyCode;
      //alert(keycode);
      if (e.shiftKey == true && keycode == 77) { //Add items
        addItem(); 
      } else if(e.shiftKey == true &&  keycode == 66) { //Scan Barcode items
        addItembyBarcode();
      } else if(e.altKey == true && keycode == 13) { //Submit Order
        $('button[type="submit"]').trigger('click');
      } else if(e.shiftKey == true && keycode == 89) { //Show Party
        $(".select2").select2('open');
      } else if(e.shiftKey == true && keycode == 80) { //Print
         $('#print-invoice')[0].click();   
      } else if(e.shiftKey == true && keycode == 68) { //Download
        $('#download-invoice')[0].click();
      } else if(e.shiftKey == true && keycode == 84) { //Thermal Print
        $('#thermal-print-invoice').trigger('click');
      } else if(e.shiftKey == true && keycode == 82) { //Thermal Print
        $('#cash_paid').focus();
      } else if(e.shiftKey == true && keycode == 78) { //New Invoice
        $('#new-invoice')[0].click();
      }

  });

//Key Codes


function addPriceVariations() {
  $('#price-variation-table tr:last').after('<tr><input type="hidden" name="variation_id[]" value="0" /><td><input type="text" name="product_purchase_quantity[]" class="form-control"></td><td><input type="number" name="product_price_multiplier[]" class="form-control"></td></tr>');
}

// New Scripts