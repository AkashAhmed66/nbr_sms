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
  <div class="row">

    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">TERMS OF SMS CONTENT</h5>
          <span>Available Balance : {{ $available_balance }} BDT</span>
          {{--<span>Expire Date: 2024-04-05 21:37:00</span>--}}
        </div>
        <div class="card-body">
          <ul>
            <li>160 Characters are counted as 1 SMS in case of English language & 70 in other language.</li>
            <li>One simple text message containing extended GSM character set (~^{}[]\|€) is of 140 characters long. Check your SMS count before pushing SMS.</li>
            <li>Check your balance before send SMS</li>
            <li>Number format must be start with 88, for example 8801727000000</li>
            <li>You may send up to 3 sms size in a single try.</li>
            <li>Separate Numbers by Comma. For example: 8801727000000,8801727000001</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="card mb-6">

        <div class="card-header overflow-hidden">
          @if(session()->has('success'))
            <div class="alert alert-success">
              {{ session()->get('success') }}
            </div>
          @endif
          @if(session()->has('error'))
            <div class="alert alert-danger">
              {{ session()->get('error') }}
            </div>
          @endif

          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#form-tabs-regular-message"
                      role="tab" aria-selected="true">
                <span class="ri-message-3-line ri-20px d-sm-none"></span><span
                  class="d-none d-sm-block">Regular Message</span></button>
            </li>
            <li class="nav-item">
              <button class="nav-link " data-bs-toggle="tab" data-bs-target="#form-tabs-dynamic-message" role="tab"
                      aria-selected="false"><span class="ri-group-line ri-20px d-sm-none"></span><span
                  class="d-none d-sm-block">Group Message</span></button>
            </li>
            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#form-tabs-file-message" role="tab"
                      aria-selected="false"><span class="ri-file-text-line ri-20px d-sm-none"></span><span
                  class="d-none d-sm-block">File Message</span></button>
            </li>
            {{-- <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#form-tabs-file-message-actual" role="tab"
                      aria-selected="false"><span class="ri-code-s-slash-line ri-20px d-sm-none"></span><span
                  class="d-none d-sm-block">Dynamic Message</span></button>
            </li> --}}
          </ul>
        </div>

        <div class="tab-content">
          <div class="tab-pane fade active show" id="form-tabs-regular-message" role="tabpanel">
            @include('messages::message.regular-message')
          </div>
          <div class="tab-pane fade" id="form-tabs-dynamic-message" role="tabpanel">
            @include('messages::message.dynamic-message')
          </div>
          <div class="tab-pane fade" id="form-tabs-file-message" role="tabpanel">
            @include('messages::message.file-message')
          </div>
          {{-- <div class="tab-pane fade" id="form-tabs-file-message-actual" role="tabpanel">
            @include('messages::message.dynamic-message-actual')
          </div> --}}
        </div>

      </div>
    </div>

  </div>
@endsection
