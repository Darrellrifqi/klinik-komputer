<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt {{ $receiptNumber }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #e2e8f0;
            color: #000000;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Top Action Bar (Screen Only) */
        .no-print-bar {
            background: #1e293b;
            color: white;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .no-print-bar .title {
            font-size: 14px;
            font-weight: 600;
            color: #e2e8f0;
        }

        .no-print-bar .actions {
            display: flex;
            gap: 10px;
        }

        .btn-print {
            background: #f5a623;
            color: #000;
            font-weight: 700;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: background 0.15s;
        }
        .btn-print:hover {
            background: #e09418;
        }

        .btn-close {
            background: #334155;
            color: #fff;
            font-weight: 600;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
        }
        .btn-close:hover {
            background: #475569;
        }

        /* Printable Receipt Container */
        .receipt-wrapper {
            max-width: 820px;
            margin: 24px auto 40px auto;
            background: #ffffff;
            padding: 48px 56px 48px 56px;
            box-shadow: 0 4px 25px rgba(0,0,0,0.08);
            border-radius: 2px;
            min-height: 840px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        /* Header */
        .receipt-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
        }

        .brand-section {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .brand-logo-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-title {
            font-size: 21px;
            font-weight: 800;
            color: #111827;
            letter-spacing: -0.3px;
            font-family: 'Inter', sans-serif;
        }

        .brand-address {
            font-size: 12px;
            color: #111827;
            line-height: 1.45;
            margin-top: 4px;
            font-weight: 500;
        }

        .receipt-title-meta {
            text-align: right;
        }

        .receipt-main-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 28px;
            font-weight: 900;
            letter-spacing: 0.5px;
            color: #000000;
            line-height: 1.1;
            margin-bottom: 12px;
            border-bottom: 2.5px solid #000000;
            padding-bottom: 4px;
            display: inline-block;
        }

        .meta-table {
            border-collapse: collapse;
            margin-left: auto;
        }

        .meta-table td {
            padding: 2px 0;
            font-size: 12px;
            color: #000000;
            line-height: 1.35;
        }

        .meta-table .meta-label {
            font-weight: 800;
            text-align: left;
            padding-right: 24px;
            font-family: 'Montserrat', sans-serif;
            letter-spacing: 0.3px;
        }

        .meta-table .meta-val {
            text-align: right;
            font-weight: 500;
            min-width: 110px;
        }

        /* Received From Section */
        .received-from-section {
            margin-bottom: 24px;
            padding-top: 4px;
        }

        .received-label {
            font-family: 'Montserrat', sans-serif;
            font-size: 13.5px;
            font-weight: 900;
            color: #000000;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .customer-name {
            font-size: 14px;
            font-weight: 800;
            color: #000000;
            margin-bottom: 3px;
        }

        .customer-city {
            font-size: 11.5px;
            color: #374151;
            font-style: italic;
        }

        /* Amber Section Bar */
        .yellow-bar-section {
            background-color: #f5a623;
            padding: 7px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .yellow-bar-section .bar-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 12.5px;
            font-weight: 900;
            color: #000000;
            letter-spacing: 0.5px;
        }

        .yellow-bar-section .bar-title-right {
            font-family: 'Montserrat', sans-serif;
            font-size: 12.5px;
            font-weight: 900;
            color: #000000;
            letter-spacing: 0.5px;
            width: 250px;
            text-align: center;
        }

        /* Content Section: Payment Info & Confirmed By */
        .body-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 24px;
        }

        .payment-info-col {
            flex: 1;
            max-width: 500px;
        }

        /* Amount Card */
        .amount-card {
            border: 2.5px solid #000000;
            padding: 10px 16px 14px 16px;
            background: #ffffff;
            margin-bottom: 16px;
        }

        .amount-header-label {
            font-size: 12px;
            font-weight: 600;
            color: #000000;
            text-decoration: underline;
            margin-bottom: 8px;
        }

        .amount-content-row {
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        .amount-numeric-wrap {
            display: flex;
            align-items: baseline;
            gap: 16px;
            min-width: 170px;
        }

        .amount-prefix {
            font-size: 16px;
            font-weight: 800;
            color: #000000;
        }

        .amount-value {
            font-size: 20px;
            font-weight: 800;
            color: #000000;
            white-space: nowrap;
        }

        .amount-divider-line {
            width: 1.5px;
            height: 48px;
            background-color: #000000;
            margin: 0 18px;
        }

        .amount-words {
            font-size: 13px;
            font-style: italic;
            font-weight: 700;
            color: #000000;
            line-height: 1.35;
        }

        /* Note Details */
        .notes-area {
            padding: 0 2px;
        }

        .note-header-label {
            font-size: 12px;
            font-weight: 600;
            color: #000000;
            text-decoration: underline;
            margin-bottom: 4px;
        }

        .note-status-val {
            font-size: 13px;
            color: #000000;
            margin-bottom: 3px;
        }

        .note-extra-val {
            font-size: 12.5px;
            color: #000000;
            margin-bottom: 5px;
            line-height: 1.35;
        }

        .note-separator {
            border-top: 1.5px solid #000000;
            margin: 6px 0 8px 0;
            width: 100%;
        }

        .item-desc-text {
            font-size: 13px;
            font-weight: 800;
            color: #000000;
            margin-bottom: 3px;
        }

        .warranty-text {
            font-size: 13px;
            font-weight: 800;
            color: #000000;
        }

        /* Confirmed By Column */
        .confirmed-by-col {
            width: 250px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 6px;
        }

        .stamp-signature-wrap {
            position: relative;
            width: 170px;
            height: 100px;
            margin-bottom: 6px;
        }

        .pic-name {
            font-size: 12px;
            font-weight: 500;
            color: #000000;
            margin-bottom: 24px;
        }

        .thank-you-note {
            font-size: 11.5px;
            font-style: italic;
            color: #374151;
        }

        /* Print Media Styles */
        @media print {
            body {
                background: #ffffff;
                margin: 0;
                padding: 0;
            }

            .no-print-bar {
                display: none !important;
            }

            .receipt-wrapper {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 20mm 20mm 15mm 20mm !important;
                box-shadow: none !important;
                border: none !important;
                min-height: auto;
            }

            @page {
                size: A4 portrait;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <!-- On-screen Toolbar -->
    <div class="no-print-bar">
        <div class="title">Preview Payment Receipt — {{ $receiptNumber }} ({{ $ticket->customer_name ?? 'Pelanggan' }})</div>
        <div class="actions">
            <button class="btn-print" onclick="window.print()">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak / Simpan PDF
            </button>
            <button class="btn-close" onclick="window.close()">Tutup</button>
        </div>
    </div>

    <!-- Main Printable Receipt -->
    <div class="receipt-wrapper">
        <div>
            <!-- Header Section -->
            <div class="receipt-header">
                <div class="brand-section">
                    <div class="brand-logo-title">
                        <img src="{{ asset('images/klinik-logo-icon.png') }}" alt="Logo Klinik Komputer" style="width: 50px; height: 50px; object-fit: contain; border-radius: 50%;">
                        <div class="brand-title">klinik-komputer.com</div>
                    </div>
                    <div class="brand-address">
                        Ruko Segitiga Mas Kosambi<br>
                        Klinik-Komputer Blok E8
                    </div>
                </div>

                <div class="receipt-title-meta">
                    <div class="receipt-main-title">PAYMENT RECEIPT</div>
                    <table class="meta-table">
                        <tr>
                            <td class="meta-label">NO. RECEIPT</td>
                            <td class="meta-val">{{ $receiptNumber }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">NO. INVOICE</td>
                            <td class="meta-val">{{ $invoiceNumber }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="meta-val" style="padding-bottom: 4px;">{{ $receiptDate }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">PAID BY</td>
                            <td class="meta-val">{{ $paidBy }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="meta-val">{{ $paymentMethod }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Received From Section -->
            <div class="received-from-section">
                <div class="received-label">RECEIVED FROM :</div>
                <div class="customer-name">{{ $ticket->customer_name ?? 'Kak Fajar' }}</div>
                <div class="customer-city">{{ $ticket->customer_city ?? 'Kota Bandung' }}</div>
            </div>

            <!-- Amber Section Bar -->
            <div class="yellow-bar-section">
                <div class="bar-title">PAYMENT INFO</div>
                <div class="bar-title-right">CONFIRMED BY</div>
            </div>

            <!-- Content Area: Payment Info & Confirmed By -->
            <div class="body-section">
                <!-- Left: Payment Info Box & Notes -->
                <div class="payment-info-col">
                    <div class="amount-card">
                        <div class="amount-header-label">Amount :</div>
                        <div class="amount-content-row">
                            <div class="amount-numeric-wrap">
                                <span class="amount-prefix">Rp</span>
                                <span class="amount-value">{{ number_format($harga, 2, '.', ',') }}</span>
                            </div>
                            <div class="amount-divider-line"></div>
                            <div class="amount-words">
                                {{ $terbilangText ?: 'nol rupiah' }}
                            </div>
                        </div>
                    </div>

                    <div class="notes-area">
                        <div class="note-header-label">Note :</div>
                        <div class="note-status-val">{{ $noteStatus }}</div>
                        @if(!empty($extraNote))
                        <div class="note-extra-val">{{ $extraNote }}</div>
                        @endif
                        <div class="note-separator"></div>
                        <div class="item-desc-text">
                            @if(isset($items) && is_array($items) && count($items) > 0)
                                @foreach($items as $item)
                                    @if(is_array($item))
                                        <div>
                                            {{ $item['desc'] ?? '-' }}
                                            @if(isset($item['qty']) && (int)$item['qty'] > 1)
                                                ({{ $item['qty'] }} Unit)
                                            @endif
                                        </div>
                                    @else
                                        <div>{{ $item }}</div>
                                    @endif
                                @endforeach
                            @else
                                <div>{{ $description }}</div>
                            @endif
                        </div>
                        <div class="warranty-text">{{ $warranty }}</div>
                    </div>
                </div>

                <!-- Right: Confirmed By (Manual Stamp & Signature) -->
                <div class="confirmed-by-col">
                    <!-- Area kosong untuk tanda tangan dan stempel manual -->
                    <div class="stamp-signature-wrap" style="height: 85px;"></div>

                    <div class="pic-name">( ........................................ )</div>
                    <div class="thank-you-note">Thank you for your payment</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('autoprint') === '1') {
                setTimeout(function() {
                    window.print();
                }, 400);
            }
        });
    </script>
</body>
</html>
