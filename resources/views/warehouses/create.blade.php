@extends('adminlte::page')

@section('title', 'Create Warehouse')

@section('content_header')
<h1>New Warehouse</h1>
@stop

@section('content')
<div>
    <form id="warehouseForm">
        <!-- Step 1 -->
        <div class="card">
            <div class="card-body">
                
                <div class="step active">
                    <h3>General Information</h3>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="owner">Owner</label>
                                <input type="text" class="form-control" id="owner" name="owner" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="project">Project</label>
                                <input type="text" class="form-control" id="project" name="project" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="code">Kode Gudang</label>
                                <input type="text" class="form-control" id="code" name="code" required/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="spk_number">SPK Number</label>
                                <input type="text" class="form-control" id="spk_number" name="spk_number" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="location">Location</label>
                                <textarea class="form-control" id="location" name="location" rows="6" required></textarea>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary next-step">Next</button>
                </div>
                
                <!-- Step 2 -->
                <div class="step">
                    <h3>Person In Charge</h3>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="logistic">Logistic</label>
                                <input type="text" class="form-control" id="logistic" name="logistic" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="supervisor">Supervisor</label>
                                <input type="text" class="form-control" id="supervisor" name="supervisor" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="site_manager">Site Manager</label>
                                <input type="text" class="form-control" id="site_manager" name="site_manager" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="project_manager">Project Manager</label>
                                <input type="text" class="form-control" id="project_manager" name="project_manager" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="head_logistic">Head Logistic</label>
                                <input type="text" class="form-control" id="head_logistic" name="head_logistic" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="branch_manager">Branch Manager</label>
                                <input type="text" class="form-control" id="branch_manager" name="branch_manager" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="site_engineer">Site Engineer</label>
                                <input type="text" class="form-control" id="site_engineer" name="site_engineer" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="asset_controller">Asset Controller</label>
                                <input type="text" class="form-control" id="asset_controller" name="asset_controller" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="head_purchasing">Head Purchasing</label>
                                <input type="text" class="form-control" id="head_purchasing" name="head_purchasing" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="project_management">Project Management</label>
                                <input type="text" class="form-control" id="project_management" name="project_management" required>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary prev-step">Previous</button>
                    <button type="button" class="btn btn-primary next-step">Next</button>
                </div>
                
                <!-- Step 3 -->
                <div class="step">
                    <h3>Number Format</h3>
                    <div class="form-group">
                        <label for="sequence_format">Sequence Format</label>
                        <input type="text" class="form-control" id="sequence_format" name="sequence_format" value="[CNT]/PROJECT/[MONTH]/[YEAR]" onkeyup="sampleFormat('sequence_format', 'sample_format')" required>
                    </div>
                    <div class="form-group">
                        <label for="sample_format">Example</label>
                        <input type="text" class="form-control" id="sample_format" name="sample_format" disabled readonly>
                    </div>
                    <button type="button" class="btn btn-secondary prev-step">Previous</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>
@stop

@section('css')


<style>
    .step {
        display: none;
    }
    .step.active {
        display: block;
    }
</style>
@stop

@section('js')
<script>
    // Get CSRF token from meta tag
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Add CSRF token to AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });
    sampleFormat("sequence_format", "sample_format")
    function sampleFormat(input_field, target_field){
        var format = document.getElementById(input_field).value
        var sample = document.getElementById(target_field)
        const cnt = "0001"; // User can change this value
        const now = new Date();
        const month = String(now.getMonth() + 1).padStart(2, '0'); // 2-digit month
        const year = String(now.getFullYear()).slice(-2); // Last 2 digits of the year
        const year4 = String(now.getFullYear()); // 4-digit year

        let result = format.replace("[CNT]", cnt)
                                .replace("[MONTH]", month)
                                .replace("[YEAR]", year)
                                .replace("[YEAR4]", year4); // Optional if [YEAR4] is needed


        sample.value = result
    }
    $(document).ready(function() {
        var currentStep = 0;
        var steps = $('.step');
        
        function showStep(step) {
            steps.removeClass('active');
            $(steps[step]).addClass('active');
        }

        function validateStep(step) {
            var isValid = true;
            var firstInvalid = null;
            $(steps[step]).find('input, textarea').each(function() {
                if (!this.checkValidity()) {
                    $(this).addClass('is-invalid');
                    if (isValid) {
                        firstInvalid = this;
                    }
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            if (!isValid && firstInvalid) {
                firstInvalid.focus();
            }
            return isValid;
        }

        
        $('.next-step').click(function() {
            if (validateStep(currentStep)) {
                currentStep++;
                if (currentStep >= steps.length) {
                    currentStep = steps.length - 1;
                }
                showStep(currentStep);
            }
        });
        
        $('.prev-step').click(function() {
            currentStep--;
            if (currentStep < 0) {
                currentStep = 0;
            }
            showStep(currentStep);
        });
        $('#warehouseForm input').on('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });

        $('#warehouseForm').submit(function(event) {
            event.preventDefault();
            
            // Perform form validation and submission logic here
            var formData = $(this).serialize();
            // Here you can make an AJAX call to submit the form data to the server
            $.ajax({
                url: '{{ route('warehouse.store') }}', // Replace with your store route
                type: 'POST',
                data: formData,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.msg,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1000,
                        }).then(function() {
                            window.location.href = "{{route('warehouse.index')}}";
                        });
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: "An error occurred while creating the warehouse",
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000
                    });
                }
            });
        });
    });
</script>
@stop