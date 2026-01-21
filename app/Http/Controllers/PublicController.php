<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Display Capability/Service Details
     */
    public function showService($slug)
    {
        $services = [
            'lead-tracking' => [
                'n' => '01',
                'title' => 'Lead Tracking',
                'tagline' => 'Digital Surveillance of the Customer Journey.',
                'what_it_does' => 'Our tracking engine logs every interaction, from email opens to page views, providing a forensic look at your leads’ behavior. By distilling raw interaction data into a readable timeline, you can see exactly when a prospect is ready to move.',
                'what_it_offers' => [
                    'Real-time behavioral alerts',
                    'Multi-channel touchpoint mapping',
                    'Engagement heatmaps',
                    'Automated lead "temperature" scoring'
                ]
            ],
            'smart-assignment' => [
                'n' => '02',
                'title' => 'Smart Assignment',
                'tagline' => 'Algorithmic Distribution Logic.',
                'what_it_does' => 'It removes human bias and delay by instantly routing leads to the representative best equipped to handle them. The system balances current workloads, industry expertise, and geographic location to ensure the fastest response time possible.',
                'what_it_offers' => [
                    'Round-robin distribution',
                    'Expertise-based matching',
                    'SLA-driven routing',
                    'Mobile notification triggers'
                ]
            ],
            'sales-pipeline' => [
                'n' => '03',
                'title' => 'Sales Pipeline',
                'tagline' => 'Visualizing Revenue Momentum.',
                'what_it_does' => 'Transform your sales process into a high-fidelity roadmap. This module provides a bird’s-eye view of your revenue architecture, allowing you to identify bottlenecks in the funnel and forecast growth with surgical precision.',
                'what_it_offers' => [
                    'Drag-and-drop stage management',
                    'Funnel velocity reporting',
                    'Automated deal aging alerts',
                    'Historical conversion analysis'
                ]
            ],
            'follow-up-protocol' => [
                'n' => '04',
                'title' => 'Follow-Up Protocol',
                'tagline' => 'Consistency as a Competitive Advantage.',
                'what_it_does' => 'Never let a lead go cold. Our protocol engine schedules touchpoints and logs interactions automatically. It ensures that every prospect receives a consistent, high-value experience that builds trust over time.',
                'what_it_offers' => [
                    'Automated reminder cadences',
                    'Omnichannel logging (Email/Call)',
                    'Smart task prioritization',
                    'Interaction history snapshots'
                ]
            ],
            'secure-governance' => [
                'n' => '05',
                'title' => 'Secure Governance',
                'tagline' => 'Enterprise-Grade Integrity.',
                'what_it_does' => 'Protect your most valuable asset: your data. We implement role-based access controls (RBAC) and encryption layers that ensure sensitive prospect information is only accessible to authorized personnel.',
                'what_it_offers' => [
                    'Role-based access controls',
                    'Data encryption at rest',
                    'User activity audit logs',
                    'Compliance-ready architecture'
                ]
            ],
            'performance-intelligence' => [
                'n' => '06',
                'title' => 'Performance Intelligence',
                'tagline' => 'Turning Data into Actionable Strategy.',
                'what_it_does' => 'Move beyond basic reporting. Our intelligence engine identifies patterns in your successful conversions, helping you replicate top-tier performance across your entire sales organization.',
                'what_it_offers' => [
                    'Advanced productivity metrics',
                    'ROI attribution reporting',
                    'Predictive performance modeling',
                    'Customizable executive dashboards'
                ]
            ],
        ];

        if (!isset($services[$slug])) {
            abort(404);
        }

        $service = $services[$slug];
        return view('public.service-detail', compact('service'));
    }

    /*
    |--------------------------------------------------------------------------
    | Governance & Legal Methods
    |--------------------------------------------------------------------------
    */

    public function terms() 
    {
        return view('public.info', [
            'title' => 'Terms of Protocol',
            'content' => '
                <p class="mb-6">These Terms of Protocol outline the rules for using LeadBridge responsibly and securely.</p>
                <h3 class="text-[#5A4651] font-bold mt-8 mb-4 uppercase tracking-wider text-sm">Responsibility</h3>
                <p>By accessing the system, users agree to use LeadBridge only for legitimate lead management purposes. Users are expected to provide accurate information, protect their login credentials, and respect system security at all times.</p>
                <h3 class="text-[#5A4651] font-bold mt-8 mb-4 uppercase tracking-wider text-sm">Restrictions</h3>
                <p>Unauthorized access, misuse of data, or attempts to disrupt system operations are strictly prohibited. Any activity that violates these protocols may result in restricted access or account suspension to protect the integrity of the platform.</p>'
        ]);
    }

    public function privacy() 
    {
        return view('public.info', [
            'title' => 'Privacy Shield',
            'content' => '
                <p class="mb-6">Your privacy matters to us. The LeadBridge Privacy Shield is designed to protect personal and business information stored within the system.</p>
                <h3 class="text-[#5A4651] font-bold mt-8 mb-4 uppercase tracking-wider text-sm">Data Handling</h3>
                <p>We collect only the data necessary to manage leads effectively and use it solely for its intended purpose. All information is handled with confidentiality and protected through appropriate security measures.</p>
                <h3 class="text-[#5A4651] font-bold mt-8 mb-4 uppercase tracking-wider text-sm">Transparency</h3>
                <p>Users have the right to access, update, or correct their personal data. LeadBridge does not share personal information with third parties unless required for system functionality or legal compliance.</p>'
        ]);
    }

    public function cookies() 
    {
        return view('public.info', [
            'title' => 'Cookie Architecture',
            'content' => '
                <p class="mb-6">LeadBridge uses cookies to ensure the platform runs smoothly and securely.</p>
                <h3 class="text-[#5A4651] font-bold mt-8 mb-4 uppercase tracking-wider text-sm">Functionality</h3>
                <p>Essential cookies help manage user sessions, enable secure logins, and maintain system functionality. Functional cookies remember user preferences to improve overall experience. In some cases, analytical cookies may be used to understand system usage.</p>
                <h3 class="text-[#5A4651] font-bold mt-8 mb-4 uppercase tracking-wider text-sm">User Control</h3>
                <p>Cookies do not store sensitive information such as passwords. Users can manage or disable cookies through their browser settings, though some features may not work properly without essential cookies.</p>'
        ]);
    }

    public function compliance() 
    {
        return view('public.info', [
            'title' => 'Compliance Audit',
            'content' => '
                <p class="mb-6">LeadBridge follows regular compliance checks to maintain security, reliability, and regulatory alignment.</p>
                <h3 class="text-[#5A4651] font-bold mt-8 mb-4 uppercase tracking-wider text-sm">Audit Scope</h3>
                <p>These audits review how data is handled, stored, and accessed within the system. Security controls, user permissions, and privacy practices are evaluated to ensure they meet organizational and legal standards.</p>
                <h3 class="text-[#5A4651] font-bold mt-8 mb-4 uppercase tracking-wider text-sm">Evolution</h3>
                <p>Audit findings are used to improve system performance, strengthen security measures, and ensure continuous compliance as LeadBridge evolves.</p>'
        ]);
    }
}