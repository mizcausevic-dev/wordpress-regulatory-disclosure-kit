<?php

declare(strict_types=1);

use WordpressRegulatoryDisclosureKit\Services\RegulatoryDisclosureKitService;

require __DIR__ . '/../src/Services/RegulatoryDisclosureKitService.php';
require __DIR__ . '/../src/Views/render.php';

$service = new RegulatoryDisclosureKitService();
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

if (str_starts_with($path, '/api/')) {
    header('Content-Type: application/json; charset=utf-8');

    $payload = match ($path) {
        '/api/dashboard/summary' => $service->summary(),
        '/api/disclosure-lane' => $service->disclosureLanes(),
        '/api/policy-evidence' => $service->policyEvidence(),
        '/api/verification' => $service->verificationGates(),
        '/api/sample' => $service->payload(),
        default => ['error' => 'Not found'],
    };

    if ($payload === ['error' => 'Not found']) {
        http_response_code(404);
    }

    echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    return;
}

$html = match ($path) {
    '/' => WordpressRegulatoryDisclosureKit\Views\render_overview(),
    '/disclosure-lane' => WordpressRegulatoryDisclosureKit\Views\render_disclosure_lane(),
    '/policy-evidence' => WordpressRegulatoryDisclosureKit\Views\render_policy_evidence(),
    '/verification' => WordpressRegulatoryDisclosureKit\Views\render_verification(),
    '/docs' => WordpressRegulatoryDisclosureKit\Views\render_docs(),
    default => null,
};

if ($html === null) {
    http_response_code(404);
    echo 'Not found';
    return;
}

header('Content-Type: text/html; charset=utf-8');
echo $html;
