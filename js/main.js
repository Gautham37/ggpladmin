jQuery.validator.addMethod("validate_email", function(value, element) {

    if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
        return true;
    } else {
        return false;
    }
}, "Please enter a valid Email.");

jQuery.validator.addMethod(
    "mobileValidation",
    function(value, element) {
        return !/^\d{10}$/.test(value) ? false : true;
    },
    "Mobile number invalid"
);

jQuery.validator.addMethod(
    "phoneValidation",
    function(value, element) {
        return !/^\d{11}$/.test(value) ? false : true;
    },
    "Phone number invalid"
);


$('.department-form').validate({ // initialize the plugin
    rules: {
        name: {
            required: true
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.department-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.department-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            $('.department-submit').html('Save Changes');
            $('.department-submit').attr("disabled", false);        
            $('#DeptModal').modal('hide');
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Department Created Sucessfully'
            });
            
            var mySelect = $(".department_id").append('<option selected value="'+results.id+'">'+results.name+'</option>'); 
            mySelect.trigger("change");

        })
        .fail(function(results) {            
            $('.department-submit').html('Save Changes');
            $('.department-submit').attr("disabled", false);        
            $('#DeptModal').modal('hide');            
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });
        });
    }
});


$('.designation-form').validate({ // initialize the plugin
    rules: {
        name: {
            required: true
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.designation-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.designation-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            $('.designation-form-submit').html('Save Changes');
            $('.designation-form-submit').attr("disabled", false);        
            $('#staffDesignationModal').modal('hide');
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Designation Created Sucessfully'
            });
            
            var mySelect = $(".staff_designation_id").append('<option selected value="'+results.id+'">'+results.name+'</option>'); 
            mySelect.trigger("change");

        })
        .fail(function(results) {            
            $('.designation-form-submit').html('Save Changes');
            $('.designation-form-submit').attr("disabled", false);        
            $('#staffDesignationModal').modal('hide');            
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });
        });
    }
});


$('.category-form').validate({ // initialize the plugin
    rules: {
        department_id: {
            required: true,
            min:1
        },
        name: {
            required: true
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.category-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.category-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            $('.category-submit').html('Save Changes');
            $('.category-submit').attr("disabled", false);        
            $('#CategoryModal').modal('hide');
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Category Created Sucessfully'
            });
            
            var mySelect = $(".category_id").append('<option selected value="'+results.id+'">'+results.name+'</option>'); 
            mySelect.trigger("change");
            
        })
        .fail(function(results) {            
            $('.category-submit').html('Save Changes');
            $('.category-submit').attr("disabled", false);        
            $('#CategoryModal').modal('hide');            
            if(results.statusText=='Not Found') {
                iziToast.success({
                    backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                    messageColor: '#fff', 
                    timeout: 3000, 
                    icon: 'fa fa-remove', 
                    position: "bottomRight", 
                    iconColor:'#fff', 
                    message: 'Category Created Unsucessfully'
                });
            }
        });
    }
});

$('.subcategory-form').validate({ // initialize the plugin
    rules: {
        name: {
            required: true
        },
        short_name: {
            required: true
        },
        department_id: {
            required: true,
            min:1
        },
        category_id: {
            required: true,
            min:1
        },
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.subcategory-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.subcategory-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            $('.subcategory-submit').html('Save Changes');
            $('.subcategory-submit').attr("disabled", false);        
            $('#SubCategoryModal').modal('hide');
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Subcategory Created Sucessfully'
            });
            
            var mySelect = $("#subcategory").append('<option selected value="'+results.id+'">'+results.name+'</option>'); 
            mySelect.trigger("change");
            
        })
        .fail(function(results) {            
            $('.subcategory-submit').html('Save Changes');
            $('.subcategory-submit').attr("disabled", false);        
            $('#SubCategoryModal').modal('hide');           
            if(results.statusText=='Not Found') {
                iziToast.success({
                    backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                    messageColor: '#fff', 
                    timeout: 3000, 
                    icon: 'fa fa-remove', 
                    position: "bottomRight", 
                    iconColor:'#fff', 
                    message: 'Subcategory Created Unsucessfully'
                });
            }
        });
    }
});

$('.quality-grade-form').validate({ // initialize the plugin
    rules: {
        name: {
            required: true
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.quality-grade-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.quality-grade-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            $('.quality-grade-submit').html('Save Changes');
            $('.quality-grade-submit').attr("disabled", false);        
            $('#QualitygradeModal').modal('hide');
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Quality Grade Created Sucessfully'
            });
            
            var mySelect = $("#quality_grade").append('<option selected value="'+results.id+'">'+results.name+'</option>'); 
            mySelect.trigger("change");

        })
        .fail(function(results) {            
            $('.quality-grade-submit').html('Save Changes');
            $('.quality-grade-submit').attr("disabled", false);        
            $('#QualitygradeModal').modal('hide');            
            if(results.statusText=='Not Found') {
                iziToast.success({
                    backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                    messageColor: '#fff', 
                    timeout: 3000, 
                    icon: 'fa fa-remove', 
                    position: "bottomRight", 
                    iconColor:'#fff', 
                    message: 'Quality Grade Created Unsucessfully'
                });
            }
        });
    }
});


