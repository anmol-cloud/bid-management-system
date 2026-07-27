<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family: Arial, sans-serif; background: #0a0c10; padding: 24px; color: #e2e8f0;">
    <div style="max-width: 560px; margin: 0 auto; background: #161a23; border: 1px solid rgba(245,185,66,0.15); border-radius: 12px; padding: 32px;">
        <h2 style="color: #f5b942; margin-top: 0;">Weekly Bid Performance Report</h2>
        <p style="color: #94a3b8;">{{ $report->week_start->format('d M') }} – {{ $report->week_end->format('d M Y') }}</p>

        <table style="width: 100%; margin-top: 20px; border-collapse: collapse;">
            <tr>
                <td style="padding: 10px 0; color: #94a3b8;">Total Bids</td>
                <td style="padding: 10px 0; text-align: right; color: #fff; font-weight: bold;">{{ $report->total_bids }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; color: #94a3b8;">Won</td>
                <td style="padding: 10px 0; text-align: right; color: #22c55e; font-weight: bold;">{{ $report->won_bids }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; color: #94a3b8;">Lost</td>
                <td style="padding: 10px 0; text-align: right; color: #ef4444; font-weight: bold;">{{ $report->lost_bids }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; color: #94a3b8; border-top: 1px solid rgba(255,255,255,0.1);">Success Rate</td>
                <td style="padding: 10px 0; text-align: right; color: #f5b942; font-weight: bold; border-top: 1px solid rgba(255,255,255,0.1);">{{ $report->success_rate }}%</td>
            </tr>
        </table>

        <p style="color: #64748b; font-size: 12px; margin-top: 24px;">This report has been automatically generated — from the Bid Command Center.</p>
    </div>
</body>
</html>
