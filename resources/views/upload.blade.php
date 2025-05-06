<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CSV Upload Tracker</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial; margin: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        .success { color: green; }
        .failed { color: red; }
    </style>
</head>
<body>

<h2>Upload CSV File</h2>

@if(session('success'))
    <p class="success">{{ session('success') }}</p>
@endif

<form action="{{ url('/upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <button type="submit">Upload File</button>
</form>

<h3>Upload History</h3>
<div id="upload-table">
    <p>Loading...</p>
</div>

<script>
    function fetchUploads() {
        fetch("/uploads")
            .then(res => res.json())
            .then(data => {
                let html = `
                    <table>
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>File Name</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                `;
                data.forEach(row => {
                    html += `
                        <tr>
                            <td>${new Date(row.uploaded_at).toLocaleString()}</td>
                            <td>${row.file_name}</td>
                            <td class="${row.status === 'completed' ? 'success' : row.status === 'failed' ? 'failed' : ''}">${row.status}</td>
                        </tr>
                    `;
                });
                html += '</tbody></table>';
                document.getElementById('upload-table').innerHTML = html;
            });
    }

    setInterval(fetchUploads, 5000);
    fetchUploads();
</script>

</body>
</html>