$('.stock-status-form').validate({ // initialize the plugin
    rules: {
        status: {
            required: true
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.stock-status-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.stock-status-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            $('.stock-status-submit').html('Save Changes');
            $('.stock-status-submit').attr("disabled", false);        
            $('#StockstatusModal').modal('hide');
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Stock Status Created Sucessfully'
            });
            
            var mySelect = $("#stock_status").append('<option selected value="'+results.id+'">'+results.status+'</option>'); 
            mySelect.trigger("change");

        })
        .fail(function(results) {            
            $('.stock-status-submit').html('Save Changes');
            $('.stock-status-submit').attr("disabled", false);        
            $('#StockstatusModal').modal('hide');           
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });
        });
    }
});


$('.value-added-service-form').validate({ // initialize the plugin
    rules: {
        name: {
            required: true
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.value-added-service-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.value-added-service-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            $('.value-added-service-submit').html('Save Changes');
            $('.value-added-service-submit').attr("disabled", false);        
            $('#ValueaddedserviceModal').modal('hide');
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Value Added Service Created Sucessfully'
            });
            
            var mySelect = $("#value_added_service_affiliated").append('<option selected value="'+results.id+'">'+results.name+'</option>'); 
            mySelect.trigger("change");

        })
        .fail(function(results) {            
            $('.value-added-service-submit').html('Save Changes');
            $('.value-added-service-submit').attr("disabled", false);        
            $('#ValueaddedserviceModal').modal('hide');           
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });
        });
    }
});


$('.party-type-form').validate({ // initialize the plugin
    rules: {
        name: {
            required: true
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.party-type-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.party-type-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            $('.party-type-submit').html('Save Changes');
            $('.party-type-submit').attr("disabled", false);        
            $('#partyTypeModal').modal('hide');
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Party Type Created Sucessfully'
            });
            
            var mySelect = $(".type").append('<option selected value="'+results.id+'">'+results.name+'</option>'); 
            mySelect.trigger("change");

        })
        .fail(function(results) {            
            $('.party-type-submit').html('Save Changes');
            $('.party-type-submit').attr("disabled", false);        
            $('#partyTypeModal').modal('hide');          
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });
        });
    }
});


$('.party-sub-type-form').validate({ // initialize the plugin
    rules: {
        name: {
            required: true
        },
        party_type_id: {
            required: true,
            min:1
        },
        prefix_value: {
            required: true
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.party-sub-type-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.party-sub-type-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            $('.party-sub-type-submit').html('Save Changes');
            $('.party-sub-type-submit').attr("disabled", false);        
            $('#partySubtypeModal').modal('hide');
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Party Sub Type Created Sucessfully'
            });
            
            var mySelect = $(".sub_type").append('<option selected value="'+results.id+'">'+results.name+'</option>'); 
            mySelect.trigger("change");

        })
        .fail(function(results) {            
            $('.party-sub-type-submit').html('Save Changes');
            $('.party-sub-type-submit').attr("disabled", false);        
            $('#partySubtypeModal').modal('hide');         
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });
        });
    }
});


$('.party-stream-form').validate({ // initialize the plugin
    rules: {
        name: {
            required: true
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.party-stream-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.party-stream-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            $('.party-stream-submit').html('Save Changes');
            $('.party-stream-submit').attr("disabled", false);        
            $('#partyStreamModal').modal('hide');
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Party Stream Created Sucessfully'
            });
            
            var mySelect = $(".stream").append('<option selected value="'+results.id+'">'+results.name+'</option>'); 
            mySelect.trigger("change");

        })
        .fail(function(results) {            
            $('.party-stream-submit').html('Save Changes');
            $('.party-stream-submit').attr("disabled", false);        
            $('#partyStreamModal').modal('hide');         
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });
        });
    }
});


$('.customer-group-form').validate({ // initialize the plugin
    rules: {
        name: {
            required: true
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.customer-group-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.customer-group-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            $('.customer-group-submit').html('Save Changes');
            $('.customer-group-submit').attr("disabled", false);        
            $('#customerGroupModal').modal('hide');
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Customer Group Created Sucessfully'
            });
            
            var mySelect = $("#customer_group_id").append('<option selected value="'+results.id+'">'+results.name+'</option>'); 
            mySelect.trigger("change");

        })
        .fail(function(results) {            
            $('.customer-group-submit').html('Save Changes');
            $('.customer-group-submit').attr("disabled", false);        
            $('#customerGroupModal').modal('hide');         
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });
        });
    }
});


