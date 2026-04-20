<?php
$files = [
    'resources/views/pages/home.blade.php',
    'resources/views/pages/about.blade.php',
    'resources/views/pages/services.blade.php',
    'resources/views/pages/contact.blade.php',
    'resources/views/pages/terms.blade.php',
    'resources/views/pages/cookies.blade.php',
    'resources/views/pages/privacy.blade.php',
];

$original_block_regex = '/<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">\s*<span class="navbar-toggler-icon"><\/span>\s*<\/button>/s';

$replacement = <<<'HTML'
<button class="navbar-toggler position-relative" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
        @if(Auth::check() && Auth::user()->bookings)
          @php
            $pendingConfirmations = Auth::user()->bookings->where('status', 'pending')->where('customer_confirmed', false)->count();
          @endphp
          @if($pendingConfirmations > 0)
            <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle" style="animation: pulseBadge 2s infinite; margin-left: -5px; margin-top: 5px;">
              <span class="visually-hidden">pending confirmations</span>
            </span>
          @endif
        @endif
      </button>
HTML;

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    $count = 0;
    $newContent = preg_replace($original_block_regex, $replacement, $content, -1, $count);
    if ($count > 0) {
        file_put_contents($file, $newContent);
        echo "Updated: $file\n";
    }
}
echo "Done\n";
