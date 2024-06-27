<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Artesaos\SEOTools\Facades\SEOMeta;

class Sitemap extends Component
{
    public function render()
    {

        //Meta
        $title = __('Sitemap') . ' ' . env('APP_SEPARATOR') . ' ' . env('APP_NAME');
        SEOMeta::setTitle($title);

        return view('livewire.admin.sitemap')->layout('layouts.admin', [
            'breadcrumbs' => [
                ['title' => __( 'Admin' ), 'url' => route('admin.dashboard.index')],
                ['title' => __( 'Sitemap' ), 'url' => null]
            ]
        ]);
    }
}
