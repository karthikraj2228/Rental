<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #INV-{{ $rent->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
.invoice {
    width: 794px; /* A4 width @ 96dpi */
    max-width: 794px;
}

/* Mobile scaling */
@media (max-width: 768px) {
    .invoice {
        transform: scale(0.85);
        transform-origin: top center;
    }
}

@media print {
    .invoice {
        transform: none;
        width: 794px;
    }
}
</style>

</head>
<body class="bg-gray-100 p-8">
    
    <div id="invoice" class="invoice mx-auto bg-white p-12 shadow-lg rounded-xl">
        <!-- Header -->
        <div class="flex flex-row justify-between items-start mb-12">

            <div>
                <h1 class="text-2xl font-bold text-gray-900">INVOICE</h1>
                <p class="text-gray-500 text-sm mt-1">#INV-{{ str_pad($rent->id, 5, '0', STR_PAD_LEFT) }}</p>
                <span class="inline-block mt-2 px-3 py-1 bg-{{ $rent->status == 'paid' ? 'green' : 'yellow' }}-100 text-{{ $rent->status == 'paid' ? 'green' : 'yellow' }}-800 rounded-full text-xs font-bold uppercase">
                    {{ $rent->status }}
                </span>
            </div>
            <div class="text-right">
                <h2 class="font-bold text-xl text-blue-600">Varalakshmi</h2>
                <h2 class="font-bold text-xl text-blue-600">Month: {{ \Carbon\Carbon::parse($rent->month)->format('M Y') }}</h2>
                <!-- <p class="text-gray-500 text-sm mt-1">123 Management St,<br>City, State, 12345</p> -->
            </div>
        </div>

        <!-- Bill To -->
        <div class="mb-12 border-b border-gray-100 pb-8">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Bill To</h3>
            <h4 class="font-bold text-gray-900 text-lg">{{ $rent->tenant->name }}</h4>
            <p class="text-gray-600">{{ $rent->room->house->name }}, Room {{ $rent->room->room_no }}</p>
            <p class="text-gray-600">{{ $rent->room->house->address }}</p>
            <p class="text-gray-500 text-sm mt-2">Email: {{ $rent->tenant->email ?? 'N/A' }}</p>
            <p class="text-gray-500 text-sm">Phone: {{ $rent->tenant->phone }}</p>
        </div>

        <!-- Line Items -->
        <table class="w-full mb-12">
            <thead>
                <tr class="border-b-2 border-gray-200">
                    <th class="text-left font-bold text-gray-600 pb-4">Description</th>
                    <th class="text-right font-bold text-gray-600 pb-4">Amount</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @if($type=='Rent')
                <tr class="border-b border-dashed border-gray-100">
                    <td class="py-4">
                        <span class="block font-medium">Monthly Rent</span>
                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($rent->month)->format('F Y') }}</span>
                    </td>
                    <td class="text-right py-4">₹{{ number_format($rent->rent_amount, 2) }}</td>
                </tr>
                @endif
                <tr class="border-b border-dashed border-gray-100">
                    <td class="py-4">
                        <span class="block font-medium">Electricity Charges</span>
                        <span class="text-xs text-gray-500">{{ $rent->to_unit - $rent->from_unit }} units @ consumed</span>
                    </td>
                    <td class="text-right py-4">₹{{ number_format($rent->eb_amount, 2) }}</td>
                </tr>
                 <tr class="border-b border-gray-200">
                    <td class="py-4">
                        <span class="block font-medium">Maintenance</span>
                    </td>
                    <td class="text-right py-4">₹{{ number_format($rent->maintenance_amount, 2) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td class="pt-6 text-right font-bold text-gray-900 text-xl">Total</td>
                    <td class="pt-6 text-right font-bold text-gray-900 text-xl">₹{{ number_format($rent->total_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div class="text-center">
            <p class="text-sm text-gray-500 mb-6">Thank you.</p>
            <button onclick="window.print()" class="no-print bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition shadow-lg">
                Print Invoice
            </button>
            <button class="no-print bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700 transition shadow-lg" onclick="shareInvoice()">
                Share Invoice
            </button>
        </div>
    </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
async function shareInvoice() {

    const element = document.getElementById("invoice");

    const fileName = "{{ $rent->tenant->name }} -{{ \Carbon\Carbon::parse($rent->month)->format('F-Y') }}.pdf";

    const opt = {
    margin: 0,
    filename: fileName,
    image: { type: 'jpeg', quality: 1 },
    html2canvas: {
        scale: 3,
        useCORS: true,
        windowWidth: 794
    },
    jsPDF: {
        unit: 'px',
        format: [794, 1123], // A4 exact size
        orientation: 'portrait'
    }
};


    const pdfBlob = await html2pdf().set(opt).from(element).outputPdf('blob');

    const file = new File([pdfBlob], fileName, { type: "application/pdf" });

    if (navigator.share) {
        await navigator.share({
            files: [file],
            title: "Invoice",
            text: "Invoice for {{ \Carbon\Carbon::parse($rent->month)->format('F Y') }}"
        });
    } else {
        alert("Sharing not supported on this browser.");
    }
}
</script>

</body>
 
</html>
