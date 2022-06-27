<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>{{setting('app_name')}}</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   <!-- Toast Alert -->
   <link rel="stylesheet" type="text/css" href="https://unpkg.com/izitoast/dist/css/iziToast.min.css">
   <style>
   /*pdf_duplicate*/
   .pdf_head {
      font-size:36px;
      color:<?= (setting('app_invoice_color')!='') ? setting('app_invoice_color')  : 'green'  ; ?>;
      font-weight:700;
      line-height: 18px;
   }
   .pdf_div {
      height:10px;
      background-color: green;
   }
   .pdf_div1 {
      background-color: #e9ecef;
      padding:5px;
   }

   .pdf_all {
      /*font-family: 'Poppins' !important; */
      font-family: Arial, Helvetica, sans-serif;
      font-size:12px;
   }
   .text-right {
      text-align: right;   
   }
   .text-left {
      text-align: left;   
   }
   .text-center {
      text-align: center;   
   }

   .pdf_all { margin:5% 20% !important;border: 1px solid #c5c4c4;; }

   .btn {
       display: inline-block;
       font-weight: 400;
       text-align: center;
       white-space: nowrap;
       vertical-align: middle;
       user-select: none;
       border: 1px solid transparent;
       padding: 0.375rem 0.75rem;
       font-size: 1rem;
       line-height: 1.5;
       border-radius: 0.25rem;
       transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
   }

   .btn-primary {
       color: #ffffff;
       background-color: #007bff;
       border-color: #007bff;
       box-shadow: 0 1px 1px rgb(0 0 0 / 8%);
   }

   .btn:not(:disabled):not(.disabled) {
       cursor: pointer;
   }

   .btn {
       font-size: 13px !important;
   }
   .btn-sm, .btn-group-sm > .btn {
       padding: 0.25rem 0.5rem;
       font-size: 0.875rem;
       line-height: 1.5;
       border-radius: 0.2rem;
   }

   </style>
</head>
<body>
    
   @yield('content') 

   <!-- jQuery -->
   <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
   <!-- Toast Alert -->
   <script src="https://unpkg.com/izitoast/dist/js/iziToast.min.js"></script>
   <!-- Toast Alert -->

   @stack('scripts')

</body>
</html>

