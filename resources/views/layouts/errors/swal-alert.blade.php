@if (count($errors) > 0)
    <script>
        // Collect all error messages
        let errorMessages = "<ul>";
        @foreach ($errors->all() as $error)
            errorMessages += "<li>{{ $error }}</li>";
        @endforeach
        errorMessages += "</ul>";

        Swal.fire({
            title: 'Whoops! Terjadi Kesalahan saat proses input.',
            html: errorMessages,
            icon: 'error',
            confirmButtonText: 'OK'
        });
    </script>
@endif
@if (session('success'))
    <script>
        Swal.fire({
            title: 'Success!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'OK'
        });

    </script>
@endif
