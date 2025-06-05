<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Reservation Invoice</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
    <style>
        @media print {
            @page {
                size: A3;
            }
            .no-print {
                display: none !important;
            }
        }

        ul {
            padding: 0;
            margin: 0 0 1rem 0;
            list-style: none;
        }

        body {
            font-family: "Inter", sans-serif;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid silver;
        }

        th, td {
            text-align: right;
            padding: 8px;
        }

        h1, h4, p {
            margin: 0;
        }

        .container {
            padding: 20px 0;
            max-width: 1000px;
            width: 90%;
            margin: 0 auto;
        }

        .inv-title {
            padding: 10px;
            border: 1px solid silver;
            text-align: center;
            margin-bottom: 30px;
        }

        .inv-logo {
            width: 150px;
            display: block;
            margin: 0 auto 40px auto;
        }

        .inv-header {
            display: flex;
            margin-bottom: 20px;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .inv-header > div {
            flex: 1;
            min-width: 250px;
        }

        .inv-header h2 {
            font-size: 20px;
            margin-bottom: 0.3rem;
        }

        .inv-header ul li {
            font-size: 15px;
            padding: 3px 0;
        }

        .inv-body table th,
        .inv-body table td {
            text-align: left;
        }

        .inv-body {
            margin-bottom: 30px;
        }

        .inv-footer {
            display: flex;
            justify-content: flex-end;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .inv-footer > div {
            min-width: 250px;
        }

        .inv-footer table th {
            text-align: left;
        }

        .inv-footer table td {
            text-align: right;
        }

        .total-highlight {
            color: #003cff;
            font-weight: bold;
        }

        .no-print {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            background-color: #003cff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #0026b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="inv-title">
            <h1>Invoice # {{ rand() }}__{{ $reservation->id }}</h1>
        </div>

        <p style="margin-bottom: 30px; font-weight: bold;">
            You can go to any of our stores near you and present your reservation invoice (digital or printed) and then pay and get your car.
        </p>

        <div class="inv-header">
            <div>
                <h2 style="color: #003cff;">Rent Car</h2>
                <ul>
                    <li>Jordan</li>
                    <li>Amman</li>
                    <li>+212637998660 | contact.ragadnofal2001@gmail.com</li>
                </ul>
            </div>
            <div>
                <h2>Client</h2>
                <ul>
                    <li>{{ $reservation->user->name }}</li>
                    <li>{{ $reservation->user->email }}</li>
                </ul>
            </div>
        </div>

        <div class="inv-body">
            <table>
                <thead>
                    <tr>
                        <th>Car</th>
                        <th>Start date</th>
                        <th>End date</th>
                        <th>Duration</th>
                        <th>Price per day</th>
                        <th>Reservation price</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <h4>{{ $reservation->car->brand }} {{ $reservation->car->model }}</h4>
                            <p>{{ $reservation->car->engine }}</p>
                        </td>
                        <td>{{ $reservation->start_date }}</td>
                        <td>{{ $reservation->end_date }}</td>
                        <td>{{ $reservation->days }}</td>
                        <td>{{ number_format($reservation->price_per_day, 2) }} $</td>
                        <td>{{ number_format($reservation->total_price, 2) }} $</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="inv-footer">
            <div><!-- Optional space for notes or signature --></div>
            <div>
                <table>
                    <tr>
                        <th class="total-highlight">Total pay</th>
                        <td class="total-highlight">{{ number_format($reservation->total_price, 2) }} $</td>
                    </tr>
                </table>
            </div>
        </div>

        <h3 style="text-align: center; margin-top: 30px;">Thank you for choosing and trusting our car company ❤️</h3>
    </div>

    <div class="no-print">
        <a href="#" class="btn" onclick="downloadPDF(); return false;">📄 Download PDF</a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function downloadPDF() {
            const element = document.querySelector('.container');
            html2pdf().from(element).save('invoice.pdf');
        }

        window.addEventListener('load', function () {
            window.print();
            setTimeout(() => window.close(), 1000);
        });
    </script>
</body>

</html>


