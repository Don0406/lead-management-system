<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
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

        // If the slug doesn't exist, show the 404 page
        if (!isset($services[$slug])) {
            abort(404);
        }

        $service = $services[$slug];

        return view('public.service-detail', compact('service'));
    }
}