<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Receipt #{{ $payment->receipt_no }}</title>
  <style>
    body {
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      color: #333333;
      margin: 0;
      padding: 20px;
      font-size: 14px;
      line-height: 1.5;
    }
    .container {
      width: 100%;
      max-width: 800px;
      margin: 0 auto;
      border: 1px solid #e5e7eb;
      padding: 30px;
      border-radius: 8px;
    }
    .header {
      border-bottom: 2px solid #3b82f6;
      padding-bottom: 20px;
      margin-bottom: 30px;
    }
    .header-table {
      width: 100%;
    }
    .header-table td {
      vertical-align: top;
    }
    .logo {
      font-size: 24px;
      font-weight: bold;
      color: #1e3a8a;
    }
    .logo-sub {
      font-size: 12px;
      color: #6b7280;
    }
    .receipt-title {
      font-size: 20px;
      font-weight: bold;
      color: #111827;
      text-align: right;
      margin-bottom: 5px;
    }
    .receipt-meta {
      text-align: right;
      font-size: 12px;
      color: #4b5563;
    }
    .receipt-meta span {
      font-weight: bold;
    }
    .info-section {
      width: 100%;
      margin-bottom: 30px;
    }
    .info-table {
      width: 100%;
      border-collapse: collapse;
    }
    .info-table td {
      width: 50%;
      padding: 15px;
      border: 1px solid #f3f4f6;
      background-color: #f9fafb;
      vertical-align: top;
    }
    .info-header {
      font-size: 11px;
      font-weight: bold;
      text-transform: uppercase;
      color: #9ca3af;
      margin-bottom: 8px;
      letter-spacing: 0.5px;
    }
    .info-name {
      font-size: 15px;
      font-weight: bold;
      color: #111827;
      margin-bottom: 4px;
    }
    .info-detail {
      font-size: 13px;
      color: #4b5563;
    }
    .ledger-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 40px;
    }
    .ledger-table th {
      background-color: #3b82f6;
      color: #ffffff;
      font-weight: bold;
      text-transform: uppercase;
      font-size: 11px;
      letter-spacing: 0.5px;
      padding: 12px 15px;
      text-align: left;
    }
    .ledger-table td {
      padding: 15px;
      border-bottom: 1px solid #e5e7eb;
      color: #374151;
    }
    .ledger-table tr:last-child td {
      border-bottom: 2px solid #3b82f6;
    }
    .total-row {
      font-weight: bold;
      background-color: #f3f4f6;
    }
    .footer {
      margin-top: 50px;
      width: 100%;
    }
    .footer-table {
      width: 100%;
    }
    .footer-table td {
      width: 50%;
      vertical-align: bottom;
    }
    .signature-area {
      text-align: right;
    }
    .signature-line {
      border-top: 1px solid #9ca3af;
      width: 180px;
      margin-left: auto;
      margin-top: 50px;
      padding-top: 8px;
      font-size: 12px;
      font-weight: bold;
      color: #4b5563;
      text-align: center;
    }
    .receipt-note {
      font-size: 11px;
      color: #9ca3af;
      margin-top: 30px;
      text-align: center;
      border-top: 1px dashed #e5e7eb;
      padding-top: 15px;
    }
  </style>
</head>
<body>

  <div class="container">
    
    <!-- Header -->
    <div class="header">
      <table class="header-table">
        <tr>
          <td>
            <div class="logo">PropDesk CRM</div>
            <div class="logo-sub">Complete Real Estate Solutions</div>
          </td>
          <td>
            <div class="receipt-title">PAYMENT RECEIPT</div>
            <div class="receipt-meta">
              Receipt No: <span>{{ $payment->receipt_no }}</span><br>
              Date: <span>{{ $payment->paid_date ? $payment->paid_date->format('d M Y') : date('d M Y') }}</span><br>
              Booking Ref: <span>{{ $payment->booking?->booking_no }}</span>
            </div>
          </td>
        </tr>
      </table>
    </div>

    <!-- Client & Property Details -->
    <div class="info-section">
      <table class="info-table">
        <tr>
          <td>
            <div class="info-header">Received From (Customer)</div>
            <div class="info-name">{{ $payment->booking?->customer?->name }}</div>
            <div class="info-detail">
              Email: {{ $payment->booking?->customer?->email }}<br>
              Phone: {{ $payment->booking?->customer?->phone ?: 'N/A' }}
            </div>
          </td>
          <td>
            <div class="info-header">Property Details</div>
            <div class="info-name">{{ $payment->booking?->project?->name }}</div>
            <div class="info-detail">
              Unit Number: {{ $payment->booking?->property?->unit_number }} ({{ $payment->booking?->property?->tower }})<br>
              Floor Level: Floor {{ $payment->booking?->property?->floor }}<br>
              Location: {{ $payment->booking?->project?->location }}
            </div>
          </td>
        </tr>
      </table>
    </div>

    <!-- Ledger Details Table -->
    <table class="ledger-table">
      <thead>
        <tr>
          <th>Installment Milestone Description</th>
          <th>Due Amount</th>
          <th>Received Amount</th>
          <th>Outstanding Balance</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="font-weight: bold;">
            {{ str_replace('_', ' ', ucfirst($payment->installment_stage)) }} Milestone Installment
          </td>
          <td>₹{{ number_format($payment->amount_due, 2) }}</td>
          <td style="color: #10b981; font-weight: bold;">₹{{ number_format($payment->amount_paid, 2) }}</td>
          <td>₹{{ number_format($payment->amount_due - $payment->amount_paid, 2) }}</td>
        </tr>
        <tr class="total-row">
          <td>Total Received</td>
          <td colspan="3" style="text-align: right; color: #10b981; font-size: 16px;">
            ₹{{ number_format($payment->amount_paid, 2) }}
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Footer Area -->
    <div class="footer">
      <table class="footer-table">
        <tr>
          <td>
            <div class="info-header">Payment Mode</div>
            <div class="info-detail" style="font-size: 14px; font-weight: bold; color: #111827;">
              {{ $payment->payment_method ?: 'N/A' }}
            </div>
            <div class="info-detail" style="margin-top: 10px; font-size: 12px; color: #6b7280;">
              * Note: Subject to realization in case of cheque payments.
            </div>
          </td>
          <td>
            <div class="signature-area">
              <div class="signature-line">Authorized Signatory</div>
            </div>
          </td>
        </tr>
      </table>
    </div>

    <!-- Note footer -->
    <div class="receipt-note">
      This is a computer-generated transaction record invoice. No physical signature is required.
    </div>

  </div>

</body>
</html>