$('.product-season-form').validate({ // initialize the plugin
    rules: {
        name: {
            required: true
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.product-season-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.product-season-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            $('.product-season-submit').html('Save Changes');
            $('.product-season-submit').attr("disabled", false);        
            $('#productSeasonModal').modal('hide');
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Product Season Created Sucessfully'
            });
            
            var mySelect = $("#season_id").append('<option selected value="'+results.id+'">'+results.name+'</option>'); 
            mySelect.trigger("change");

        })
        .fail(function(results) {            
            $('.product-season-submit').html('Save Changes');
            $('.product-season-submit').attr("disabled", false);        
            $('#productSeasonModal').modal('hide');       
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });
        });
    }
});


$('.product-color-form').validate({ // initialize the plugin
    rules: {
        name: {
            required: true
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.product-color-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.product-color-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            $('.product-color-submit').html('Save Changes');
            $('.product-color-submit').attr("disabled", false);        
            $('#productColorModal').modal('hide');
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Product Color Created Sucessfully'
            });
            
            var mySelect = $("#color_id").append('<option selected value="'+results.id+'">'+results.name+'</option>'); 
            mySelect.trigger("change");

        })
        .fail(function(results) {            
            $('.product-color-submit').html('Save Changes');
            $('.product-color-submit').attr("disabled", false);        
            $('#productColorModal').modal('hide');      
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });
        });
    }
});


$('.product-nutrition-form').validate({ // initialize the plugin
    rules: {
        name: {
            required: true
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.product-nutrition-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.product-nutrition-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            $('.product-nutrition-submit').html('Save Changes');
            $('.product-nutrition-submit').attr("disabled", false);        
            $('#productNutritionModal').modal('hide');
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Product Nutrition Created Sucessfully'
            });
            
            var mySelect = $("#nutrition_id").append('<option selected value="'+results.id+'">'+results.name+'</option>'); 
            mySelect.trigger("change");

        })
        .fail(function(results) {            
            $('.product-nutrition-submit').html('Save Changes');
            $('.product-nutrition-submit').attr("disabled", false);        
            $('#productNutritionModal').modal('hide');      
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });
        });
    }
});


$('.product-taste-form').validate({ // initialize the plugin
    rules: {
        name: {
            required: true
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.product-taste-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.product-taste-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            $('.product-taste-submit').html('Save Changes');
            $('.product-taste-submit').attr("disabled", false);        
            $('#productTasteModal').modal('hide');
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Product Taste Created Sucessfully'
            });
            
            var mySelect = $("#taste_id").append('<option selected value="'+results.id+'">'+results.name+'</option>'); 
            mySelect.trigger("change");

        })
        .fail(function(results) {            
            $('.product-taste-submit').html('Save Changes');
            $('.product-taste-submit').attr("disabled", false);        
            $('#productTasteModal').modal('hide');      
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });
        });
    }
});



$('.party-form').validate({ // initialize the plugin
    rules: {
        name: {
            required: true
        },
        email: {
            validate_email: true
        },
        customer_group_id: {
            required: true  
        },
        mobile: {
            required: true,
            mobileValidation: $("#mobile").val(), 
        },
        phone: {
            phoneValidation: $("#phone").val(), 
        },
        type: {
            required: true
        },
        sub_type: {
            required: true
        },
        stream: {
            required: true  
        },
        staff_designation_id: {
            required: true  
        },
        date_of_joining: {
            required: true  
        },
        probation_ended_on: {
            required: true  
        },
        "d_street_no[][]": {
            required: true
        },
        "d_street_type[]": {
            required: true
        },
        "d_address_line_1[]": {
            required: true
        },
        "d_town[]": {
            required: true
        },
        "d_city[]": {
            required: true
        },
        "d_state[]": {
            required: true
        },
        "d_pincode[]": {
            required: true
        }
    },
    highlight: function (element, errorClass, validClass) {   
        var elem = $(element);
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').addClass(errorClass);
        } else {
            elem.addClass(errorClass);
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        var elem = $(element); 
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').removeClass(errorClass);
        } else {
            elem.removeClass(errorClass);
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.party-form-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.party-form-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            
            $('.party-form-submit').html('<i class="fa fa-save"></i> Save Party');
            $('.party-form-submit').attr("disabled", false);

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "topRight", 
                iconColor:'#fff',
                message: 'Party Created Sucessfully'
            });
            
            setTimeout(function () { location.reload(); }, 1000);

        })
        .fail(function(results) {            
            
            $('.party-form-submit').html('<i class="fa fa-save"></i> Save Party');
            $('.party-form-submit').attr("disabled", false);

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "topRight", 
                iconColor:'#fff', 
                message: results.statusText
            });
        });
    }
});


