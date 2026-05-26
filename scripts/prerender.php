<?php

declare(strict_types=1);

require __DIR__ . '/../src/Services/RegulatoryDisclosureKitService.php';
require __DIR__ . '/../src/Views/render.php';

$root = dirname(__DIR__);
$site = $root . '/site';

if (is_dir($site)) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($site, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($iterator as $item) {
        if ($item->isDir()) {
            rmdir($item->getPathname());
        } else {
            unlink($item->getPathname());
        }
    }
}

if (! is_dir($site) && ! mkdir($site, 0777, true) && ! is_dir($site)) {
    throw new RuntimeException('Failed to create site directory.');
}

$apiDir = $site . '/api/dashboard/summary';
if (! is_dir($apiDir) && ! mkdir($apiDir, 0777, true) && ! is_dir($apiDir)) {
    throw new RuntimeException('Failed to create API directory.');
}

$service = new WordpressRegulatoryDisclosureKit\Services\RegulatoryDisclosureKitService();

$pages = [
    'index.html' => WordpressRegulatoryDisclosureKit\Views\render_overview(),
    'disclosure-lane/index.html' => WordpressRegulatoryDisclosureKit\Views\render_disclosure_lane(),
    'policy-evidence/index.html' => WordpressRegulatoryDisclosureKit\Views\render_policy_evidence(),
    'verification/index.html' => WordpressRegulatoryDisclosureKit\Views\render_verification(),
    'docs/index.html' => WordpressRegulatoryDisclosureKit\Views\render_docs(),
];

foreach ($pages as $file => $html) {
    $target = $site . '/' . $file;
    $dir = dirname($target);
    if (! is_dir($dir) && ! mkdir($dir, 0777, true) && ! is_dir($dir)) {
        throw new RuntimeException('Failed to create page directory: ' . $dir);
    }
    file_put_contents($target, $html);
}

$payloads = [
    $site . '/api/dashboard/summary/index.json' => $service->summary(),
    $site . '/api/disclosure-lane.json' => $service->disclosureLanes(),
    $site . '/api/policy-evidence.json' => $service->policyEvidence(),
    $site . '/api/verification.json' => $service->verificationGates(),
    $site . '/api/sample.json' => $service->payload(),
];

foreach ($payloads as $file => $payload) {
    $dir = dirname($file);
    if (! is_dir($dir) && ! mkdir($dir, 0777, true) && ! is_dir($dir)) {
        throw new RuntimeException('Failed to create payload directory: ' . $dir);
    }

    file_put_contents($file, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

file_put_contents($site . '/CNAME', trim((string) file_get_contents($root . '/CNAME')));
