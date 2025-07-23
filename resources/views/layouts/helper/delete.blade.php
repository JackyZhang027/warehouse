<script>
    function confirmDelete(url, table) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        if (response.success){
                            if(table != ''){
                                $('#' + table).DataTable().ajax.reload();
                                Swal.fire(
                                    'Deleted!',
                                    'Your record has been deleted.',
                                    'success'
                                )
                            }else{
                                Swal.fire(
                                    'Deleted!',
                                    'Your record has been deleted.',
                                    'success'
                                ).then(() => {
                                    location.reload()
                                });

                            }
                        }else{
                            Swal.fire(
                                'Error!',
                                'There was a problem deleting the record.',
                                'error'
                            )
                        }

                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            'There was a problem deleting the record.',
                            'error'
                        )
                    }
                });
            }
        })
    }
</script>