$('.party-modal-form').validate({ // initialize the plugin
    rules: {
        name: {
            required: true
        },
        email: {
            validate_email: true
        },
        customer_group_id: {
            required: true  
        },
        mobile: {
            required: true,
            mobileValidation: $("#mobile").val(), 
        },
        phone: {
            phoneValidation: $("#phone").val(), 
        },
        type: {
            required: true
        },
        sub_type: {
            required: true
        },
        stream: {
            required: true  
        },
        "d_street_no[][]": {
            required: true
        },
        "d_street_type[]": {
            required: true
        },
        "d_address_line_1[]": {
            required: true
        },
        "d_town[]": {
            required: true
        },
        "d_city[]": {
            required: true
        },
        "d_state[]": {
            required: true
        },
        "d_pincode[]": {
            required: true
        }
    },
    highlight: function (element, errorClass, validClass) {   
        var elem = $(element);
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').addClass(errorClass);
        } else {
            elem.addClass(errorClass);
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        var elem = $(element); 
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').removeClass(errorClass);
        } else {
            elem.removeClass(errorClass);
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.party-modal-form-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.party-modal-form-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            
            $('.party-modal-form-submit').html('<i class="fa fa-save"></i> Save Party');
            $('.party-modal-form-submit').attr("disabled", false);
            $('#partyModal').modal('hide');

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "topRight", 
                iconColor:'#fff',
                message: 'Party Created Sucessfully'
            });
            
            var mySelect = $("#market_id").append('<option selected value="'+results.id+'">'+results.name+' '+results.mobile+' '+results.code+'</option>'); 
            mySelect.trigger("change");    

        })
        .fail(function(results) {            
            
            $('.party-modal-form-submit').html('<i class="fa fa-save"></i> Save Party');
            $('.party-modal-form-submit').attr("disabled", false);

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "topRight", 
                iconColor:'#fff', 
                message: results.statusText
            });
        });
    }
});

$('#productForm').validate({ // initialize the plugin
    rules: {
        name: {
            required: true
        },
        department_id: {
            required: true
        },
        category_id: {
            required: true
        },
        subcategory_id: {
            required: true
        },
        purchase_price: {
            required: true
        },
        price: {
            required: true
        },
        hsn_code: {
            required: true
        },
        description: {
            required: true
        },
        secondary_unit: {
           required: true 
        },
        conversion_rate: {
           required: true 
        },
        category_name: {
           required: true 
        },
        alpha: {
           required: true 
        },
        product_code_short: {
           required: true 
        },
        product_varient: {
           required: true 
        },
        product_varient_number: {
           required: true 
        },
        con: {
           required: true 
        },
        product_code: {
           required: true 
        },
        short_product_code: {
           required: true 
        },
        "product_purchase_quantity[]": {
           required: true 
        },
        "product_price_multiplier[]": {
           required: true 
        },
        "vas_id[]": {
           required: true 
        },
        "vas_price[]": {
           required: true 
        }
    },
    highlight: function (element, errorClass, validClass) {   
        var elem = $(element);
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').addClass(errorClass);
        } else {
            elem.addClass(errorClass);
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        var elem = $(element); 
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').removeClass(errorClass);
        } else {
            elem.removeClass(errorClass);
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.product-form-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.product-form-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            
            $('.product-form-submit').html('<i class="fa fa-save"></i> Save Product');
            $('.product-form-submit').attr("disabled", false);

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "topRight", 
                iconColor:'#fff',
                message: 'Product Created Sucessfully'
            });
            
            setTimeout(function () { location.reload(); }, 1000);

        })
        .fail(function(results) {            
            
            $('.product-form-submit').html('<i class="fa fa-save"></i> Save Product');
            $('.product-form-submit').attr("disabled", false);

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "topRight", 
                iconColor:'#fff', 
                message: results.statusText
            });
        });
    }
});


