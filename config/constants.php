<?php
/**
 * AMVAC 2027 — Application Constants & Canonical Copy
 * Governed strictly by 01_PROJECT_SOURCE_OF_TRUTH.md
 */

declare(strict_types=1);

// Canonical Event Information
define('AMVAC_NAME', 'Africa Motor Vehicle Administration Conference 2027');
define('AMVAC_ACRONYM', 'AMVAC 2027');
define('AMVAC_WORKING_NAME', 'Africa Motor Vehicle Administration Conference');
define('AMVAC_FLYER_TITLE', '1st International Conference on Motor Vehicle Administration');
define('AMVAC_THEME', 'Enhancing Continental Security, Road Safety and Economic Integration Through Effective Motor Vehicle Administration in Africa.');
define('AMVAC_SLOGAN', 'Please be road safety conscious to save lives.');

// Dates & Venue (Provisional Baseline - D-02)
define('AMVAC_DATES', '15–19 March 2027');
define('AMVAC_LOCATION', 'Banjul, The Gambia');
define('AMVAC_HYBRID_FORMAT', 'Hybrid – Abuja & Banjul');
define('AMVAC_VENUE_PROVISIONAL', 'BICC Conference Centre, Banjul');
define('AMVAC_VENUE_NOTE', 'Provisional venue subject to official host ratification.');

// Organizing Entities
define('AMVAC_ORGANIZERS', [
    [
        'name' => 'Accident Prevention and Rescue Initiative (APRI)',
        'country' => 'Nigeria',
        'role' => 'Co-Organizer & Technical Secretariat',
        'status' => 'APPROVED'
    ],
    [
        'name' => 'Road Safety Driver Standard Association of The Gambia',
        'country' => 'The Gambia',
        'role' => 'Host Country Co-Organizer',
        'status' => 'APPROVED'
    ],
    [
        'name' => 'Network of Africa Road Safety NGOs',
        'country' => 'Pan-African',
        'role' => 'Collaborating Network',
        'status' => 'PENDING CLIENT APPROVAL' // D-03
    ]
]);

// Leadership Profiles
define('AMVAC_LEADERS', [
    'fidelis_nnadi' => [
        'name' => 'Amb. Prince Fidelis Nnadi',
        'title' => 'Executive Director, APRI - Nigeria',
        'role' => 'Conference Host & Consultant on Motor Vehicle Administration',
        'image' => '/assets/images/fidelis-nnadi.jpg',
        'status' => 'APPROVED'
    ],
    'njie_baldeh' => [
        'name' => 'Njie Baldeh',
        'title' => 'Executive Secretary',
        'role' => 'Road Safety Driver Standard Association of The Gambia',
        'image' => '/assets/images/njie-baldeh.jpg',
        'status' => 'APPROVED'
    ],
    'adama_barrow' => [
        'name' => 'His Excellency Adama Barrow',
        'title' => 'President of The Republic of The Gambia',
        'role' => 'Host Head of State (Protocol Verification In Progress)',
        'image' => '/assets/images/adama-barrow.jpg',
        'status' => 'PENDING CLIENT APPROVAL' // D-04
    ]
]);

// All Eight Canonical Objectives (LOCKED - ZERO OMISSIONS)
define('AMVAC_OBJECTIVES', [
    1 => [
        'number' => 1,
        'title' => 'Continental Harmonization',
        'summary' => 'Establish a unified legal and regulatory framework for vehicle inspection, registration, licensing and number plate administration across Africa.',
        'full_text' => 'To establish a unified legal and regulatory framework for vehicle inspection, registration, licensing and number plate administration across Africa.',
        'icon' => 'harmonization'
    ],
    2 => [
        'number' => 2,
        'title' => 'Security Integration',
        'summary' => 'Integrate motor vehicle data into Africa’s security system to combat cross-border vehicle theft, smuggling, kidnapping and terrorism.',
        'full_text' => 'To integrate motor vehicle data into Africa’s security system to combat cross-border vehicle theft, smuggling, kidnapping and terrorism.',
        'icon' => 'security'
    ],
    3 => [
        'number' => 3,
        'title' => 'Digital Transformation',
        'summary' => 'Promote automation, centralized digital databases and biometric systems for transparency and traceability continent-wide.',
        'full_text' => 'To promote automation, centralized digital databases and biometric systems for transparency and traceability continent-wide.',
        'icon' => 'digital'
    ],
    4 => [
        'number' => 4,
        'title' => 'Capacity Building',
        'summary' => 'Train and retrain Motor Vehicle Administrators, VIOs and Road Safety Officers in modern techniques and global best practices.',
        'full_text' => 'To train and retrain Motor Vehicle Administrators, VIOs and Road Safety Officers in modern techniques and global best practices.',
        'icon' => 'capacity'
    ],
    5 => [
        'number' => 5,
        'title' => 'Safer African Roads',
        'summary' => 'Rid African roads of unroadworthy vehicles and work towards drastically reducing annual road deaths across Africa.',
        'full_text' => 'To rid African roads of unroadworthy vehicles and reduce the over 300,000 annual road deaths in Africa.',
        'citation_note' => 'Mortality statistic pending client citation review (D-10).',
        'icon' => 'safety'
    ],
    6 => [
        'number' => 6,
        'title' => 'Public Enlightenment',
        'summary' => 'Reorient public perception of Motor Vehicle Administration as a life-saving service, not punishment.',
        'full_text' => 'To reorient public perception of Motor Vehicle Administration as a life-saving service, not punishment.',
        'icon' => 'enlightenment'
    ],
    7 => [
        'number' => 7,
        'title' => 'Inter-Agency & Cross-Border Collaboration',
        'summary' => 'Foster partnership among transport agencies, Police, Customs, FRSC, Immigration and Interpol across AU states.',
        'full_text' => 'To foster partnership among transport agencies, Police, Customs, FRSC, Immigration and Interpol across AU states.',
        'icon' => 'collaboration'
    ],
    8 => [
        'number' => 8,
        'title' => 'Economic Development',
        'summary' => 'Unlock revenue, create jobs, and promote establishment of number plate, driver license and vehicle assembly plants across Africa.',
        'full_text' => 'To unlock revenue, create jobs, and promote establishment of number plate, driver license and vehicle assembly plants across Africa.',
        'icon' => 'economy'
    ]
]);

// Strict Server-Side Fee Rules (D-05)
define('AMVAC_FEE_MATRIX', [
    'nigerian_delegate' => [
        'id' => 'nigerian_delegate',
        'name' => 'Nigerian Resident Delegate',
        'amount' => 100000.00,
        'currency' => 'NGN',
        'symbol' => '₦',
        'description' => 'For delegates resident in Nigeria or affiliated with Nigerian institutions.'
    ],
    'international_delegate' => [
        'id' => 'international_delegate',
        'name' => 'International & Regional Delegate',
        'amount' => 50.00,
        'currency' => 'USD',
        'symbol' => '$',
        'description' => 'For delegates from The Gambia, ECOWAS member states, and international institutions.'
    ]
]);

// Bank Account (Provisional - D-06)
// Staging only; hide sensitive payment details on public production pages until beneficiary authorization.
define('AMVAC_BANK_DETAILS', [
    'bank_name' => 'Bloom Bank Africa',
    'bank_country' => 'The Gambia',
    'account_number' => '1031770538978',
    'bban' => '013202177053897837',
    'status' => 'PENDING CLIENT APPROVAL' // Beneficiary authorization required
]);
