<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9fafb; color: #111827; margin: 0; padding: 20px; }
        .container { max-w-[600px] margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); }
        .header { margin-bottom: 30px; border-bottom: 2px solid #f3f4f6; padding-bottom: 20px; }
        .title { font-size: 24px; font-weight: bold; color: #111827; margin: 0; }
        .subtitle { font-size: 14px; color: #6b7280; margin-top: 5px; }
        table { w-full; border-collapse: collapse; margin-bottom: 30px; }
        td { padding: 12px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
        .label { color: #6b7280; font-weight: bold; width: 40%; }
        .value { color: #111827; font-weight: normal; }
        .footer { text-align: center; margin-top: 40px; padding-top: 20px; border-top: 2px solid #f3f4f6; }
        .link { color: #3b82f6; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="title">Incident Report Confirmation</h1>
            <p class="subtitle">Your report has been securely received by the STRAW Head and CSC President.</p>
        </div>

        <table>
            <tr>
                <td class="label">Incident report issued by:</td>
                <td class="value">{{ $report->first_name }} {{ $report->last_name }}</td>
            </tr>
            <tr>
                <td class="label" style="text-transform: uppercase; color: #ea580c;">CASE NUMBER</td>
                <td class="value" style="font-weight: bold; color: #ea580c;">{{ $report->case_number }}</td>
            </tr>
            <tr>
                <td class="label">Email</td>
                <td class="value">{{ $report->email }}</td>
            </tr>
            <tr>
                <td class="label">Phone Number</td>
                <td class="value">{{ $report->phone_number }}</td>
            </tr>
            <tr>
                <td class="label">Year and Block</td>
                <td class="value">{{ $report->year_and_block }}</td>
            </tr>
            <tr>
                <td class="label">Nature of Incident</td>
                <td class="value">{{ $report->nature_of_incident }}</td>
            </tr>
            <tr>
                <td class="label">Incident details</td>
                <td class="value">{{ \Illuminate\Support\Str::limit($report->incident_details, 150) }}</td>
            </tr>
            <tr>
                <td class="label">File Upload</td>
                <td class="value">{{ $report->file_upload_path ? 'Evidence Attached' : 'None' }}</td>
            </tr>
        </table>

        <div class="footer">
            <p style="font-size: 14px; color: #4b5563; margin-bottom: 10px;">For updates you can check this link:</p>
            {{-- We will create this route next! --}}
            <a href="{{ url('/track?case=' . $report->case_number) }}" class="link">
                Track Case {{ $report->case_number }}
            </a>
        </div>
    </div>
</body>
</html>