function calculateInvoiceTotals() {

        var sub_total = 0;
        $(".amount").each(function() {
            var val = this.value;
            sub_total += val == "" || isNaN(val) ? 0 : parseFloat(val);
        });
      
        var discount_amount = 0;
        $(".discount_amount").each(function() {
            var dis_val = this.value;
            discount_amount += dis_val == "" || isNaN(dis_val) ? 0 : parseFloat(dis_val);
        });
      
        var tax_amount = 0;
        $(".tax_amount").each(function() {
            var tax_val = this.value;
            tax_amount += tax_val == "" || isNaN(tax_val) ? 0 : parseFloat(tax_val);
        });
      
        var additional_charge    = ($('.additional_charge').val() > 0) ? $('.additional_charge').val() : 0 ;
        var delivery_charge      = ($('.delivery_charge').val() > 0) ? $('.delivery_charge').val() : 0 ;
        var cash_paid            = ($('.cash_paid').val() > 0) ? $('.cash_paid').val() : 0 ;

        $('.discount_total').val((parseFloat(discount_amount)).toFixed(2));
        $('.tax_total').val((parseFloat(tax_amount)).toFixed(2));
        $('.sub_total').val( ( (parseFloat(sub_total) + parseFloat(discount_amount)) - parseFloat(tax_amount) ).toFixed(2) );
      
        $('.additional_charge').val((parseFloat(additional_charge)).toFixed(2));
        $('.delivery_charge').val((parseFloat(delivery_charge)).toFixed(2));
        $('.cash_paid').val((parseFloat(cash_paid)).toFixed(2));

    

        var total_rewards = ($('#total_rewards').val() > 0) ? $('#total_rewards').val() : 0 ;
        var redeem_value  = 0;
        var redeem_points = ($('.redeem_points').val() > 0) ? $('.redeem_points').val() : 0 ;
         
        if(total_rewards > 0 && redeem_points > 0) {
            if(redeem_points >= 100) {
                if(parseFloat(redeem_points) <= parseFloat(total_rewards)) { 
                    var redeem_value          = parseFloat(redeem_points) / 100;
                    if(redeem_value) {
                        iziToast.success({title: 'Success', position: 'topRight', message: 'Redeem points applied!'});
                    }
                    $('.redeemed-point').html(redeem_value);
                    $('.used_point_worth').val(redeem_value);
                } else {
                    iziToast.warning({title: 'Invalid Points', position: 'topRight', message: 'Please enter Points below '+total_rewards});
                }
            } else {
                iziToast.warning({title: 'Invalid Points', position: 'topRight', message: 'Minimum 100 reward points redeem at a time'}); 
            }
        } else {
            $('.redeem_points').val('');
        }


        var grand_total = Math.round((parseFloat(sub_total) + parseFloat(additional_charge) + parseFloat(delivery_charge)) - parseFloat(redeem_value));
        var amount_due  = parseFloat(grand_total) - parseFloat(cash_paid);

        $('.total').val(parseFloat(grand_total).toFixed(2));
        $('.amount_due').val(parseFloat(amount_due).toFixed(2));
      
      
        if(parseFloat(amount_due) <= 0) {
            $('.mark_as_paid').prop('checked', true);
        } else {
            $('.mark_as_paid').prop('checked', false);
        }


}

$('.mark_as_paid').click(function(){
    if($(this).is(':checked')){
        var total = $('.total').val();
        $('.cash_paid').val((parseFloat(total)).toFixed(2));
        calculateInvoiceTotals();
    } else {
        $('.cash_paid').val(0.00);
        calculateInvoiceTotals();
    }
});

$('.redeem-points-button').click(function() {
    calculateInvoiceTotals();
});


$('.sales-invoice-form').validate({ // initialize the plugin
    rules: {
        market_id: {
            required: true
        },
        code: {
            required: true
        },
        date: {
            required: true
        },
        valid_date: {
            required: true
        },
        sub_total: {
            required: true,
            min:1
        },
        total: {
            required: true,
            min:1
        }
    },
    highlight: function (element, errorClass, validClass) {   
        var elem = $(element);
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').addClass(errorClass);
        } else {
            elem.addClass(errorClass);
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        var elem = $(element); 
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').removeClass(errorClass);
        } else {
            elem.removeClass(errorClass);
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.sales-invoice-form-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.sales-invoice-form-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            
            $('.sales-invoice-form-submit').html('<i class="fa fa-save"></i> Save Sales Invoice');
            $('.sales-invoice-form-submit').attr("disabled", false);

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Sales Invoice Created Sucessfully'
            });

            setTimeout(function () { location.reload(); }, 1000);

        })
        .fail(function(results) {            
            
            $('.sales-invoice-form-submit').html('<i class="fa fa-save"></i> Save Sales Invoice');
            $('.sales-invoice-form-submit').attr("disabled", false); 

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });

        });
    }
});

$('.sales-return-form').validate({ // initialize the plugin
    rules: {
        market_id: {
            required: true
        },
        code: {
            required: true
        },
        date: {
            required: true
        },
        valid_date: {
            required: true
        },
        sub_total: {
            required: true,
            min:1
        },
        total: {
            required: true,
            min:1
        }
    },
    highlight: function (element, errorClass, validClass) {   
        var elem = $(element);
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').addClass(errorClass);
        } else {
            elem.addClass(errorClass);
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        var elem = $(element); 
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').removeClass(errorClass);
        } else {
            elem.removeClass(errorClass);
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.sales-return-form-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.sales-return-form-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            
            $('.sales-return-form-submit').html('<i class="fa fa-save"></i> Save Sales Return');
            $('.sales-return-form-submit').attr("disabled", false);

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Sales Return Created Sucessfully'
            });

            setTimeout(function () { location.reload(); }, 1000);

        })
        .fail(function(results) {            
            
            $('.sales-return-form-submit').html('<i class="fa fa-save"></i> Save Sales Return');
            $('.sales-return-form-submit').attr("disabled", false); 

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });

        });
    }
});



