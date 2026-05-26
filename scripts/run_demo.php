<?php

declare(strict_types=1);

require __DIR__ . '/../src/Services/RegulatoryDisclosureKitService.php';

use WordpressRegulatoryDisclosureKit\Services\RegulatoryDisclosureKitService;

$service = new RegulatoryDisclosureKitService();
$lanes = $service->disclosureLanes();
$blocked = array_values(array_filter($lanes, static fn(array $lane): bool => $lane['status'] === 'blocked'));

echo json_encode(
    [
        'dashboard' => $service->summary(),
        'blockedSurfaces' => array_map(
            static fn(array $lane): array => [
                'surface' => $lane['surface'],
                'package' => $lane['package'],
                'owner' => $lane['owner'],
            ],
            $blocked
        ),
        'watchEvidence' => array_values(
            array_filter(
                $service->policyEvidence(),
                static fn(array $artifact): bool => $artifact['status'] !== 'healthy'
            )
        ),
    ],
    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
) . PHP_EOL;
