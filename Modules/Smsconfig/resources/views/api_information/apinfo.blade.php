@extends('layouts/layoutMaster')

@section('title', ' API Information')

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
        <p class="text-muted">{{ request()->getSchemeAndHttpHost() }}/api/v1/smsapi</p>

        <h6 class="fw-bold">Request Body:</h6>
        <pre class="bg-light p-3 border rounded">
{
    "api_key": "{{$api_key}}",
    "type": "text",
    "senderid": "{{$senderId}}",
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

                  <pre class="bg-danger text-white p-3 border rounded">
{
    "error": true,
    "error_message": "Insufficient balance",
    "error_code": 1020
}
                          </pre>
      </div>


      <div class="card-body mt-5">
        <h6 class="fw-bold">Get Status API Endpoint (GET METHOD):</h6>
        <p class="text-muted">{{ request()->getSchemeAndHttpHost() }}
          /api/v1/send-sms-status?message_id=633352757&api_key={{ $api_key }}</p>
        <h5>OR</h5>
        <p class="text-muted">{{ request()->getSchemeAndHttpHost() }}/api/v1/send-sms-status</p>

        <h6 class="fw-bold">Request Parameters:</h6>

        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
            <tr>
              <th>Key</th>
              <th>Value</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td>message_id</td>
              <td>458829848</td>
            </tr>
            <tr>
              <td>api_key</td>
              <td>{{ $api_key }}</td>
              <td>
                <button class="btn btn-warning btn-sm update-btn">Generate Key</button>
              </td>
            </tr>
            </tbody>
          </table>
        </div>

        <br />
        <h6 class="fw-bold">Success Response:</h6>
        <pre class="bg-success text-white p-3 border rounded">
{
    "status": "success",
    "message_id": "81755690907846727",
    "data": [
        {
            "phone": "01629334432",
            "status": "Delivered",
            "delivery time": "2025-08-20 17:55:07"
        },
        {
            "phone": "01734183130",
            "status": "Undelivered",
            "delivery time": "2025-08-20 17:55:07"
        }
    ]
}

          </pre>
      </div>


      <div class="card-body mt-5">
        <h6 class="fw-bold">Get User Information API Endpoint (GET METHOD):</h6>
        <p class="text-muted">{{ request()->getSchemeAndHttpHost() }}/api/v1/user-info?api_key={{ $api_key }}</p>

        <h6 class="fw-bold">Request Parameters:</h6>

        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
            <tr>
              <th>Key</th>
              <th>Value</th>
            </tr>
            </thead>
            <tbody>
              <tr>
                <td>api_key</td>
                <td>{{ $api_key }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <br />
        <h6 class="fw-bold">Success Response:</h6>
        <pre class="bg-success text-white p-3 border rounded">
{
    "error": false,
    "user": {
        "id": 45,
        "name": "Mizanur rahaman",
        "username": "engrmukul123456",
        "mobile": "01734183130",
        "email": "engrmukul123456@hotmail.com",
        "api_key": "$2y$12$5o90g75qQ13WH0ZNxPN2U.p03QtMPec0h69kYDQbtOYzduHYXyXQG",
        "address": "Dhaka Bangladesh",
        "my_sms_nonmasking_rate": "0.35",
        "my_sms_masking_rate": "0.53",
        "sms_rate_list": [
            {
                "id": 40,
                "rate_name": "test rate",
                "masking_rate": "0.23",
                "nonmasking_rate": "0.900000"
            },
            {
                "id": 41,
                "rate_name": "Test rate 02",
                "masking_rate": "0.45",
                "nonmasking_rate": "0.340000"
            }
        ],
        "sms_senderId_list": [
            {
                "id": 56,
                "senderID": "1200000000022"
            },
            {
                "id": 57,
                "senderID": "1200000000023"
            },
            {
                "id": 58,
                "senderID": "1200000000024"
            },
            {
                "id": 59,
                "senderID": "1200000000025"
            },
            {
                "id": 60,
                "senderID": "1200000000026"
            },
            {
                "id": 61,
                "senderID": "1200000000027"
            },
            {
                "id": 62,
                "senderID": "1200000000028"
            },
            {
                "id": 63,
                "senderID": "1200000000029"
            }
        ],
        "sms_mask_list": [
            {
                "id": 1,
                "mask": "MTRONET"
            }
        ]
    }
}
          </pre>

        <h6 class="fw-bold">Failed Response:</h6>
        <pre class="bg-danger text-white p-3 border rounded">
{
    "error": true,
    "error_message": "User not found",
    "error_code": 404
}
       </pre>
      </div>



  @if(auth()->user()->id_user_group == 2)
  <div class="card-body mt-5">
        <h6 class="fw-bold">Customer Create API Endpoint  (POST METHOD):</h6>
        <p class="text-muted">{{ request()->getSchemeAndHttpHost() }}/api/v1/user-create</p>

        <h6 class="fw-bold">Request Body:</h6>
        <pre class="bg-light p-3 border rounded">
{
    "api_key": "{{$api_key}}",
    "name": "Test User",
    "username": "test12345",
    "mobile": "01734183130",
    "email": "test12345@gmail.com",
    "password": "12345678",
    "address": "Test address",
    "sms_rate_id": 40,
    "sms_senderId": 55
}
                          </pre>

                  <h6 class="fw-bold">Success Response:</h6>
                  <pre class="bg-success text-white p-3 border rounded">
{
    "error": false,
    "message": "User created successfully",
    "user": {
        "id": 50,
        "name": "Test user",
        "username": "test12345",
        "mobile": "01734183130",
        "email": "test12345@gmail.com",
        "api_key": "$2y$12$vQdKaG9YQGk0ksfHrRoNLeJ3mcmwmIwAlFfeEuSVxRVnBRk5PFJNe",
        "address": "Test address",
        "sms_nonmasking_rate": "0.35",
        "sms_masking_rate": "0.53",
        "sms_mask": null,
        "sms_senderId": [
            {
                "id": 55,
                "senderID": "1200000000021"
            }
        ]
    }
}
                          </pre>

                  <h6 class="fw-bold">Failed Response:</h6>
                  <pre class="bg-danger text-white p-3 border rounded">
{
    "message": "The username has already been taken.",
    "errors": {
        "username": [
            "The username has already been taken."
        ],
        "email": [
            "The email has already been taken."
        ]
    }
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




  <div class="card-body mt-5">
        <h6 class="fw-bold">Customer Update API Endpoint  (PUT METHOD):</h6>
        <p class="text-muted">{{ request()->getSchemeAndHttpHost() }}/api/v1/user-update/{customer id}</p>

        <h6 class="fw-bold">Request Body:</h6>
        <pre class="bg-light p-3 border rounded">
{
    "api_key": "{{$api_key}}",
    "name": "Test User",
    "username": "test12345",
    "mobile": "01734183130",
    "email": "test12345@gmail.com",
    "password": "12345678",
    "address": "Test address",
    "sms_rate_id": 40,
    "sms_senderId": 55
}
                          </pre>

                  <h6 class="fw-bold">Success Response:</h6>
                  <pre class="bg-success text-white p-3 border rounded">
{
    "error": false,
    "message": "User updated successfully",
    "user": {
        "id": 49,
        "name": "Test User",
        "username": "test12345",
        "mobile": "01733333333",
        "email": "test12345@gmail.com",
        "address": "Test address",
        "sms_nonmasking_rate": "0.35",
        "sms_masking_rate": "0.53",
        "sms_mask": null,
        "sms_senderId": [
            {
                "id": 55,
                "senderID": "1200000000021"
            }
        ]
    }
}
                          </pre>

                  <h6 class="fw-bold">Failed Response:</h6>
                  <pre class="bg-danger text-white p-3 border rounded">
{
    "message": "The username has already been taken.",
    "errors": {
        "username": [
            "The username has already been taken."
        ],
        "email": [
            "The email has already been taken."
        ]
    }
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
      @endif




      <div class="card-body mt-5">
        <h6 class="fw-bold">Get Balance API Endpoint (GET METHOD):</h6>
        <p class="text-muted">{{ request()->getSchemeAndHttpHost() }}/api/v1/balance?api_key={{ $api_key }}</p>

        <h6 class="fw-bold">Request Parameters:</h6>

        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
            <tr>
              <th>Key</th>
              <th>Value</th>
            </tr>
            </thead>
            <tbody>
              <tr>
                <td>api_key</td>
                <td>{{ $api_key }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <br />
        <h6 class="fw-bold">Response:</h6>
        <pre class="bg-success text-white p-3 border rounded">
{
    "error": false,
    "message": "Your current balance is 9404.52 Taka",
    "balance": 9404.52
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
      </div>


      <div class="card-body mt-5">
        <h6 class="fw-bold">Get Incoming Messages List API Endpoint (GET METHOD):</h6>
        <p class="text-muted">{{ request()->getSchemeAndHttpHost() }}/api/v1/incoming-messages-list?api_key={{ $api_key }}</p>

        <h6 class="fw-bold">Request Parameters:</h6>

        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
            <tr>
              <th>Parameter</th>
              <th>Type</th>
              <th>Required</th>
              <th>Description</th>
            </tr>
            </thead>
            <tbody>
              <tr>
                <td>api_key</td>
                <td>String</td>
                <td>Yes</td>
                <td>Your API key for authentication</td>
              </tr>
            </tbody>
          </table>
        </div>

        <br />
        <h6 class="fw-bold">Success Response:</h6>
        <pre class="bg-success text-white p-3 border rounded">
{
    "error": false,
    "messages": [
        {
            "id": 10,
            "sender": "8801629334432",
            "operator_prefix": null,
            "receiver": "8809612342030",
            "message": "Testing sms for incoming ",
            "smscount": 0,
            "part_no": 0,
            "total_parts": 0,
            "reference_no": 0,
            "read": 0,
            "created_at": "2025-07-31 11:21:15",
            "updated_at": null
        },
        {
            "id": 8,
            "sender": "8801887989743",
            "operator_prefix": 18,
            "receiver": "8809612342030",
            "message": "Hello test sms robi to metronet",
            "smscount": 0,
            "part_no": 0,
            "total_parts": 0,
            "reference_no": 0,
            "read": 0,
            "created_at": "2025-06-03 10:22:11",
            "updated_at": null
        }
    ]
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


      <div class="card-body mt-5">
        <h6 class="fw-bold">Payment Initiation API Endpoint (POST METHOD):</h6>
        <p class="text-muted">{{ request()->getSchemeAndHttpHost() }}/api/initiate-payment</p>

        <h6 class="fw-bold">Request Body:</h6>
        <pre class="bg-light p-3 border rounded">
{
    "api_key": "{{$api_key}}",
    "amount": 100.50,
    "callback_url": "https://xyz.com"
}
                          </pre>

        <h6 class="fw-bold">Request Parameters:</h6>

        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
            <tr>
              <th>Parameter</th>
              <th>Type</th>
              <th>Required</th>
              <th>Description</th>
            </tr>
            </thead>
            <tbody>
              <tr>
                <td>api_key</td>
                <td>String</td>
                <td>Yes</td>
                <td>Your API key for authentication</td>
              </tr>
              <tr>
                <td>amount</td>
                <td>Numeric</td>
                <td>Yes</td>
                <td>Payment amount (minimum: 10)</td>
              </tr>
              <tr>
                <td>callback_url</td>
                <td>String</td>
                <td>Yes</td>
                <td>Callback URL for redirection after payment. Use "https://xyz.com" for dashboard redirect or your custom URL</td>
              </tr>
            </tbody>
          </table>
        </div>

        <br />
        <h6 class="fw-bold">Success Response:</h6>
        <pre class="bg-success text-white p-3 border rounded">
{
    "payment_url": "https://sandbox.sslcommerz.com/gwprocess/v4/gw.php?Q=PAY&SESSIONKEY=ABCD1234567890"
}
       </pre>

        <h6 class="fw-bold">Final Response:</h6>
        <div class="alert alert-info">
          <ul>
            <li><strong>Success:</strong> <code>https://xyz.com?status=success</code></li>
            <li><strong>Failure:</strong> <code>https://xyz.com?status=failed</code></li>
            <li><strong>Cancel:</strong> <code>https://xyz.com?status=cancelled</code></li>
          </ul>
        </div>

        <h6 class="fw-bold">Failed Response:</h6>
        <pre class="bg-danger text-white p-3 border rounded">
{
    "error": "Invalid API key"
}
       </pre>
       
        <pre class="bg-danger text-white p-3 border rounded">
{
    "message": "The amount field is required.",
    "errors": {
        "amount": [
            "The amount field is required."
        ],
        "callback_url": [
            "The callback url field is required."
        ]
    }
}
       </pre>
       
      </div>

    </div>
  </div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  /*document.querySelector('.update-btn').addEventListener('click', function () {
      fetch('/developer/update-api-key', {
          method: 'POST',
          headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          },
      })
      .then(response => response.json())
      .then(data => {
          alert(data.message);
          location.reload();
      })
      .catch(error => console.error('Error:', error));
  });*/
</script>

<script>
  $(document).on('click', '.update-btn', function() {

    // AJAX request to Laravel controller
    $.ajax({
      url: '/developer/update-api-key',
      type: 'POST',
      data: {
        _token: '{{ csrf_token() }}' // Add CSRF token for security
      },
      success: function(response) {
        alert(response.message); // Show success message
        location.reload();       // Reload the page or update the table dynamically
      },
      error: function(xhr) {
        alert('Error: ' + xhr.responseText); // Show error message
      }
    });
  });
</script>
