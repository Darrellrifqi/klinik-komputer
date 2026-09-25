<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoiceNumber }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Dancing+Script:wght@600;700&family=Inter:wght@400;500;600;700;800;900&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
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

        /* Invoice Container Page */
        .invoice-wrapper {
            max-width: 820px;
            margin: 24px auto 40px auto;
            background: #ffffff;
            padding: 48px 56px 40px 56px;
            box-shadow: 0 4px 25px rgba(0,0,0,0.08);
            border-radius: 2px;
            min-height: 1050px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        /* Header */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
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

        .invoice-title-meta {
            text-align: right;
        }

        .invoice-main-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 34px;
            font-weight: 900;
            letter-spacing: 1px;
            color: #000000;
            line-height: 1;
            margin-bottom: 16px;
        }

        .meta-table {
            border-collapse: collapse;
            margin-left: auto;
        }

        .meta-table td {
            padding: 2.5px 0;
            font-size: 12px;
            color: #000000;
            line-height: 1.35;
        }

        .meta-table .meta-label {
            font-weight: 800;
            text-align: left;
            padding-right: 28px;
            font-family: 'Montserrat', sans-serif;
            letter-spacing: 0.3px;
        }

        .meta-table .meta-val {
            text-align: right;
            font-weight: 500;
            min-width: 100px;
        }

        /* Bill To / Ship To */
        .parties-section {
            display: flex;
            margin-bottom: 24px;
            padding-top: 6px;
        }

        .party-bill-to {
            width: 45%;
        }

        .party-ship-to {
            width: 55%;
        }

        .party-heading {
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            font-weight: 900;
            color: #000000;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .party-name {
            font-size: 13.5px;
            font-weight: 800;
            color: #000000;
            margin-bottom: 3px;
        }

        .party-city {
            font-size: 11.5px;
            color: #374151;
            font-style: italic;
        }

        .party-ship-val {
            font-size: 14px;
            color: #000000;
            font-weight: 500;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .items-table thead tr {
            background-color: #f5a623;
        }

        .items-table th {
            font-family: 'Montserrat', sans-serif;
            font-size: 12.5px;
            font-weight: 900;
            color: #000000;
            letter-spacing: 0.5px;
            padding: 8px 12px;
            text-align: left;
        }

        .items-table th.col-center {
            text-align: center;
        }

        .items-table th.col-right {
            text-align: right;
        }

        .items-table td {
            padding: 12px 12px 10px 12px;
            font-size: 13px;
            color: #000000;
            vertical-align: top;
        }

        .items-table td.desc-cell {
            font-weight: 800;
            color: #000000;
        }

        .items-table td.qty-cell {
            text-align: center;
            font-weight: 500;
        }

        .items-table td.price-cell,
        .items-table td.amount-cell {
            text-align: right;
            font-weight: 500;
            white-space: nowrap;
        }

        .items-table .currency-prefix {
            float: left;
            margin-right: 12px;
        }

        /* Divider Line */
        .invoice-divider-space {
            min-height: 100px;
        }

        .yellow-divider {
            border-top: 3.5px solid #f5a623;
            border-bottom: 1.5px solid #f5a623;
            height: 3px;
            margin: 20px 0 24px 0;
        }

        /* Summary / Bottom Area */
        .summary-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }

        .tc-box {
            width: 54%;
            font-size: 11.5px;
            line-height: 1.55;
            color: #000000;
        }

        .tc-row {
            display: flex;
            align-items: flex-start;
        }

        .tc-label {
            font-weight: 800;
            margin-right: 6px;
            white-space: nowrap;
        }

        .tc-content {
            font-weight: 500;
        }

        .tc-indent {
            padding-left: 10px;
            margin-top: 2px;
        }

        .totals-box {
            width: 44%;
        }

        .subtotal-row, .discount-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 6px;
            color: #000000;
            padding: 0 4px;
        }

        .subtotal-row .sub-label,
        .discount-row .sub-label {
            font-size: 13px;
            font-weight: 500;
        }

        .subtotal-row .sub-val,
        .discount-row .sub-val {
            display: flex;
            justify-content: space-between;
            width: 140px;
            font-weight: 500;
        }

        /* Total Highlight Card */
        .total-card {
            border: 2px solid #f5a623;
            margin-top: 10px;
            background: #ffffff;
        }

        .total-card-header {
            background-color: #f5a623;
            padding: 6px 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-card-header .label {
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
            font-weight: 900;
            color: #000000;
            letter-spacing: 0.5px;
        }

        .total-card-header .val-wrap {
            display: flex;
            justify-content: space-between;
            width: 140px;
            font-size: 13.5px;
            font-weight: 900;
            color: #000000;
        }

        .total-card-body {
            padding: 8px 14px 14px 14px;
            text-align: right;
            background: #ffffff;
        }

        .amounted-label {
            font-size: 10.5px;
            font-style: italic;
            color: #000000;
            margin-bottom: 6px;
        }

        .amounted-words {
            font-size: 13px;
            font-style: italic;
            color: #000000;
            text-align: center;
            line-height: 1.35;
        }

        /* Footer */
        .invoice-footer {
            margin-top: 50px;
            text-align: center;
        }

        .thank-you-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            margin-bottom: 12px;
        }

        .thank-you-line {
            flex: 1;
            height: 1px;
            background: #000000;
            max-width: 160px;
        }

        .thank-you-text {
            font-family: 'Alex Brush', 'Dancing Script', cursive;
            font-size: 38px;
            color: #111827;
            font-weight: 400;
            line-height: 1;
            letter-spacing: 1px;
        }

        .contact-info {
            font-size: 11px;
            color: #111827;
            font-weight: 600;
            letter-spacing: 0.2px;
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

            .invoice-wrapper {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 20mm 20mm 15mm 20mm !important;
                box-shadow: none !important;
                border: none !important;
                min-height: 100vh;
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
        <div class="title">Preview Invoice — {{ $invoiceNumber }} ({{ $ticket->customer_name ?? 'Pelanggan' }})</div>
        <div class="actions">
            <button class="btn-print" onclick="window.print()">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak / Simpan PDF
            </button>
            <button class="btn-close" onclick="window.close()">Tutup</button>
        </div>
    </div>

    <!-- Main Printable Invoice -->
    <div class="invoice-wrapper">
        <div>
            <!-- Header Section -->
            <div class="invoice-header">
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

                <div class="invoice-title-meta">
                    <div class="invoice-main-title">INVOICE</div>
                    <table class="meta-table">
                        <tr>
                            <td class="meta-label">NO. INVOICE</td>
                            <td class="meta-val">{{ $invoiceNumber }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">INVOICE DATE</td>
                            <td class="meta-val">{{ $invoiceDate }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">NO. PO</td>
                            <td class="meta-val">{{ $noPo }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">TERM</td>
                            <td class="meta-val">{{ $term }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">DUE DATE</td>
                            <td class="meta-val">{{ $dueDate }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Bill To / Ship To Section -->
            <div class="parties-section">
                <div class="party-bill-to">
                    <div class="party-heading">BILL TO</div>
                    <div class="party-name">{{ $ticket->customer_name ?? 'Kak Opet' }}</div>
                    <div class="party-city">{{ $ticket->customer_city ?? 'Kota Bandung' }}</div>
                </div>
                <div class="party-ship-to">
                    <div class="party-heading">SHIP TO</div>
                    <div class="party-ship-val">-</div>
                </div>
            </div>

            <!-- Items Table -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">DESCRIPTION</th>
                        <th class="col-center" style="width: 15%;">QTY</th>
                        <th class="col-right" style="width: 17%;">PRICE</th>
                        <th class="col-right" style="width: 18%;">AMOUNT</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($items) && is_array($items) && count($items) > 0)
                        @foreach($items as $item)
                        <tr>
                            <td class="desc-cell">{{ $item['desc'] ?? '-' }}</td>
                            <td class="qty-cell">{{ $item['qty'] ?? 1 }} Unit</td>
                            <td class="price-cell">
                                <span class="currency-prefix">Rp</span>
                                {{ number_format($item['price'] ?? 0, 2, '.', ',') }}
                            </td>
                            <td class="amount-cell">
                                <span class="currency-prefix">Rp</span>
                                {{ number_format($item['amount'] ?? 0, 2, '.', ',') }}
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="desc-cell">{{ $description }}</td>
                            <td class="qty-cell">1 Unit</td>
                            <td class="price-cell">
                                <span class="currency-prefix">Rp</span>
                                {{ number_format($harga, 2, '.', ',') }}
                            </td>
                            <td class="amount-cell">
                                <span class="currency-prefix">Rp</span>
                                {{ number_format($harga, 2, '.', ',') }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <!-- Whitespace area to match invoice proportion -->
            <div class="invoice-divider-space"></div>

            <!-- Yellow Divider Line -->
            <div class="yellow-divider"></div>

            <!-- Summary & Terms Section -->
            <div class="summary-section">
                <!-- Left: Terms & Conditions -->
                <div class="tc-box">
                    <div class="tc-row">
                        <span class="tc-label">T&amp;C :</span>
                        <div class="tc-content">
                            <div>- Cash before delivery (CBD)</div>
                            @if(isset($bankAccount) && $bankAccount === 'CIMB 5600')
                            <div style="margin-top: 4px;">- Bank Account CIMB Niaga (Kode Bank 022)</div>
                            <div class="tc-indent">No. 800-17770-5600 a/n. CV Belajar Kerja Indonesia</div>
                            @else
                            <div style="margin-top: 4px;">- Bank Account CIMB Niaga (Kode Bank 022)</div>
                            <div class="tc-indent">No. 800-17609-1100 a/n. PT Mabito Karya</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right: Subtotal, Discount & Total Card -->
                <div class="totals-box">
                    <div class="subtotal-row">
                        <span class="sub-label">Sub Total</span>
                        <div class="sub-val">
                            <span>Rp</span>
                            <span>{{ number_format($harga, 2, '.', ',') }}</span>
                        </div>
                    </div>
                    <div class="discount-row">
                        <span class="sub-label">Discount</span>
                        <div class="sub-val">
                            <span>Rp</span>
                            <span>-</span>
                        </div>
                    </div>

                    <!-- Yellow bordered total card -->
                    <div class="total-card">
                        <div class="total-card-header">
                            <span class="label">TOTAL</span>
                            <div class="val-wrap">
                                <span>Rp</span>
                                <span>{{ number_format($harga, 2, '.', ',') }}</span>
                            </div>
                        </div>
                        <div class="total-card-body">
                            <div class="amounted-label">amounted :</div>
                            <div class="amounted-words">{{ $terbilangText ?: 'nol rupiah' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <div class="thank-you-wrapper">
                <div class="thank-you-line"></div>
                <div class="thank-you-text">thank you</div>
                <div class="thank-you-line"></div>
            </div>
            <div class="contact-info">
                Contact Number +62 85103051000 ( klinik komputer ) / Email : Sales@klinik-komputer.com
            </div>
        </div>
    </div>

    <script>
        // Auto open print dialog when opened via direct print parameter
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
