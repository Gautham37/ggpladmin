<tr>
    <td>
        <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0">
            <tr>
                <td class="content-cell" align="center">
                        <div id="footer">
                            <span class="left">
                                <img src="{{ asset('images/email-images/brown_mail.png') }}" width="20" alt="{{ setting('app_name') }}" class="tiny">
                                <b> &nbsp; 
                                    {{setting('app_store_address_line_1')}}, 
                                    {{setting('app_store_address_line_2')}}, 
                                    {{setting('app_store_city')}}, 
                                    {{setting('app_store_state')}}, 
                                    {{setting('app_store_country')}} - {{setting('app_store_pincode')}}
                                </b>
                            </span>
                            <span>
                                <img src="{{ asset('images/email-images/brown_phone.png') }}" width="20" alt="{{ setting('app_name') }}" class="tiny">
                                <b> &nbsp; {{setting('app_store_phone_no')}}</b>
                            </span>
                            
                            <br>
                            
                            <div id="footer_next">
                                <span class="left">
                                    <img src="{{ asset('images/email-images/brown_mail.png') }}" width="20" alt="{{ setting('app_name') }}" class="tiny">
                                    <b>&nbsp; <span style="color:#9a6e3a!important;">{{setting('mail_from_address')}}</span></b>
                                </span>
                                <span>
                                    <img src="{{ asset('images/email-images/brown_globe.jpg') }}" width="20" alt="{{ setting('app_name') }}" class="tiny">
                                    <b> &nbsp; <span style="color:#9a6e3a!important;">{{ env('APP_SITE_URL') }}</span></b>
                                </span>
                            </div>
                        </div>
                    <br>
                    {{ Illuminate\Mail\Markdown::parse($slot) }}
                </td>
            </tr>
        </table>
    </td>
</tr>
