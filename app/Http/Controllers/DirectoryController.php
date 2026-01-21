<?php

namespace App\Http\Controllers;

class DirectoryController extends Controller
{
    public function show($slug)
    {
        $pages = [
            'vision' => [
                'title' => 'Our Vision',
                'subtitle' => 'The Architecture of Tomorrow',
                'content' => 'LeadBridge was built on the principle that data should never be static. Our vision is to transform lead management from a storage task into a momentum engine...'
            ],
            'specialists' => [
                'title' => 'Consult Specialists',
                'subtitle' => 'Expert Intelligence',
                'content' => 'Our specialists provide the human intuition required to scale complex sales architectures. We connect you with industry veterans who understand growth.'
            ],
            'data-security' => [
                'title' => 'Data Governance',
                'subtitle' => 'Secure Intelligence Protocols',
                'content' => 'Security is our baseline. Every interaction within LeadBridge is encrypted under enterprise-grade governance protocols to ensure your data remains sovereign.'
            ],
            'terms' => [
                'title' => 'Terms of Access',
                'subtitle' => 'Engagement Framework',
                'content' => 'Access to the LeadBridge terminal is governed by strict professional standards and mutual growth agreements.'
            ],
        ];

        if (!isset($pages[$slug])) abort(404);

        $page = $pages[$slug];
        return view('directory.show', compact('page'));
    }
}