$('.payment-in-form').validate({ // initialize the plugin
    rules: {
        market_id: {
            required: true
        },
        code: {
            required: true
        },
        date: {
            required: true
        },
        payment_method: {
            required: true
        },
        total: {
            required: true,
            number:true,
            min: 1
        }
    },
    highlight: function (element, errorClass, validClass) {   
        var elem = $(element);
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').addClass(errorClass);
        } else {
            elem.addClass(errorClass);
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        var elem = $(element); 
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').removeClass(errorClass);
        } else {
            elem.removeClass(errorClass);
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.payment-in-form-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.payment-in-form-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            
            $('.payment-in-form-submit').html('<i class="fa fa-save"></i> Save Payment In');
            $('.payment-in-form-submit').attr("disabled", false);

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Payment In Created Sucessfully'
            });

            setTimeout(function () { location.reload(); }, 1000);

        })
        .fail(function(results) {            
            
            $('.payment-in-form-submit').html('<i class="fa fa-save"></i> Save Payment In');
            $('.payment-in-form-submit').attr("disabled", false); 

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });

        });
    }
});


$('.purchase-order-form').validate({ // initialize the plugin
    rules: {
        market_id: {
            required: true
        },
        code: {
            required: true
        },
        date: {
            required: true
        },
        valid_date: {
            required: true
        },
        sub_total: {
            required: true,
            min:1
        },
        total: {
            required: true,
            min:1
        }
    },
    highlight: function (element, errorClass, validClass) {   
        var elem = $(element);
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').addClass(errorClass);
        } else {
            elem.addClass(errorClass);
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        var elem = $(element); 
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').removeClass(errorClass);
        } else {
            elem.removeClass(errorClass);
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.purchase-order-form-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.purchase-order-form-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            
            $('.purchase-order-form-submit').html('<i class="fa fa-save"></i> Save Purchase Order');
            $('.purchase-order-form-submit').attr("disabled", false);

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Purchase Order Created Sucessfully'
            });

            setTimeout(function () { location.reload(); }, 1000);

        })
        .fail(function(results) {            
            
            $('.purchase-order-form-submit').html('<i class="fa fa-save"></i> Save Purchase Order');
            $('.purchase-order-form-submit').attr("disabled", false); 

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });

        });
    }
});


$('.purchase-invoice-form').validate({ // initialize the plugin
    rules: {
        market_id: {
            required: true
        },
        code: {
            required: true
        },
        date: {
            required: true
        },
        valid_date: {
            required: true
        },
        sub_total: {
            required: true,
            min:1
        },
        total: {
            required: true,
            min:1
        }
    },
    highlight: function (element, errorClass, validClass) {   
        var elem = $(element);
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').addClass(errorClass);
        } else {
            elem.addClass(errorClass);
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        var elem = $(element); 
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').removeClass(errorClass);
        } else {
            elem.removeClass(errorClass);
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.purchase-invoice-form-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.purchase-invoice-form-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            
            $('.purchase-invoice-form-submit').html('<i class="fa fa-save"></i> Save Purchase Invoice');
            $('.purchase-invoice-form-submit').attr("disabled", false);

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Purchase Invoice Created Sucessfully'
            });

            setTimeout(function () { location.reload(); }, 1000);

        })
        .fail(function(results) {            
            
            $('.purchase-invoice-form-submit').html('<i class="fa fa-save"></i> Save Purchase Invoice');
            $('.purchase-invoice-form-submit').attr("disabled", false); 

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });

        });
    }
});


$('.purchase-return-form').validate({ // initialize the plugin
    rules: {
        market_id: {
            required: true
        },
        code: {
            required: true
        },
        date: {
            required: true
        },
        valid_date: {
            required: true
        },
        sub_total: {
            required: true,
            min:1
        },
        total: {
            required: true,
            min:1
        }
    },
    highlight: function (element, errorClass, validClass) {   
        var elem = $(element);
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').addClass(errorClass);
        } else {
            elem.addClass(errorClass);
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        var elem = $(element); 
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').removeClass(errorClass);
        } else {
            elem.removeClass(errorClass);
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.purchase-return-form-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.purchase-return-form-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            
            $('.purchase-return-form-submit').html('<i class="fa fa-save"></i> Save Purchase Return');
            $('.purchase-return-form-submit').attr("disabled", false);

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Purchase Return Created Sucessfully'
            });

            setTimeout(function () { location.reload(); }, 1000);

        })
        .fail(function(results) {            
            
            $('.purchase-return-form-submit').html('<i class="fa fa-save"></i> Save Purchase Return');
            $('.purchase-return-form-submit').attr("disabled", false); 

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });

        });
    }
});


$('.payment-out-form').validate({ // initialize the plugin
    rules: {
        market_id: {
            required: true
        },
        code: {
            required: true
        },
        date: {
            required: true
        },
        payment_method: {
            required: true
        },
        total: {
            required: true,
            number:true,
            min: 1
        }
    },
    highlight: function (element, errorClass, validClass) {   
        var elem = $(element);
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').addClass(errorClass);
        } else {
            elem.addClass(errorClass);
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        var elem = $(element); 
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').removeClass(errorClass);
        } else {
            elem.removeClass(errorClass);
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.payment-out-form-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.payment-out-form-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            
            $('.payment-out-form-submit').html('<i class="fa fa-save"></i> Save Payment Out');
            $('.payment-out-form-submit').attr("disabled", false);

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Payment Out Created Sucessfully'
            });

            setTimeout(function () { location.reload(); }, 1000);

        })
        .fail(function(results) {            
            
            $('.payment-out-form-submit').html('<i class="fa fa-save"></i> Save Payment Out');
            $('.payment-out-form-submit').attr("disabled", false); 

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });

        });
    }
});


