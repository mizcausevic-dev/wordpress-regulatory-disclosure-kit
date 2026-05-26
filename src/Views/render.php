<?php

declare(strict_types=1);

namespace WordpressRegulatoryDisclosureKit\Views;

use WordpressRegulatoryDisclosureKit\Services\RegulatoryDisclosureKitService;

function status_class(string $status): string
{
    return match ($status) {
        'blocked', 'critical' => 'critical',
        'watch' => 'watch',
        default => 'good',
    };
}

function shell(string $active, string $title, string $eyebrow, string $hero, string $intro, string $body, array $rightCards): string
{
    $service = new RegulatoryDisclosureKitService();
    $summary = $service->summary();
    $operatorPosture = htmlspecialchars((string) $summary['operatorPosture'], ENT_QUOTES);
    $leadRecommendation = htmlspecialchars((string) $summary['leadRecommendation'], ENT_QUOTES);
    $rightCardsHtml = render_side_cards($rightCards);
    $nav = render_nav($active);

    $safeTitle = htmlspecialchars($title, ENT_QUOTES);
    $safeEyebrow = htmlspecialchars($eyebrow, ENT_QUOTES);
    $safeHero = htmlspecialchars($hero, ENT_QUOTES);
    $safeIntro = htmlspecialchars($intro, ENT_QUOTES);

    return <<<HTML
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{$safeTitle}</title>
  <style>
    :root{
      --bg:#070a0f; --panel:#0b1220; --panel2:#0a1426;
      --line:rgba(120,255,170,.18); --line2:rgba(120,255,170,.10);
      --text:#e9f3ff; --muted:rgba(233,243,255,.72); --muted2:rgba(233,243,255,.55);
      --bert:#37ff8b; --bert2:#19c7ff;
      --warn:#ffcc66; --bad:#ff5c7a; --good:#37ff8b; --plum:#b88cff;
      --shadow: 0 18px 60px rgba(0,0,0,.55);
      --radius: 18px;
      --mono: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
      --sans: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
    }
    *{box-sizing:border-box} html,body{height:100%}
    body{
      margin:0; font-family:var(--sans); color:var(--text);
      background:
        radial-gradient(1200px 600px at 20% -10%, rgba(55,255,139,.18), transparent 60%),
        radial-gradient(900px 520px at 90% 0%, rgba(25,199,255,.16), transparent 55%),
        radial-gradient(1000px 600px at 50% 110%, rgba(55,255,139,.10), transparent 60%),
        linear-gradient(180deg, #05070c 0%, #070a0f 35%, #05070c 100%);
    }
    .grid-bg{
      position:fixed; inset:0; pointer-events:none; opacity:.12; z-index:-1;
      background-image:
        linear-gradient(to right, rgba(55,255,139,.14) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(55,255,139,.10) 1px, transparent 1px);
      background-size: 46px 46px;
      mask-image: radial-gradient(900px 600px at 40% 10%, #000 60%, transparent 100%);
    }
    .wrap{max-width:1280px; margin:0 auto; padding:24px 22px 80px}
    .topbar{
      display:flex; justify-content:space-between; align-items:flex-start; gap:14px;
      border-bottom:1px solid var(--line2); padding-bottom:14px; margin-bottom:22px;
      font-family:var(--mono); font-size:11px; letter-spacing:.16em; color:var(--muted);
      text-transform:uppercase;
    }
    .topbar .left{color:var(--bert)}
    .topbar .right{text-align:right; color:var(--muted)}
    .topbar .right div{margin-bottom:4px}
    .herorow{display:grid; grid-template-columns: 1.5fr .9fr; gap:18px}
    @media (max-width:1000px){.herorow{grid-template-columns:1fr}}
    .hero{
      background: linear-gradient(180deg, rgba(11,18,32,.95), rgba(8,14,26,.92));
      border:1px solid var(--line); border-radius:22px; padding:28px 28px 24px;
      box-shadow: var(--shadow); position:relative; overflow:hidden;
      border-top:2px solid var(--bert2);
    }
    .hero h1{ font-size:64px; line-height:.95; margin:0 0 18px; letter-spacing:-.5px; font-weight:800; }
    @media (max-width:700px){.hero h1{font-size:42px}}
    .hero p{color:var(--muted); font-size:15px; line-height:1.55; max-width:680px; margin:0 0 18px}
    .chiprow{display:flex; flex-wrap:wrap; gap:8px}
    .meta-chip{
      font-family:var(--mono); font-size:11px; color:var(--muted);
      padding:7px 12px; border-radius:999px; border:1px solid var(--line);
      background:rgba(6,10,18,.4);
    }
    .side{display:flex; flex-direction:column; gap:14px}
    .bluf{
      border:1px solid var(--warn); border-left:4px solid var(--warn);
      background: linear-gradient(180deg, rgba(255,204,102,.06), rgba(11,18,32,.92));
      border-radius:14px; padding:16px 18px;
    }
    .bluf .lbl, .corr .lbl{font-family:var(--mono); font-size:10px; letter-spacing:.18em; text-transform:uppercase}
    .bluf .lbl{color:var(--warn)} .corr .lbl{color:var(--bert)}
    .bluf p, .corr p{color:var(--muted); font-size:13.5px; line-height:1.55; margin:6px 0 0}
    .corr{
      border:1px solid var(--bert); border-left:4px solid var(--bert);
      background: linear-gradient(180deg, rgba(55,255,139,.06), rgba(11,18,32,.92));
      border-radius:14px; padding:16px 18px;
    }
    .toolchips{display:flex; flex-wrap:wrap; gap:8px}
    .toolchip{
      font-family:var(--mono); font-size:11px; padding:6px 12px; border-radius:999px;
      border:1px solid currentColor; background:transparent;
    }
    .tc-claude{color:var(--bert2)} .tc-codex{color:var(--warn)} .tc-gpt{color:var(--bert)} .tc-perplex{color:var(--plum)}
    .section{margin-top:34px}
    .sh{
      display:flex; justify-content:space-between; align-items:baseline; gap:14px;
      padding-bottom:10px; border-bottom:1px solid var(--line2); margin-bottom:14px;
    }
    .sh h2{margin:0; font-size:24px; font-weight:600; letter-spacing:-.2px}
    .sh .note{font-family:var(--mono); font-size:11px; color:var(--muted2); letter-spacing:.16em; text-transform:uppercase}
    .kpis{display:grid; grid-template-columns: repeat(6, 1fr); gap:12px}
    @media (max-width:1100px){.kpis{grid-template-columns: repeat(3, 1fr)}} @media (max-width:640px){.kpis{grid-template-columns: repeat(2, 1fr)}}
    .kpi{
      border:1px solid var(--line); border-radius:14px; padding:14px 14px 12px;
      background: linear-gradient(180deg, rgba(11,18,32,.85), rgba(8,14,26,.65));
    }
    .kpi .v{font-family:var(--mono); font-size:26px; font-weight:600; letter-spacing:-.5px}
    .kpi.amber .v{color:var(--warn)} .kpi.cyan .v{color:var(--bert2)} .kpi.green .v{color:var(--bert)} .kpi.plum .v{color:var(--plum)} .kpi.red .v{color:var(--bad)} .kpi.white .v{color:var(--text)}
    .kpi .lbl{font-family:var(--mono); font-size:10px; letter-spacing:.18em; text-transform:uppercase; color:var(--muted); margin-top:6px}
    .kpi .h{font-size:12px; color:var(--muted); line-height:1.45; margin-top:8px}
    .board{display:grid; grid-template-columns: repeat(2,1fr); gap:14px}
    @media (max-width:1000px){.board{grid-template-columns:1fr}}
    .pcard{
      border:1px solid var(--line); border-radius:16px; padding:18px 20px;
      background: linear-gradient(180deg, rgba(11,18,32,.85), rgba(8,14,26,.65));
    }
    .pcard .ptop{display:flex; justify-content:space-between; align-items:center; margin-bottom:8px}
    .pcard .pnum{font-family:var(--mono); font-size:22px; font-weight:600; color:var(--bert)}
    .pcard .ppri{font-family:var(--mono); font-size:10px; padding:5px 10px; border-radius:999px; border:1px solid var(--line); color:var(--bert); letter-spacing:.14em; background:rgba(55,255,139,.06)}
    .pcard h3{margin:6px 0 8px; font-size:19px; font-weight:600}
    .pcard .pdesc{font-size:13.5px; color:var(--muted); line-height:1.55; margin:0 0 12px}
    .pcard ul.check{list-style:none; padding:0; margin:0}
    .pcard ul.check li{display:grid; grid-template-columns:18px 1fr; gap:10px; padding:6px 0; font-size:13.5px; color:var(--muted); line-height:1.45}
    .pcard ul.check li:before{content:""; width:14px; height:14px; border:1px solid var(--line); border-radius:3px; background:rgba(6,10,18,.4); margin-top:3px}
    .ttbl{
      width:100%; border-collapse:separate; border-spacing:0;
      border:1px solid var(--line); border-radius:14px; overflow:hidden;
    }
    .ttbl th, .ttbl td{padding:13px 14px; text-align:left; font-size:13.5px; vertical-align:top}
    .ttbl thead th{
      font-family:var(--mono); font-size:11px; letter-spacing:.16em; text-transform:uppercase;
      color:var(--muted2); border-bottom:1px solid var(--line); background:rgba(11,18,32,.5);
    }
    .ttbl tbody tr:hover{background:rgba(55,255,139,.03)}
    .ttbl td, .ttbl td *{color:var(--muted)}
    .ttbl b{color:var(--text)}
    .st{font-family:var(--mono); font-size:10px; padding:4px 9px; border-radius:6px; letter-spacing:.1em; text-transform:uppercase; border:1px solid currentColor; display:inline-block}
    .st.good{color:var(--bert)} .st.watch{color:var(--warn)} .st.critical{color:var(--bad)}
    footer{
      margin-top:30px; padding-top:14px; border-top:1px dashed var(--line2);
      display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap;
      font-family:var(--mono); font-size:11px; color:var(--muted2); letter-spacing:.08em;
    }
    a{color:var(--bert2); text-decoration:none}
    a:hover{text-decoration:underline}
  </style>
</head>
<body>
  <div class="grid-bg"></div>
  <div class="wrap">
    <div class="topbar">
      <div class="left">KINETIC GAIN · wordpress disclosure lane</div>
      <div class="right">
        <div>WordPress plugin + disclosure governance</div>
        <div>Regulated claims · schema qualifiers · evidence packs</div>
      </div>
    </div>
    <div class="herorow">
      <section class="hero">
        <div class="chiprow">
          <span class="meta-chip">{$safeEyebrow}</span>
          <span class="meta-chip">CNAME · disclosure.kineticgain.com</span>
          <span class="meta-chip">PHP + static Pages bundle</span>
        </div>
        <h1>{$safeHero}</h1>
        <p>{$safeIntro}</p>
        <div class="bluf" style="margin:18px 0 18px;">
          <div class="lbl">Lead recommendation</div>
          <p><strong>Reviewer-first posture</strong><br>{$leadRecommendation}</p>
        </div>
        <div class="chiprow">
          {$nav}
        </div>
      </section>
      <aside class="side">{$rightCardsHtml}</aside>
    </div>
    <section class="section">
      <div class="sh"><h2>Operator summary</h2><div class="note">claims + qualifiers + release trust</div></div>
      <div class="kpis">
        <div class="kpi green"><div class="v">{$summary['surfaceCount']}</div><div class="lbl">Tracked surfaces</div><div class="h">Public pages, docs, collateral, and support disclosures in one lane.</div></div>
        <div class="kpi cyan"><div class="v">{$summary['healthyCount']}</div><div class="lbl">Healthy surfaces</div><div class="h">Already aligned to reviewed disclosure and canonical URLs.</div></div>
        <div class="kpi amber"><div class="v">{$summary['watchCount']}</div><div class="lbl">Watch surfaces</div><div class="h">Need qualifier or URL fixes before the next commercial push.</div></div>
        <div class="kpi red"><div class="v">{$summary['blockedCount']}</div><div class="lbl">Blocked releases</div><div class="h">Unsafe to launch until the approval packet and manifest are back in sync.</div></div>
        <div class="kpi plum"><div class="v">{$summary['evidenceCount']}</div><div class="lbl">Evidence packs</div><div class="h">Reviewed artifacts tied to claims, schema, and field collateral.</div></div>
        <div class="kpi white"><div class="v mono">{$operatorPosture}</div><div class="lbl">Operator posture</div><div class="h">Disclosure governance treated as a shippable system, not a footer afterthought.</div></div>
      </div>
    </section>
    {$body}
    <footer>
      <div>wordpress-regulatory-disclosure-kit · AGPL-3.0-or-later · synthetic demonstration data only</div>
      <div>Routes: / · /disclosure-lane · /policy-evidence · /verification · /docs</div>
    </footer>
  </div>
</body>
</html>
HTML;
}

function active_class(string $active, string $href): string
{
    return $active === $href ? 'tc-gpt' : 'tc-claude';
}

function render_nav(string $active): string
{
    $items = [
        '/' => 'Overview',
        '/disclosure-lane' => 'Disclosure Lane',
        '/policy-evidence' => 'Policy Evidence',
        '/verification' => 'Verification',
        '/docs' => 'Docs',
    ];

    $html = '';
    foreach ($items as $href => $label) {
        $class = active_class($active, $href);
        $safeHref = htmlspecialchars($href, ENT_QUOTES);
        $safeLabel = htmlspecialchars($label, ENT_QUOTES);
        $html .= "<a class=\"toolchip {$class}\" href=\"{$safeHref}\">{$safeLabel}</a>";
    }

    return $html;
}

function render_side_cards(array $cards): string
{
    $html = '';
    foreach ($cards as $index => $card) {
        $class = $index === 0 ? 'bluf' : 'corr';
        $label = htmlspecialchars($card['label'], ENT_QUOTES);
        $title = htmlspecialchars($card['title'], ENT_QUOTES);
        $body = htmlspecialchars($card['body'], ENT_QUOTES);
        $html .= <<<HTML
<article class="{$class}">
  <div class="lbl">{$label}</div>
  <p><strong>{$title}</strong><br>{$body}</p>
</article>
HTML;
    }

    return $html;
}

function render_overview(): string
{
    $service = new RegulatoryDisclosureKitService();
    $lanes = array_slice($service->disclosureLanes(), 0, 4);

    $cards = '';
    foreach ($lanes as $index => $lane) {
        $indexPlus = $index + 1;
        $surface = htmlspecialchars((string) $lane['surface'], ENT_QUOTES);
        $channel = htmlspecialchars((string) $lane['channel'], ENT_QUOTES);
        $owner = htmlspecialchars((string) $lane['owner'], ENT_QUOTES);
        $proof = htmlspecialchars((string) $lane['proof'], ENT_QUOTES);
        $nextAction = htmlspecialchars((string) $lane['nextAction'], ENT_QUOTES);
        $status = htmlspecialchars((string) $lane['status'], ENT_QUOTES);
        $statusClass = status_class((string) $lane['status']);
        $cards .= <<<HTML
<article class="pcard">
  <div class="ptop"><div class="pnum">D-0{$indexPlus}</div><div class="ppri">{$status}</div></div>
  <h3>{$surface}</h3>
  <p class="pdesc">{$channel} · owner: {$owner}</p>
  <ul class="check">
    <li>{$proof}</li>
    <li><strong>Next action:</strong> {$nextAction}</li>
    <li><strong>Status:</strong> <span class="st {$statusClass}">{$status}</span></li>
  </ul>
</article>
HTML;
    }

    $body = <<<HTML
<section class="section">
  <div class="sh"><h2>Overview</h2><div class="note">where public disclosure drifts first</div></div>
  <div class="board">{$cards}</div>
</section>
HTML;

    return shell(
        '/',
        'WordPress Regulatory Disclosure Kit',
        'wordpress regulatory disclosure kit',
        'Keep WordPress claims, schema qualifiers, and approval evidence in the same release lane.',
        'This disclosure surface makes WordPress governance explicit: which claims are safe, which evidence packs are stale, and where legal, SEO, content, or RevOps still need to repair the publication path before buyers or regulators see the mismatch.',
        $body,
        [
            ['label' => 'Core offer', 'title' => 'Disclosure control plane', 'body' => 'WordPress blocks, schema, manifests, and approval packets tied into one operator surface.'],
            ['label' => 'Buyer fit', 'title' => 'Regulated web teams', 'body' => 'For teams shipping compliance-sensitive claims, partner collateral, docs, and support macros through WordPress.'],
            ['label' => 'Execution style', 'title' => 'Review-safe publication', 'body' => 'Treat disclosure fragments and qualifiers like release artifacts, not buried footer copy.'],
        ]
    );
}

function render_disclosure_lane(): string
{
    $service = new RegulatoryDisclosureKitService();
    $rows = '';
    foreach ($service->disclosureLanes() as $lane) {
        $surface = htmlspecialchars((string) $lane['surface'], ENT_QUOTES);
        $channel = htmlspecialchars((string) $lane['channel'], ENT_QUOTES);
        $owner = htmlspecialchars((string) $lane['owner'], ENT_QUOTES);
        $package = htmlspecialchars((string) $lane['package'], ENT_QUOTES);
        $risk = htmlspecialchars((string) $lane['risk'], ENT_QUOTES);
        $nextAction = htmlspecialchars((string) $lane['nextAction'], ENT_QUOTES);
        $status = htmlspecialchars((string) $lane['status'], ENT_QUOTES);
        $statusClass = status_class((string) $lane['status']);
        $rows .= <<<HTML
<tr>
  <td><b>{$surface}</b><br>{$channel}</td>
  <td>{$package}</td>
  <td><span class="st {$statusClass}">{$status}</span></td>
  <td>{$owner}</td>
  <td>{$risk}</td>
  <td>{$nextAction}</td>
</tr>
HTML;
    }

    $body = <<<HTML
<section class="section">
  <div class="sh"><h2>Disclosure lane</h2><div class="note">surface-by-surface release posture</div></div>
  <table class="ttbl">
    <thead>
      <tr>
        <th>Surface</th><th>Disclosure package</th><th>Status</th><th>Owner</th><th>Risk</th><th>Next action</th>
      </tr>
    </thead>
    <tbody>{$rows}</tbody>
  </table>
</section>
HTML;

    return shell(
        '/disclosure-lane',
        'Disclosure lane | WordPress Regulatory Disclosure Kit',
        'disclosure lane',
        'Review every public and operator-facing disclosure surface before claims outrun approval posture.',
        'The disclosure lane shows exactly which WordPress-managed surfaces are healthy, which are drifting, and which should block publication until the evidence and qualifier path is repaired.',
        $body,
        [
            ['label' => 'Signal', 'title' => 'Surface-level clarity', 'body' => 'See homepage, docs, PDF, and support disclosure posture in one table.'],
            ['label' => 'Pressure', 'title' => 'Approval timing', 'body' => 'Pair each surface with the actual commercial or regulatory risk if it ships stale.'],
            ['label' => 'Control', 'title' => 'Named owner + next fix', 'body' => 'Every disclosure drift item has one owner and one next action.'],
        ]
    );
}

function render_policy_evidence(): string
{
    $service = new RegulatoryDisclosureKitService();
    $cards = '';
    foreach ($service->policyEvidence() as $index => $artifact) {
        $indexPlus = $index + 1;
        $artifactName = htmlspecialchars((string) $artifact['artifact'], ENT_QUOTES);
        $purpose = htmlspecialchars((string) $artifact['purpose'], ENT_QUOTES);
        $owner = htmlspecialchars((string) $artifact['owner'], ENT_QUOTES);
        $anchor = htmlspecialchars((string) $artifact['anchor'], ENT_QUOTES);
        $status = htmlspecialchars((string) $artifact['status'], ENT_QUOTES);
        $statusClass = status_class((string) $artifact['status']);
        $cards .= <<<HTML
<article class="pcard">
  <div class="ptop"><div class="pnum">E-0{$indexPlus}</div><div class="ppri">{$status}</div></div>
  <h3>{$artifactName}</h3>
  <p class="pdesc">{$purpose}</p>
  <ul class="check">
    <li><strong>Owner:</strong> {$owner}</li>
    <li><strong>Anchor:</strong> {$anchor}</li>
    <li><strong>Status:</strong> <span class="st {$statusClass}">{$status}</span></li>
  </ul>
</article>
HTML;
    }

    $body = <<<HTML
<section class="section">
  <div class="sh"><h2>Policy evidence</h2><div class="note">review packets + machine-readable anchors</div></div>
  <div class="board">{$cards}</div>
</section>
HTML;

    return shell(
        '/policy-evidence',
        'Policy evidence | WordPress Regulatory Disclosure Kit',
        'policy evidence',
        'Keep the manifest, claim packet, schema notes, and collateral footer registry tied to one governed release path.',
        'This route turns disclosure governance into an evidence map: which artifacts are canonical, who owns them, and where the public site still points at stale review state.',
        $body,
        [
            ['label' => 'Manifest', 'title' => 'Machine-readable disclosure index', 'body' => 'Make reviewed claims and qualifiers discoverable to products, docs, and answer engines.'],
            ['label' => 'Collateral', 'title' => 'Field-safe footers', 'body' => 'PDFs and one-pagers need the same canonical URL and provenance trail as the site.'],
            ['label' => 'Schema', 'title' => 'Qualifier parity', 'body' => 'The visible copy and the schema graph need to say the same thing.'],
        ]
    );
}

function render_verification(): string
{
    $service = new RegulatoryDisclosureKitService();
    $cards = '';
    foreach ($service->verificationGates() as $index => $gate) {
        $indexPlus = $index + 1;
        $name = htmlspecialchars((string) $gate['gate'], ENT_QUOTES);
        $detail = htmlspecialchars((string) $gate['detail'], ENT_QUOTES);
        $status = htmlspecialchars((string) $gate['status'], ENT_QUOTES);
        $statusClass = status_class((string) $gate['status']);
        $cards .= <<<HTML
<article class="pcard">
  <div class="ptop"><div class="pnum">V-0{$indexPlus}</div><div class="ppri">{$status}</div></div>
  <h3>{$name}</h3>
  <p class="pdesc">{$detail}</p>
  <ul class="check">
    <li><strong>Gate status:</strong> <span class="st {$statusClass}">{$status}</span></li>
  </ul>
</article>
HTML;
    }

    $body = <<<HTML
<section class="section">
  <div class="sh"><h2>Verification</h2><div class="note">buyer-safe release gate</div></div>
  <div class="board">{$cards}</div>
</section>
HTML;

    return shell(
        '/verification',
        'Verification | WordPress Regulatory Disclosure Kit',
        'verification gate',
        'Block release when disclosure manifests, approval packets, and public claim surfaces disagree.',
        'The verification route keeps legal, SEO, and content teams aligned around one decision: is the WordPress surface safe to publish with the current claim and qualifier posture?',
        $body,
        [
            ['label' => 'Signal', 'title' => 'No silent disclosure drift', 'body' => 'Release should stop before a buyer discovers an approval mismatch.'],
            ['label' => 'Proof', 'title' => 'Schema + copy alignment', 'body' => 'Public HTML, schema nodes, and disclosure packets should converge on one reviewed truth.'],
            ['label' => 'Buyer value', 'title' => 'Safer commercial claims', 'body' => 'Keep regulated or compliance-sensitive language defensible across every surface.'],
        ]
    );
}

function render_docs(): string
{
    $service = new RegulatoryDisclosureKitService();
    $payload = $service->payload();
    $priorities = '';
    foreach ($payload['priorities'] as $priority) {
        $safePriority = htmlspecialchars((string) $priority, ENT_QUOTES);
        $priorities .= "<li>{$safePriority}</li>";
    }

    $body = <<<HTML
<section class="section">
  <div class="sh"><h2>Docs</h2><div class="note">implementation notes</div></div>
  <div class="board">
    <article class="pcard">
      <div class="ptop"><div class="pnum">A</div><div class="ppri">Plugin layer</div></div>
      <h3>WordPress manifest hooks</h3>
      <p class="pdesc">The plugin demonstrates how reviewed disclosure fragments can be exposed through a shortcode and REST endpoint without burying them in theme-only copy.</p>
      <ul class="check">
        <li>Shortcode output keeps disclosure payloads readable to admins and reviewers.</li>
        <li>REST route gives external systems one canonical disclosure anchor.</li>
      </ul>
    </article>
    <article class="pcard">
      <div class="ptop"><div class="pnum">B</div><div class="ppri">Control plane</div></div>
      <h3>Operator priorities</h3>
      <p class="pdesc">This repo treats disclosure governance as a release system, not a footer-edit task.</p>
      <ul class="check">{$priorities}</ul>
    </article>
  </div>
</section>
HTML;

    return shell(
        '/docs',
        'Docs | WordPress Regulatory Disclosure Kit',
        'operator docs',
        'Document the disclosure path so the release system can enforce it.',
        'The docs route explains why the plugin exists, how the public control plane is shaped, and where the machine-readable disclosure manifest fits into the broader Kinetic Gain stack.',
        $body,
        [
            ['label' => 'Language atlas', 'title' => 'PHP / WordPress surface', 'body' => 'This expands the platform atlas with a WordPress-native disclosure control plane.'],
            ['label' => 'Deploy', 'title' => 'Static Pages + plugin repo', 'body' => 'Local PHP routes validate the operator lane, while the prerendered bundle powers the public demo.'],
            ['label' => 'Embedded tie-back', 'title' => 'Governed product copy', 'body' => 'The same primitive can power in-product disclosure and qualifier governance for SaaS teams.'],
        ]
    );
}
