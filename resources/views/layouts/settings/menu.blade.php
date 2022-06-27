<div class="card settings {{ Request::is('users*') || Request::is('staffs*') || Request::is('settings/permissions*') || Request::is('settings/roles*') ? '' : 'collapsed-card' }}">
    <div class="card-header">
        <h3 class="card-title">{{trans('lang.permission_menu')}}</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa {{ Request::is('settings/users*') || Request::is('settings/permissions*') || Request::is('settings/roles*') ? 'fa-minus' : 'fa-plus' }}"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a href="{!! route('permissions.index') !!}" class="nav-link {{  Request::is('settings/permissions*') ? 'selected' : '' }}">
                    <i class="fa fa-inbox"></i> {{trans('lang.permission_plural')}}
                </a>
            </li>
            <li class="nav-item">
                <a href="{!! route('roles.index') !!}" class="nav-link {{  Request::is('settings/roles*') ? 'selected' : '' }}">
                    <i class="fa fa-inbox"></i> {{trans('lang.role_plural')}}
                </a>
            </li>

            <!--<li class="nav-item">
                <a href="{!! route('users.index') !!}" class="nav-link {{  Request::is('users*') ? 'selected' : '' }}">
                    <i class="fa fa-users"></i> {{trans('lang.user_plural')}}
                </a>
            </li>-->

            <?php /*/ ?>
            <li class="nav-item">
                <a href="{!! route('staffs.index') !!}" class="nav-link {{  Request::is('staffs*') ? 'selected' : '' }}">
                    <i class="fa fa-users"></i> {{trans('lang.staff_plural')}}
                </a>
            </li>
            <?php /*/ ?>

        </ul>
    </div>
</div>

<div class="card  settings {{
             Request::is('settings/app/*') ||
             Request::is('settings/mail*') ||
             Request::is('settings/translation*') ||
             Request::is('settings/payment*') ||
             Request::is('settings/rewards*') ||
             Request::is('settings/currencies*') ||
             Request::is('settings/customFields*') ||
             Request::is('settings/bankDetails*') || 
             Request::is('settings/addressDetails*') ||
             Request::is('settings/birthdayCustomerDetails*')
 ? '' : 'collapsed-card' }}">
    <div class="card-header">
        <h3 class="card-title">{{trans('lang.app_setting_globals')}}</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa {{
             Request::is('settings/app/*') ||
             Request::is('settings/mail*') ||
             Request::is('settings/translation*') ||
             Request::is('settings/payment*') ||
             Request::is('settings/rewards*') ||
             Request::is('settings/currencies*') ||
             Request::is('settings/customFields*') ||
             Request::is('settings/bankDetails*') ||
             Request::is('settings/addressDetails*') ||
             Request::is('settings/birthdayCustomerDetails*')
             ? 'fa-minus' : 'fa-plus' }}"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a href="{!! url('settings/app/globals') !!}" class="nav-link {{  Request::is('settings/app/globals*') ? 'selected' : '' }}">
                    <i class="fa fa-cog"></i> {{trans('lang.app_setting_globals')}}
                </a>
            </li>

            <?php /*/ ?><li class="nav-item">
                <a href="{!! url('settings/app/localisation') !!}" class="nav-link {{  Request::is('settings/app/localisation*') ? 'selected' : '' }}">
                    <i class="fa fa-language"></i> {{trans('lang.app_setting_localisation')}}
                </a>
            </li>
            <?php /*/ ?>
            <?php /*/ ?>
            <li class="nav-item">
                <a href="{!! url('settings/app/social') !!}" class="nav-link {{  Request::is('settings/app/social*') ? 'selected' : '' }}">
                    <i class="fa fa-globe"></i> {{trans('lang.app_setting_social')}}
                </a>
            </li>
            <?php /*/ ?>
            <?php /*/ ?>    
            <li class="nav-item">
                <a href="{!! url('settings/payment/payment') !!}" class="nav-link {{  Request::is('settings/payment*') ? 'selected' : '' }}">
                    <i class="fa fa-credit-card"></i> {{trans('lang.app_setting_payment')}}
                </a>
            </li>
            <?php /*/ ?>
            
            <li class="nav-item">
                <a href="{!! url('settings/rewards/rewards') !!}" class="nav-link {{  Request::is('settings/rewards*') ? 'selected' : '' }}">
                    <i class="fa fa-trophy"></i> {{trans('lang.rewards_plural')}}
                </a>
            </li>
            <?php /*/ ?>
            @can('currencies.index')
                <li class="nav-item">
                    <a href="{!! route('currencies.index') !!}" class="nav-link {{ Request::is('settings/currencies*') ? 'selected' : '' }}" ><i class="nav-icon fa fa-dollar ml-1"></i> {{trans('lang.currency_plural')}}</a>
                </li>
            @endcan
            <?php /*/ ?>

            <li class="nav-item">
                <a href="{!! url('settings/app/notifications') !!}" class="nav-link {{  Request::is('settings/app/notifications*') || Request::is('notificationTypes*') ? 'selected' : '' }}">
                    <i class="fa fa-bell"></i> {{trans('lang.app_setting_notifications')}}
                </a>
            </li>

            <li class="nav-item">
                <a href="{!! url('settings/mail/smtp') !!}" class="nav-link {{ Request::is('settings/mail*') ? 'selected' : '' }}">
                    <i class="fa fa-envelope"></i> {{trans('lang.app_setting_mail')}}
                </a>
            </li>

            <?php /*/ ?>
            <li class="nav-item">
                <a href="{!! url('settings/translation/en') !!}" class="nav-link {{ Request::is('settings/translation*') ? 'selected' : '' }}">
                    <i class="fa fa-language"></i> {{trans('lang.app_setting_translation')}}
                </a>
            </li>

            <li class="nav-item">
                <a href="{!! route('customFields.index') !!}" class="nav-link {{ Request::is('settings/customFields*') ? 'selected' : '' }}">
                    <i class="fa fa-list"></i> {{trans('lang.custom_field_plural')}}
                </a>
            </li>
            <?php /*/ ?>

            <li class="nav-item">
                <a href="{!! url('settings/bankDetails') !!}" class="nav-link {{ Request::is('settings/bankDetails*') ? 'selected' : '' }}">
                    <i class="fa fa-bank"></i> {{trans('lang.app_bank_detail_plural')}}
                </a>
            </li>

            <li class="nav-item">
                <a href="{!! url('settings/addressDetails') !!}" class="nav-link {{ Request::is('settings/addressDetails*') ? 'selected' : '' }}">
                    <i class="fa fa-address-card"></i> {{trans('lang.app_store_address_plural')}}
                </a>
            </li>
            
             <li class="nav-item">
                <a href="{!! url('settings/birthdayCustomerDetails') !!}" class="nav-link {{ Request::is('settings/birthdayCustomerDetails*') ? 'selected' : '' }}">
                    <i class="fa fa-users"></i> Birthday Discount
                </a>
            </li>

        </ul>
    </div>
