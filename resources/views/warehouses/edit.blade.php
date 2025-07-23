@extends('adminlte::page')

@section('title', 'Warehouse')

@section('content_header')
<h1>Warehouse - {{ $warehouse->spk_number }}</h1>
@stop

@section('content')
<form id="editWarehouseForm">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="step1-tab" data-toggle="tab" href="#step1" role="tab" aria-controls="step1" aria-selected="true">General Information</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="step2-tab" data-toggle="tab" href="#step2" role="tab" aria-controls="step2" aria-selected="false">Person In Charge</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="step3-tab" data-toggle="tab" href="#step3" role="tab" aria-controls="step3" aria-selected="false">Numbering</a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content" id="myTabContent">
        <!-- Step 1 -->
        <div class="tab-pane fade show active" id="step1" role="tabpanel" aria-labelledby="step1-tab">
            <div class="form-group">
                <label for="owner">Owner</label>
                <input type="text" class="form-control" id="owner" name="owner" value="{{$warehouse->owner}}" required/>
                <div class="invalid-feedback">Owner is required.</div>
            </div>
            <div class="form-group">
                <label for="project">Project</label>
                <input type="text" class="form-control" id="project" name="project" value="{{$warehouse->project}}" required/>
                <div class="invalid-feedback">Project is required.</div>
            </div>
            <div class="form-group">
                <label for="code">Kode Gudang</label>
                <input type="text" class="form-control" id="code" name="code" value="{{$warehouse->code}}" required/>
                <div class="invalid-feedback">Kode Gudang is required.</div>
            </div>
            <div class="form-group">
                <label for="spk_number">Kode Proyek</label>
                <input type="text" class="form-control" id="spk_number" name="spk_number" value="{{$warehouse->spk_number}}" required/>
                <div class="invalid-feedback">Kode Proyek is required.</div>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" class="form-control" id="location" name="location" value="{{$warehouse->location}}" required/>
                <div class="invalid-feedback">Location is required.</div>
            </div>
        </div>

        <!-- Step 2 -->
        <div class="tab-pane fade" id="step2" role="tabpanel" aria-labelledby="step2-tab">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="logistic">Logistic</label>
                        <input type="text" class="form-control" id="logistic" name="logistic" value="{{$warehouse->logistic}}"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="supervisor">Supervisor</label>
                        <input type="text" class="form-control" id="supervisor" name="supervisor" value="{{$warehouse->supervisor}}"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="site_manager">Site Manager</label>
                        <input type="text" class="form-control" id="site_manager" name="site_manager" value="{{$warehouse->site_manager}}"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="project_manager">Project Manager</label>
                        <input type="text" class="form-control" id="project_manager" name="project_manager" value="{{$warehouse->project_manager}}"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="head_logistic">Head Logistic</label>
                        <input type="text" class="form-control" id="head_logistic" name="head_logistic" value="{{$warehouse->head_logistic}}"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="branch_manager">Branch Manager</label>
                        <input type="text" class="form-control" id="branch_manager" name="branch_manager" value="{{$warehouse->branch_manager}}" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="site_engineer">Site Engineer</label>
                        <input type="text" class="form-control" id="site_engineer" name="site_engineer"  value="{{$warehouse->site_engineer}}" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="asset_controller">Asset Controller</label>
                        <input type="text" class="form-control" id="asset_controller" name="asset_controller"  value="{{$warehouse->asset_controller}}" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="head_purchasing">Head Purchasing</label>
                        <input type="text" class="form-control" id="head_purchasing" name="head_purchasing"  value="{{$warehouse->head_purchasing}}" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="project_management">Project Management</label>
                        <input type="text" class="form-control" id="project_management" name="project_management"  value="{{$warehouse->project_management}}" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3 -->
        <div class="tab-pane fade" id="step3" role="tabpanel" aria-labelledby="step3-tab">
            <div class="form-group">
                <label for="sequence_format">Sequence Format</label>
                <input type="text" class="form-control" id="sequence_format" name="sequence_format" value="{{$warehouse->sequence_format}}">
            </div>
        </div>
        <button type="submit" class="btn btn-success mt-3">Save Changes</button>
    </div>
</form>

@stop

@section('css')

<style>
    .tab-content {
        padding: 15px;
    }
</style>
@stop

@section('js')

<script>
    $(document).ready(function() {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });
        $('#editWarehouseForm input').on('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });

        $('#editWarehouseForm').submit(function(event) {
            event.preventDefault();

            // Perform form validation and submission logic here
            var formData = $(this).serialize();
            // Make an AJAX call to submit the form data to the server
            $.ajax({
                url: '{{ route('warehouse.update', $warehouse->id) }}', // Replace with your update route
                type: 'PUT',
                data: formData,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.msg,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000
                    });
                    // Redirect or perform other actions on success
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