$('.vendor-stock-form').validate({ // initialize the plugin
    rules: {
        market_id: {
            required: true
        },
        code: {
            required: true
        },
        date: {
            required: true
        },
        total: {
            required: true,
            number:true,
            min: 1
        }
    },
    highlight: function (element, errorClass, validClass) {   
        var elem = $(element);
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').addClass(errorClass);
        } else {
            elem.addClass(errorClass);
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        var elem = $(element); 
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').removeClass(errorClass);
        } else {
            elem.removeClass(errorClass);
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.vendor-stock-form-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.vendor-stock-form-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            
            $('.vendor-stock-form-submit').html('<i class="fa fa-save"></i> Save Vendor Stock');
            $('.vendor-stock-form-submit').attr("disabled", false);

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Vendor Stock Created Sucessfully'
            });

            setTimeout(function () { location.reload(); }, 1000);

        })
        .fail(function(results) {            
            
            $('.vendor-stock-form-submit').html('<i class="fa fa-save"></i> Save Vendor Stock');
            $('.vendor-stock-form-submit').attr("disabled", false); 

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });

        });
    }
});


$('.inventory-form').validate({ // initialize the plugin
    rules: {
        product_id: {
            required: true
        },
        type: {
            required: true
        },
        quantity: {
            required: true
        },
        unit: {
            required: true
        },
        usage: {
            required: true
        }
    },
    highlight: function (element, errorClass, validClass) {   
        var elem = $(element);
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').addClass(errorClass);
        } else {
            elem.addClass(errorClass);
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        var elem = $(element); 
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').removeClass(errorClass);
        } else {
            elem.removeClass(errorClass);
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.inventory-form-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.inventory-form-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            
            $('.inventory-form-submit').html('<i class="fa fa-save"></i> Save Changes');
            $('.inventory-form-submit').attr("disabled", false);
            $('#productInventoryModal').modal('hide');

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Inventory Updated Sucessfully'
            });

            setTimeout(function () { location.reload(); }, 1000);

        })
        .fail(function(results) {            
            
            $('.inventory-form-submit').html('<i class="fa fa-save"></i> Save Changes');
            $('.inventory-form-submit').attr("disabled", false);

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });

        });
    }
});

$('.expenses-form').validate({ // initialize the plugin
    rules: {
        expense_category_id: {
            required: true
        },
        payment_mode: {
            required: true
        },
        created_by: {
            required: true
        },
        date: {
            required: true
        },
        total_amount: {
            required: true,
            min:1
        }
    },
    highlight: function (element, errorClass, validClass) {   
        var elem = $(element);
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').addClass(errorClass);
        } else {
            elem.addClass(errorClass);
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        var elem = $(element); 
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').removeClass(errorClass);
        } else {
            elem.removeClass(errorClass);
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.expenses-form-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.expenses-form-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            
            $('.expenses-form-submit').html('<i class="fa fa-save"></i> Save Changes');
            $('.expenses-form-submit').attr("disabled", false);
            
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Expense Saved Sucessfully'
            });

            setTimeout(function () { location.reload(); }, 1000);

        })
        .fail(function(results) {            
            
            $('.expenses-form-submit').html('<i class="fa fa-save"></i> Save Changes');
            $('.expenses-form-submit').attr("disabled", false);

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });

        });
    }
});


$('.quotes-form').validate({ // initialize the plugin
    rules: {
        market_id: {
            required: true
        },
        code: {
            required: true
        },
        date: {
            required: true
        },
        valid_date: {
            required: true
        },
        sub_total: {
            required: true,
            min:1
        },
        total: {
            required: true,
            min:1
        }
    },
    highlight: function (element, errorClass, validClass) {   
        var elem = $(element);
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').addClass(errorClass);
        } else {
            elem.addClass(errorClass);
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        var elem = $(element); 
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').removeClass(errorClass);
        } else {
            elem.removeClass(errorClass);
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.quotes-form-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.quotes-form-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            
            $('.quotes-form-submit').html('<i class="fa fa-save"></i> Save Quote');
            $('.quotes-form-submit').attr("disabled", false);

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Quote Created Sucessfully'
            });

            setTimeout(function () { location.reload(); }, 1000);

        })
        .fail(function(results) {            
            
            $('.quotes-form-submit').html('<i class="fa fa-save"></i> Save Quote');
            $('.quotes-form-submit').attr("disabled", false); 

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });

        });
    }
});