</div>

<div class="card settings {{ Request::is('settings/website*') ? '' : 'collapsed-card' }}">
    <div class="card-header">
        <h3 class="card-title">{{trans('lang.website_menu')}}</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa {{ Request::is('settings/website*') ? 'fa-minus' : 'fa-plus' }}"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <ul class="nav nav-pills flex-column">

            <li class="nav-item">
                <a href="{!! url('settings/website/globals') !!}" class="nav-link {{  Request::is('settings/website/globals*') ? 'selected' : '' }}">
                    <i class="fa fa-gear"></i> &nbsp; {{trans('lang.website_globals')}}
                </a>
            </li>

            <li class="nav-item">
                <a href="{!! url('settings/website/aboutus') !!}" class="nav-link {{  Request::is('settings/website/aboutus*') ? 'selected' : '' }}">
                    <i class="fa fa-info"></i> &nbsp;&nbsp; {{trans('lang.app_setting_aboutus')}}
                </a>
            </li>

            <li class="nav-item">
                <a href="{!! url('settings/website/specialoffers') !!}" class="nav-link {{  Request::is('settings/website/specialoffers*') ? 'selected' : '' }}">
                    <i class="fa fa-gift"></i> &nbsp;&nbsp; {{trans('lang.app_setting_specialoffers')}}
                </a>
            </li>

            <li class="nav-item">
                <a href="{!! url('settings/website/volunteer') !!}" class="nav-link {{  Request::is('settings/website/volunteer*') ? 'selected' : '' }}">
                    <i class="fa fa-handshake-o"></i> &nbsp;&nbsp; {{trans('lang.website_volunteer_with_us')}}
                </a>
            </li>

            <!-- <li class="nav-item">
                <a href="{!! url('settings/website/testimonials') !!}" class="nav-link {{  Request::is('settings/website/testimonials*') ? 'selected' : '' }}">
                    <i class="fa fa-quote-left"></i> &nbsp;&nbsp; {{trans('lang.app_setting_testimonials')}}
                </a>
            </li> -->


        </ul>
    </div>
</div>

<div class="card settings {{ Request::is('settings/mobile*') ? '' : 'collapsed-card' }}">
    <div class="card-header">
        <h3 class="card-title">{{trans('lang.mobile_menu')}}</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa {{ Request::is('settings/mobile*') ? 'fa-minus' : 'fa-plus' }}"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a href="{!! url('settings/mobile/globals') !!}" class="nav-link {{  Request::is('settings/mobile/globals*') ? 'selected' : '' }}">
                    <i class="fa fa-inbox"></i> {{trans('lang.mobile_globals')}}
                </a>
            </li>

            <li class="nav-item">
                <a href="{!! url('settings/mobile/colors') !!}" class="nav-link {{  Request::is('settings/mobile/colors*') ? 'selected' : '' }}">
                    <i class="fa fa-inbox"></i> {{trans('lang.mobile_colors')}}
                </a>
            </li>

            <li class="nav-item">
                <a href="{!! url('settings/mobile/home') !!}" class="nav-link {{  Request::is('settings/mobile/home*') ? 'selected' : '' }}">
                    <i class="fa fa-home"></i> {{trans('lang.mobile_home')}}
                </a>
            </li>

        </ul>
    </div>
</div>
