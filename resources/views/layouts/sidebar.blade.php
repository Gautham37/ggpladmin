<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-{{setting('theme_contrast')}}-{{setting('theme_color')}} elevation-4">
    <!-- Brand Logo -->
    <a href="{{url('dashboard')}}" class="brand-link text-left {{setting('logo_bg_color','bg-white')}}">
        <img src="{{$app_logo}}" alt="{{setting('app_name')}}" style="width: 65px;height:65px;">
		
        
        <b style="font-size:14px;text-align:center;margin-left: 5px;vertical-align: middle;">{{setting('app_name')}}</b>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @include('layouts.menu',['icons'=>true])
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
