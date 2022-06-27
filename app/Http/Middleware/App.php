<?php namespace App\Http\Middleware;

use App\Repositories\UploadRepository;
use Carbon\Carbon;
use Closure;

class App
{

    /**
     * The availables languages.
     *
     * @array $languages
     */
    protected $languages = ['en', 'fr']; // en, fr

    protected $uploadRepository;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            app()->setLocale(setting('language', app()->getLocale()));
        } else {
            app()->setLocale($request->getPreferredLanguage($this->languages));
        }
        try {
            Carbon::setLocale(app()->getLocale());
            // config(['app.timezone' => setting('timezone')]);

            $this->uploadRepository = new UploadRepository(app());
            $upload = $this->uploadRepository->findByField('uuid', setting('app_logo', ''))->first();
            $appLogo = asset('images/logo_default.png');
            if ($upload && $upload->hasMedia('app_logo')) {
                $appLogo = $upload->getFirstMediaUrl('app_logo');
            }
            view()->share('app_logo', $appLogo);

            $upload = $this->uploadRepository->findByField('uuid', setting('app_invoice_signature', ''))->first();
            $appSignature = asset('images/logo_default.png');
            if ($upload && $upload->hasMedia('app_invoice_signature')) {
                $appSignature = $upload->getFirstMediaUrl('app_invoice_signature');
            }
            view()->share('app_invoice_signature', $appSignature);

            $upload = $this->uploadRepository->findByField('uuid', setting('app_upi_code', ''))->first();
            $appUpicode = asset('images/logo_default.png');
            if ($upload && $upload->hasMedia('app_upi_code')) {
                $appUpicode = $upload->getFirstMediaUrl('app_upi_code');
            }
            view()->share('app_upi_code', $appUpicode); 


            $upload = $this->uploadRepository->findByField('uuid', setting('app_email_header_image', ''))->first();
            $app_email_header_image = asset('images/email-images/mail-bg.png');
            if ($upload && $upload->hasMedia('app_email_header_image')) {
                $app_email_header_image = $upload->getFirstMediaUrl('app_email_header_image');
            }
            view()->share('app_email_header_image', $app_email_header_image); 


        } catch (\Exception $exception) { }

        return $next($request);
    }

}