@extends('layouts/layoutMaster')

@section('title', ' Vertical Layouts - Forms')

<!-- Vendor Styles -->
@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/jquery-timepicker/jquery-timepicker.scss',
    'resources/assets/vendor/libs/pickr/pickr-themes.scss',
    'resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.scss',
  ])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/cleavejs/cleave.js',
    'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
    'resources/assets/vendor/libs/moment/moment.js',
    'resources/assets/vendor/libs/flatpickr/flatpickr.js',
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js',
    'resources/assets/vendor/libs/jquery-timepicker/jquery-timepicker.js',
    'resources/assets/vendor/libs/pickr/pickr.js'
  ])
@endsection

<!-- Page Scripts -->
@section('page-script')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
  @vite(['resources/assets/js/form-layouts.js', 'resources/js/message-management.js', 'resources/js/banglaType.js'])
@endsection
@php
    use Illuminate\Support\Facades\Session;
@endphp
@section('content')
        <style>
  
        h5, h6 {
            font-size: 1.3rem; /* Increased header sizes */
        }
        pre {
            font-size: 1.1rem; /* Slightly larger preformatted text */
        }
        .text-muted {
            font-size: 1.3rem; /* Increased font size for muted text */
        }
    </style>
        <div class="container my-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 text-white">API Documentation</h5>
            </div>
            <div class="card-body mt-5">
                <h6 class="fw-bold">API Endpoint:</h6>
                <p class="text-muted">http://116.193.222.198:7002/api/v1/smsapi</p>

                <h6 class="fw-bold">Request Body:</h6>
                <pre class="bg-light p-3 border rounded">
{
    "api_key": "2y10RgWRjG7QKLAxqXp31FomeOWwsXBnbK77OuPdOUoaIxLxdrIrI06y3",
    "type": "text",
    "senderid": "8809612659965",
    "msg": "Hello",
    "numbers": "01629334432,01734183130"
}
                </pre>

                <h6 class="fw-bold">Success Response:</h6>
                <pre class="bg-success text-white p-3 border rounded">
{
    "error": false,
    "message_id": "31732181378868950",
    "message": "Your SMS is Submitted"
}
                </pre>

                <h6 class="fw-bold">Failed Response:</h6>
                <pre class="bg-danger text-white p-3 border rounded">
{
    "error": true,
    "error_message": "API Key does not matched",
    "error_code": 1003
}
                </pre>
                <pre class="bg-danger text-white p-3 border rounded">
{
    "error": true,
    "error_message": "Something Wrong",
    "error_code": 1015
}
                </pre>
            </div>
      
        </div>
    </div>
@endsection
