<?php

// Script to update welcome.blade.php with conditional statistics

$filePath = '/home/mohamed/Desktop/iam/iam/resources/views/welcome.blade.php';
$content = file_get_contents($filePath);

// Define the search pattern for the user info grid section
$searchPattern = '/<div class="user-info-grid"[^>]*>.*?<\/div>\s*<\/div>\s*<\/div>\s*<\/div>\s*<\/div>/s';

// Define the replacement content with conditional logic
$replacement = '<div class="user-info-grid" style="display:grid;grid-template-columns:1fr 1fr;grid-template-rows:1fr 1fr;gap:20px;margin-bottom:2rem;max-width:100%;margin-left:0;margin-right:0;padding:0;">
            @if($user)
                <!-- User Personal Statistics -->
                <div class="user-info-card enhanced">
                    <div class="info-icon">💰</div>
                    <div class="info-content">
                        <div class="info-label">رصيدك الحالي</div>
                        <div class="info-value" data-stat="user_balance">
                            ${{ number_format($statistics[\'user_balance\'], 2) }}
                        </div>
                    </div>
                </div>
                <div class="user-info-card enhanced">
                    <div class="info-icon">🔄</div>
                    <div class="info-content">
                        <div class="info-label">معاملاتك</div>
                        <div class="info-value" data-stat="user_transactions">
                            {{ number_format($statistics[\'user_transactions\']) }} معاملة
                        </div>
                    </div>
                </div>
                <div class="user-info-card enhanced">
                    <div class="info-icon">📈</div>
                    <div class="info-content">
                        <div class="info-label">إجمالي إيداعاتك</div>
                        <div class="info-value" data-stat="total_deposits">
                            ${{ number_format($statistics[\'total_deposits\'], 2) }}
                        </div>
                    </div>
                </div>
                <div class="user-info-card enhanced">
                    <div class="info-icon">📉</div>
                    <div class="info-content">
                        <div class="info-label">إجمالي سحوباتك</div>
                        <div class="info-value" data-stat="total_withdrawals">
                            ${{ number_format($statistics[\'total_withdrawals\'], 2) }}
                        </div>
                    </div>
                </div>
            @else
                <!-- Guest/System Statistics -->
                <div class="user-info-card enhanced">
                    <div class="info-icon">👥</div>
                    <div class="info-content">
                        <div class="info-label">إجمالي المستخدمين</div>
                        <div class="info-value" data-stat="total_users">
                            {{ number_format($statistics[\'total_users\']) }} مستخدم
                        </div>
                    </div>
                </div>
                <div class="user-info-card enhanced">
                    <div class="info-icon">💰</div>
                    <div class="info-content">
                        <div class="info-label">إجمالي الأرصدة</div>
                        <div class="info-value" data-stat="total_balance">
                            ${{ number_format($statistics[\'total_balance\'], 2) }}
                        </div>
                    </div>
                </div>
                <div class="user-info-card enhanced">
                    <div class="info-icon">🔄</div>
                    <div class="info-content">
                        <div class="info-label">إجمالي المعاملات</div>
                        <div class="info-value" data-stat="total_transactions">
                            {{ number_format($statistics[\'total_transactions\']) }} معاملة
                        </div>
                    </div>
                </div>
                <div class="user-info-card enhanced">
                    <div class="info-icon">📈</div>
                    <div class="info-content">
                        <div class="info-label">المستخدمون النشطون</div>
                        <div class="info-value" data-stat="active_users">
                            {{ number_format($statistics[\'active_users\']) }} نشط
                        </div>
                    </div>
                </div>
            @endif
        </div>';

// Perform the replacement
$newContent = preg_replace($searchPattern, $replacement, $content);

if ($newContent !== null && $newContent !== $content) {
    file_put_contents($filePath, $newContent);
    echo "File updated successfully!\n";
} else {
    echo "No changes made or replacement failed.\n";
}

?>