$('.market-notes-form').validate({ // initialize the plugin
    rules: {
        market_id: {
            required: true
        },
        notes: {
            required: true
        }
    },
    highlight: function (element, errorClass, validClass) {   
        var elem = $(element);
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').addClass(errorClass);
        } else {
            elem.addClass(errorClass);
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        var elem = $(element); 
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').removeClass(errorClass);
        } else {
            elem.removeClass(errorClass);
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.market-notes-form-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.market-notes-form-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            
            $('.market-notes-form-submit').html('<i class="fa fa-save"></i> Save Notes');
            $('.market-notes-form-submit').attr("disabled", false);

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Quote Created Sucessfully'
            });
            marketNotes();

        })
        .fail(function(results) {            
            
            $('.market-notes-form-submit').html('<i class="fa fa-save"></i> Save Notes');
            $('.market-notes-form-submit').attr("disabled", false); 

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });

        });
    }
});


$('.stock-take-form1').validate({ // initialize the plugin
    rules: {
        code: {
            required: true
        },
        date: {
            required: true
        }
    },
    highlight: function (element, errorClass, validClass) {   
        var elem = $(element);
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').addClass(errorClass);
        } else {
            elem.addClass(errorClass);
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        var elem = $(element); 
        if(elem.hasClass('select2')) {
            elem.siblings('.select2-container').removeClass(errorClass);
        } else {
            elem.removeClass(errorClass);
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.stock-take-form-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.stock-take-form-submit').attr("disabled", true);
        
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            
            $('.stock-take-form-submit').html('<i class="fa fa-save"></i> Save Stock Take');
            $('.stock-take-form-submit').attr("disabled", false);
            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Stock Take Created Sucessfully'
            });
            setTimeout(function () { location.reload(); }, 1000);

        })
        .fail(function(results) {            
            
            $('.stock-take-form-submit').html('<i class="fa fa-save"></i> Save Stock Take');
            $('.stock-take-form-submit').attr("disabled", false); 

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });

        });
    }
});



$('.market-activity-form').validate({ // initialize the plugin
    rules: {
        action: {
            required: true
        },
        notes: {
            required: true
        },
        priority: {
            required: true
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) { },
    success: function (error) {
        error.remove();
    },
    submitHandler: function(form) {
        
        $('.market-activity-form-submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.market-activity-form-submit').attr("disabled", true);
        
        $.ajax({
            url: "https://www.localhost/ggpl_admin/marketActivity",
            type: form.method,
            data: $(form).serialize()         
        }).done(function(results) {            
            
            $('.market-activity-form-submit').html('<i class="fa fa-save"></i> Save Activity');
            $('.market-activity-form-submit').attr("disabled", false);

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                messageColor: '#fff',
                timeout: 3000, 
                icon: 'fa fa-check', 
                position: "bottomRight", 
                iconColor:'#fff',
                message: 'Party Activity Created Sucessfully'
            });
            $('.market-activity-form')[0].reset();
            $('#marketActivityModal').modal('hide');
            activity_table.ajax.reload();
            marketActivity();

        })
        .fail(function(results) {            
            
            $('.market-activity-form-submit').html('<i class="fa fa-save"></i> Save Activity');
            $('.market-activity-form-submit').attr("disabled", false); 

            iziToast.success({
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                timeout: 3000, 
                icon: 'fa fa-remove', 
                position: "bottomRight", 
                iconColor:'#fff', 
                message: results.statusText
            });

        });
    }
});



$(function () {
    $(".dobdatepicker").datepicker({
        changeMonth: true,
        changeYear: true,
        showOn: 'button',
        format: "dd-mm-yyyy",
        yearRange: '1900:+0'
    }).on('change',function(dateString) {
        ValidateDOB($(this).val());
    });
});
function ValidateDOB(dateString) {
    var lblError = $("#lblError");
    var parts = dateString.split("-");
    var dtDOB = new Date(parts[1] + "/" + parts[0] + "/" + parts[2]);
    var dtCurrent = new Date();
    if (dtCurrent.getFullYear() - dtDOB.getFullYear() < 18) {
        $(".dobdatepicker").val('');
        iziToast.success({
            backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
            messageColor: '#fff', 
            timeout: 3000, 
            icon: 'fa fa-remove', 
            position: "topRight", 
            iconColor:'#fff', 
            message: 'Eligibility 18 years ONLY.'
        });
        return false;
    }

    if (dtCurrent.getFullYear() - dtDOB.getFullYear() == 18) {

        //CD: 11/06/2018 and DB: 15/07/2000. Will turned 18 on 15/07/2018.
        if (dtCurrent.getMonth() < dtDOB.getMonth()) {
            return false;
        }
        if (dtCurrent.getMonth() == dtDOB.getMonth()) {
            //CD: 11/06/2018 and DB: 15/06/2000. Will turned 18 on 15/06/2018.
            if (dtCurrent.getDate() < dtDOB.getDate()) {
                return false;
            }
        }
    }
    return true;
}