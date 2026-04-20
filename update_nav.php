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

$replacement = <<<'HTML'
<a class="nav-link d-flex align-items-center position-relative" href="#" data-bs-toggle="modal" data-bs-target="#myBookingsModal" title="View and manage your bookings" aria-label="My Bookings">
    <i class="bi bi-journal-check me-1" aria-hidden="true"></i>
    <span>My Bookings</span>
    @php
      $pendingConfirmations = Auth::user()->bookings->where('status', 'pending')->where('customer_confirmed', false)->count();
    @endphp
    @if($pendingConfirmations > 0)
      <span class="position-absolute top-25 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem; padding: 0.25em 0.5em; animation: pulseBadge 2s infinite; box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); margin-left: -10px; margin-top: 5px;">
        {{ $pendingConfirmations }}
        <span class="visually-hidden">pending confirmations</span>
      </span>
      <style>
        @keyframes pulseBadge {
          0% { transform: translate(-50%, -50%) scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
          70% { transform: translate(-50%, -50%) scale(1); box-shadow: 0 0 0 6px rgba(220, 53, 69, 0); }
          100% { transform: translate(-50%, -50%) scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
        }
      </style>
    @endif
  </a>
HTML;

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    $pattern = '/<a class="nav-link d-flex align-items-center"\s+href="#"\s+data-bs-toggle="modal"\s+data-bs-target="#myBookingsModal".*?<\/a>/s';
    
    $count = 0;
    $newContent = preg_replace($pattern, $replacement, $content, -1, $count);
    if ($count > 0) {
        file_put_contents($file, $newContent);
        echo "Updated: $file\n";
    }
}
echo "Done\n";
