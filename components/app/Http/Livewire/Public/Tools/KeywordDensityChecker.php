<?php

namespace App\Http\Livewire\Public\Tools;

use Livewire\Component;
use App\Models\Admin\History;
use Illuminate\Support\Facades\Http;
use App\Classes\KeywordDensityCheckerClass;
use DateTime;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use App\Rules\VerifyRecaptcha;
use App\Models\Admin\General;
use App\Rules\DomainNameValidation;

class KeywordDensityChecker extends Component
{

    public $link;
    public $data = [];
    public $recaptcha;

    public function render()
    {
        return view('livewire.public.tools.keyword-density-checker');
    }

    /**
     * -------------------------------------------------------------------------------
     *  onKeywordDensityChecker
     * -------------------------------------------------------------------------------
    **/
    public function onKeywordDensityChecker(){

        $validationRules = [
            'link' => [
                'required',
                'max:255',
                new DomainNameValidation()
            ]
        ];

        if (General::first()->captcha_status) {
            $validationRules['recaptcha'] = ['required', new VerifyRecaptcha];
        }

        $this->validate($validationRules);

        $this->data = null;

        try {

                $output = new KeywordDensityCheckerClass();

                $output->domain =  $this->link;

                $resdata = $output->get_data(); 

                if ( $resdata ) {

                    $this->data['total'] = count($resdata);

                    $i = 0;

                    foreach ($resdata as $value) {

                        if( !empty( $value['keyword'] ) ){
                            $this->data['keywords'][$i]['keyword'] = $value['keyword'];
                            $this->data['keywords'][$i]['count']   = $value['count'];
                            $this->data['keywords'][$i]['percent'] = $value['percent'];
                            $i++;
                        }
                    }

                } else $this->addError('error', __('Invalid request!'));

                $this->dispatchBrowserEvent('resetReCaptcha');

        } catch (\Exception $e) {

            $this->addError('error', __($e->getMessage()));
        }

        //Save History
        if ( !empty($this->data) ) {

            $history             = new History;
            $history->tool_name  = 'Keyword Density Checker';
            $history->client_ip  = request()->ip();

            require app_path('Classes/geoip2.phar');

            $reader = new Reader( app_path('Classes/GeoLite2-City.mmdb') );

            try {

                $record           = $reader->city( request()->ip() );

                $history->flag    = strtolower( $record->country->isoCode );
                
                $history->country = strip_tags( $record->country->name );

            } catch (AddressNotFoundException $e) {

            }

            $history->created_at = new DateTime();
            $history->save();
        }
    }
    //
}
