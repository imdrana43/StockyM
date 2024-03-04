<!-- Accessing $symbol variable -->
{{-- Symbol:
{{dd($symbol)}} --}}

<!-- Accessing $setting variable -->
{{-- Setting:
{{dd($setting)}} --}}

<!-- Accessing $sale variable -->
{{-- Sale:
{{dd($sale)}} --}}

<!-- Accessing $details variable -->
{{-- Details:
{{dd($setting)}} --}}

@php
    $symbol = '';
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Invoice - {{ substr($sale['Ref'], 3) }}
    </title>
    <link rel="stylesheet" href="/invoice/style.css" media="all" />
</head>

<body>
    <div class="page-header">
        <header class="clearfix">
            <div id="logo">
                <a href="/">
                <img src="/images/{{ $setting->logo }}">
                </a>
            </div>
            <div id="company">
                <h1 class="name">{{ $setting->CompanyName }}</h1>
                <div class="t1">{{ $setting->CompanyAdress }}</div>
                <div class="t2">{{ $setting->CompanyPhone }}</div>
                <div class="t1"><a href="mailto:{{ $setting->email }}">{{ $setting->email }}</a></div>
            </div>
            </div>
        </header>
    </div>

    <div class="page-footer">
        <footer>
            Invoice was created digitally and is valid without the signature and seal.
        </footer>
    </div>

    <table>
        <thead style="background-color: white;">
            <tr>
                <td>
                    <!--place holder for the fixed-position header-->
                    <div class="page-header-space"></div>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="page">
                        <main>
                            <div id="details" class="clearfix">
                                <div id="client">
                                    <div class="to">INVOICE TO:</div>
                                    <h2 class="name">{{ $sale['client_name'] }}</h2>
                                    @if (!empty($sale['client_adr']))
                                        <div class="address">{{ $sale['client_adr'] }}</div>
                                    @endif
                                    @if (!empty($sale['client_phone']))
                                        <div class="email">{{ $sale['client_phone'] }}</div>
                                    @endif
                                    @if (!empty($sale['client_email']))
                                        <div class="email"><a href="mailto:{{ $sale['client_email'] }}">{{ $sale['client_email'] }}</a>
                                        </div>
                                    @endif
                                </div>
                                <div id="invoice">
                                    <h1>INVOICE {{ substr($sale['Ref'], 3) }}</h1>
                                    <div class="date">Date: {{ date('M d, Y', strtotime($sale['date'])) }}</div>
                                </div>
                            </div>
                            <table border="0" cellspacing="0" cellpadding="0" class="table">
                                <thead>
                                    <tr>
                                        <th class="no">#</th>
                                        <th class="desc">DESCRIPTION</th>
                                        <th class="unit">UNIT PRICE</th>
                                        <th class="qty">QUANTITY</th>
                                        <th class="total">TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $subtotal = 0;
                                        $tdiscount = 0;
                                    @endphp
                                    @foreach ($details as $detail)
                                        <tr>
                                            <td class="no">{{ $loop->iteration }}</td>
                                            <td class="desc">
                                                <h3>{{ $detail['name'] }}</h3> @if (!empty($detail['imei_number'])) S/N: {{ $detail['imei_number'] }} @endif
                                            </td>
                                            <td class="unit"> {{ $detail['Unit_price'] + 0 }} {{ $symbol }}</td>
                                            <td class="qty"> {{ $detail['quantity'] + 0 }} {{ $detail['unitSale'] }}</td>
                                            <td class="total"> {{ $detail['DiscountNet'] + $detail['Net_price'] }} {{ $symbol }}</td>
                                        </tr>
                                        @php
                                            $subtotal = $subtotal + $detail['DiscountNet'] + $detail['Net_price'];
                                            $tdiscount = $tdiscount + $detail['DiscountNet'];
                                        @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td colspan="2">SUBTOTAL</td>
                                        <td>{{ $subtotal }} {{ $symbol }}</td>
                                    </tr>
                                    @if ($tdiscount+$sale['discount'] > 0)
                                        <tr>
                                            <td colspan="2"></td>
                                            <td colspan="2">Discount</td>
                                            <td>{{ $tdiscount+$sale['discount'] }} {{ $symbol }}</td>
                                        </tr>
                                    @endif
                                    @if ($sale['shipping'] > 0)
                                        <tr>
                                            <td colspan="2"></td>
                                            <td colspan="2">Shipping</td>
                                            <td>{{ $sale['shipping'] }} {{ $symbol }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="2" style="text-align: left; color: inherit;">Thank you!</td>
                                        <td colspan="2">GRAND TOTAL</td>
                                        <td>{{ $sale['GrandTotal']+0 }} {{ $symbol }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                            <div id="notices">
                                <h2>Terms and Conditions:</h2>
                                <div class="notice">{!! $setting->invoice_footer !!}</div>
                            </div>
                        </main>
                    </div>
                </td>
            </tr>
        </tbody>
        <tfoot style="background-color: white;">
            <tr>
                <td>
                    <!--place holder for the fixed-position footer-->
                    <div class="page-footer-space"></div>
                </td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
