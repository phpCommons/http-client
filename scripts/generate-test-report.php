<?php
declare(strict_types=1);

// Renders a self-contained HTML report from PHPUnit's JUnit XML log.
// Usage: php scripts/generate-test-report.php <junit.xml> <output.html>

[$script, $input, $output] = $argv + [null, 'test-results/junit.xml', 'tests/reports/test-report.html'];

if (!is_file($input)) {
    fwrite(STDERR, "JUnit log not found: $input\n");
    exit(1);
}

$xml = simplexml_load_file($input);
$cases = [];
foreach ($xml->xpath('//testcase') as $case) {
    $status = 'passed';
    $message = '';
    foreach (['failure' => 'failed', 'error' => 'failed', 'skipped' => 'skipped'] as $tag => $mapped) {
        if (isset($case->{$tag})) {
            $status = $mapped;
            $message = trim((string) $case->{$tag});
        }
    }
    $cases[] = [
        'suite' => (string) $case['class'],
        'name' => (string) $case['name'],
        'time' => (float) $case['time'],
        'status' => $status,
        'message' => $message,
    ];
}
usort($cases, fn ($a, $b) => [$a['status'] === 'passed', $a['suite'], $a['name']] <=> [$b['status'] === 'passed', $b['suite'], $b['name']]);

$counts = array_count_values(array_column($cases, 'status')) + ['passed' => 0, 'failed' => 0, 'skipped' => 0];
$total = count($cases);
$duration = array_sum(array_column($cases, 'time'));
$colour = ['passed' => '#3fb950', 'failed' => '#f85149', 'skipped' => '#d29922'];
$e = fn (string $s) => htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE);

$rows = '';
foreach ($cases as $c) {
    $rows .= sprintf(
        '<tr><td><span class="badge" style="color:%1$s;border-color:%1$s55;background:%1$s14">%2$s</span></td><td>%3$s</td><td>%4$s%5$s</td><td class="num">%6$.3fs</td></tr>',
        $colour[$c['status']], $c['status'], $e($c['suite']), $e($c['name']),
        $c['message'] === '' ? '' : '<pre>' . $e($c['message']) . '</pre>', $c['time']
    );
}
$bar = '';
foreach ($colour as $status => $hex) {
    $bar .= sprintf('<span style="flex:%d;background:%s" title="%s: %d"></span>', $counts[$status], $hex, $status, $counts[$status]);
}

$html = sprintf(<<<'HTML'
<!doctype html><html lang="en"><head><meta charset="utf-8"><title>http-client test report</title><style>
body{margin:0;background:#0d0d0f;color:#c9d1d9;font:13px/1.5 system-ui,sans-serif;padding:24px}
h1{font-size:18px;margin:0 0 4px}h1::before{content:"";display:inline-block;width:8px;height:8px;border-radius:50%%;background:#c2651a;margin-right:8px}
.meta{color:#8b949e;margin-bottom:16px}.kpis{display:flex;gap:12px;margin-bottom:16px}
.kpi{background:#151518;border:1px solid #2a2a30;border-radius:10px;padding:12px 18px}.kpi b{display:block;font-size:24px}
.result-bar{display:flex;height:8px;border-radius:4px;overflow:hidden;background:#ffffff0a;gap:2px;margin-bottom:16px}
table{width:100%%;border-collapse:collapse;background:#151518;border:1px solid #2a2a30;border-radius:10px}
th{text-align:left;font-size:10px;letter-spacing:.08em;color:#8b949e;font-weight:500;padding:8px;border-bottom:1px solid #2a2a30;text-transform:uppercase}
td{padding:6px 8px;border-bottom:1px solid #2a2a30;vertical-align:top}.num{text-align:right;font-variant-numeric:tabular-nums}
.badge{border:1px solid;border-radius:999px;padding:1px 8px;font-size:11px}
pre{white-space:pre-wrap;background:#0d0d0f;border:1px solid #2a2a30;border-radius:6px;padding:8px 10px;max-height:240px;overflow:auto}
</style></head><body><h1>PHPUnit test report</h1><div class="meta">%s · PHP %s · %s</div>
<div class="kpis"><div class="kpi"><b>%d</b>total</div><div class="kpi"><b style="color:#3fb950">%d</b>passed</div><div class="kpi"><b style="color:#f85149">%d</b>failed</div><div class="kpi"><b style="color:#d29922">%d</b>skipped</div><div class="kpi"><b>%.2fs</b>duration</div></div>
<div class="result-bar">%s</div>
<table><thead><tr><th>Status</th><th>Class</th><th>Test</th><th>Time</th></tr></thead><tbody>%s</tbody></table></body></html>
HTML,
    getenv('GITHUB_SHA') ? substr(getenv('GITHUB_SHA'), 0, 7) : 'local', PHP_VERSION, gmdate('Y-m-d H:i') . ' UTC',
    $total, $counts['passed'], $counts['failed'], $counts['skipped'], $duration, $bar, $rows
);

@mkdir(dirname($output), 0777, true);
file_put_contents($output, $html);
echo "Report written to $output ($total tests, {$counts['failed']} failed)\n";
