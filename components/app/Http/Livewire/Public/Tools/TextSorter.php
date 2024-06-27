<?php

namespace App\Http\Livewire\Public\Tools;

use Livewire\Component;
use App\Models\Admin\History;
use App\Classes\TextSorterClass;
use DateTime;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use App\Rules\VerifyRecaptcha;
use App\Models\Admin\General;

class TextSorter extends Component
{
    public $text;
    public $sorting_options = 'az';
    public $remove_duplicates;
    public $data = [];
    public $recaptcha;
    
    public function render()
    {
        return view('livewire.public.tools.text-sorter');
    }

    /**
     * -------------------------------------------------------------------------------
     *  onTextSorter
     * -------------------------------------------------------------------------------
    **/
    public function onTextSorter(){

        $validationRules = [
            'text' => 'required',
        ];

        if (General::first()->captcha_status) {
            $validationRules['recaptcha'] = ['required', new VerifyRecaptcha];
        }

        $this->validate($validationRules);

        $this->data = null;

        try {

            $output = new TextSorterClass();

            $this->data = $output->get_data( $this->text, $this->sorting_options, $this->remove_duplicates );

            $this->dispatchBrowserEvent('resetReCaptcha');

        } catch (\Exception $e) {

            $this->addError('error', __($e->getMessage()));
        }

        //Save History
        if ( !empty($this->data) ) {

            $history             = new History;
            $history->tool_name  = 'Text Sorter';
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

    /**
     * -------------------------------------------------------------------------------
     *  onSample
     * -------------------------------------------------------------------------------
    **/
    public function onSample()
    {
    $this->text = <<<EOT
Tomato
Nutmeg
Zucchini
Mango
Lettuce
Blueberry
Horseradish
Grape
Apple
Eggplant
Pineapple
Kale
Strawberry
apple
Radish
Cherry
Orange
Dewberry
Watermelon
EOT;
    }

    /**
     * -------------------------------------------------------------------------------
     *  onReset
     * -------------------------------------------------------------------------------
    **/
    public function onReset()
    {
        $this->text            = null;
        $this->data            = null;
        $this->sorting_options = 'az';
    }
}
