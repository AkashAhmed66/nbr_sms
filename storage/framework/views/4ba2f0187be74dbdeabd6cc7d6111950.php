<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('vendor-style'); ?>
  <?php echo app('Illuminate\Foundation\Vite')(['resources/assets/vendor/libs/apex-charts/apex-charts.scss']); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-style'); ?>
<style>
  .chart-card {
    height: 500px;
    display: flex;
    flex-direction: column;
  }
  
  .chart-card .card-body {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .chart-card .card-body > div {
    width: 100%;
    height: 100%;
  }
  
  /* Ensure equal height rows */
  .chart-row {
    display: flex;
    align-items: stretch;
  }
  
  .chart-row .col-12 {
    display: flex;
    flex-direction: column;
  }
  
  .chart-row .card {
    flex: 1;
  }
  
  /* Modal z-index fixes */
  .modal-backdrop {
    z-index: 9998 !important;
  }
  
  .modal {
    z-index: 9999 !important;
  }
  
  .modal-content {
    z-index: 10000 !important;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    border-radius: 12px;
  }
  
  /* Professional modal styling */
  .modal-header {
    border-bottom: 1px solid #e9ecef;
    padding: 1.5rem 2rem;
    background-color: #696cff;
    color: white;
    border-radius: 12px 12px 0 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  
  .modal-header .modal-title {
    font-weight: 600;
    font-size: 1.25rem;
    color: white;
    margin: 0;
  }
  
  .modal-header .btn-close {
    filter: brightness(0) invert(1);
    opacity: 0.8;
    color: white;
    margin: 0;
    padding: 0;
  }
  
  .modal-header .btn-close:hover {
    opacity: 1;
    color: white;
  }
  
  .modal-body {
    padding: 2rem;
    background-color: #f8f9fa;
  }
  
  .modal-footer {
    border-top: 1px solid #e9ecef;
    padding: 1.5rem 2rem;
    background-color: white;
    border-radius: 0 0 12px 12px;
  }
  
  .modal-dialog {
    max-width: 450px;
    margin: 1.75rem auto;
    display: flex;
    align-items: center;
    min-height: calc(100vh - 3.5rem);
  }
  
  @media (min-width: 576px) {
    .modal-dialog {
      margin: 1.75rem auto;
      display: flex;
      align-items: center;
      min-height: calc(100vh - 3.5rem);
    }
  }
  
  /* Ensure modal is perfectly centered */
  .modal.fade .modal-dialog {
    transform: translate(0, -50px);
  }
  
  .modal.show .modal-dialog {
    transform: none;
  }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('vendor-script'); ?>
  <?php echo app('Illuminate\Foundation\Vite')(['resources/assets/vendor/libs/apex-charts/apexcharts.js']); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
  <script>

    document.addEventListener('DOMContentLoaded', function() {

      // Static data for demo
      const staticData = [
        { status: 'Delivered', percentage: 45 },
        { status: 'Failed', percentage: 25 },
        { status: 'Pending', percentage: 20 },
        { status: 'Sent', percentage: 10 }
      ];

      updateChartData(staticData);

      document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', function() {
          const range = this.getAttribute('data-range');
          // Using same static data for all ranges in demo
          updateChartData(staticData);
        });
      });
    });

    function updateChartData(data) {
      statusWiseMessages = data;
      updateDonutChart(statusWiseMessages);
    }

    let donutChart = null;

    function updateDonutChart(data) {

      if (donutChart) {
        donutChart.destroy();
      }

      if (!data || data.length === 0) {
        console.error('No data provided for the chart.');
        return;
      }

      let cardColor, headingColor, labelColor, borderColor, legendColor;

      if (isDarkStyle) {
        cardColor = config.colors_dark.cardColor;
        headingColor = config.colors_dark.headingColor;
        labelColor = config.colors_dark.textMuted;
        legendColor = config.colors_dark.bodyColor;
        borderColor = config.colors_dark.borderColor;
      } else {
        cardColor = config.colors.cardColor;
        headingColor = config.colors.headingColor;
        labelColor = config.colors.textMuted;
        legendColor = config.colors.bodyColor;
        borderColor = config.colors.borderColor;
      }

      // Color constant
      const chartColors = {
        column: {
          series1: '#826af9',
          series2: '#d2b0ff',
          bg: '#f8d3ff'
        },
        donut: {
          series1: '#fdd835',
          series2: '#32baff',
          series3: '#ffa1a1',
          series4: '#7367f0',
          series5: '#29dac7'
        },
        area: {
          series1: '#ab7efd',
          series2: '#b992fe',
          series3: '#e0cffe'
        }
      };

      const labels = data.map(item => item.status);
      const series = data.map(item => item.percentage);

      const donutChartEl = document.querySelector('#donutChart');
      if (!donutChartEl) {
        console.error('Donut chart element not found!');
        return;
      }

      const donutChartConfig = {
        chart: {
          height: 390,
          fontFamily: 'Inter',
          type: 'donut'
        },
        labels: labels,
        series: series,
        colors: [
          chartColors.donut.series1,
          chartColors.donut.series3,
          chartColors.donut.series4,
          chartColors.donut.series5
        ],
        stroke: {
          show: false,
          curve: 'straight'
        },
        dataLabels: {
          enabled: true,
          formatter: function(val) {
            return parseInt(val, 10) + '%';
          },
          style: {
            fontSize: '15px',
            fontWeight: 'normal'
          }
        },
        legend: {
          show: true,
          position: 'bottom',
          fontSize: '13px',
          markers: { offsetX: -3, width: 10, height: 10 },
          itemMargin: {
            vertical: 3,
            horizontal: 10
          },
          labels: {
            colors: legendColor,
            useSeriesColors: false
          }
        },
        plotOptions: {
          pie: {
            donut: {
              labels: {
                show: true,
                name: {
                  fontSize: '2rem'
                },
                value: {
                  fontSize: '0.9375rem',
                  fontWeight: 500,
                  color: headingColor,
                  formatter: function(val) {
                    return parseInt(val, 10) + '%';
                  }
                },
                total: {
                  show: true,
                  fontSize: '0.9375rem',
                  fontWeight: 500,
                  color: headingColor,
                  label: 'Total',
                  formatter: function() {
                    return series.reduce((a, b) => a + b, 0) + '%';
                  }
                }
              }
            }
          }
        }
      };

      donutChart = new ApexCharts(donutChartEl, donutChartConfig);
      donutChart.render();
    }

  </script>

  <script>
    // Static data for demo - no external variables needed
    var monthlyStatusCounts = [
      { month: 'Jan', delivered: 1200, failed: 300, pending: 150 },
      { month: 'Feb', delivered: 1400, failed: 250, pending: 200 },
      { month: 'Mar', delivered: 1600, failed: 200, pending: 180 },
      { month: 'Apr', delivered: 1800, failed: 280, pending: 220 },
      { month: 'May', delivered: 2000, failed: 320, pending: 250 },
      { month: 'Jun', delivered: 1700, failed: 180, pending: 190 }
    ];
    var last7DaysTransections = [
      { date: '2025-08-14', amount: 1500 },
      { date: '2025-08-15', amount: 2200 },
      { date: '2025-08-16', amount: 1800 },
      { date: '2025-08-17', amount: 2500 },
      { date: '2025-08-18', amount: 1900 },
      { date: '2025-08-19', amount: 3200 },
      { date: '2025-08-20', amount: 2800 }
    ];
    var last7DaysMessages = [
      { date: '2025-08-14', count: 850 },
      { date: '2025-08-15', count: 1200 },
      { date: '2025-08-16', count: 950 },
      { date: '2025-08-17', count: 1400 },
      { date: '2025-08-18', count: 1100 },
      { date: '2025-08-19', count: 1650 },
      { date: '2025-08-20', count: 1350 }
    ];
  </script>

  <script>
    // Static charts implementation
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize all charts with static data
      initializeStaticCharts();
    });

    function initializeStaticCharts() {
      // Bar Chart for Monthly Messages
      if (document.querySelector('#barChart')) {
        const barChartConfig = {
          chart: {
            type: 'bar',
            height: 400,
            fontFamily: 'Inter'
          },
          series: [{
            name: 'Delivered',
            data: [1200, 1400, 1600, 1800, 2000, 1700]
          }, {
            name: 'Failed',
            data: [300, 250, 200, 280, 320, 180]
          }, {
            name: 'Pending',
            data: [150, 200, 180, 220, 250, 190]
          }],
          xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']
          },
          colors: ['#28a745', '#dc3545', '#ffc107'],
          plotOptions: {
            bar: {
              horizontal: false,
              columnWidth: '55%',
            },
          },
          dataLabels: {
            enabled: false
          },
          stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
          },
          fill: {
            opacity: 1
          }
        };
        const barChart = new ApexCharts(document.querySelector('#barChart'), barChartConfig);
        barChart.render();
      }

      // Line Chart for Transactions
      if (document.querySelector('#lineChart')) {
        const lineChartConfig = {
          chart: {
            type: 'line',
            height: 400,
            fontFamily: 'Inter'
          },
          series: [{
            name: 'Transaction Amount',
            data: [1500, 2200, 1800, 2500, 1900, 3200, 2800]
          }],
          xaxis: {
            categories: ['Aug 14', 'Aug 15', 'Aug 16', 'Aug 17', 'Aug 18', 'Aug 19', 'Aug 20']
          },
          colors: ['#007bff'],
          stroke: {
            curve: 'smooth',
            width: 3
          },
          markers: {
            size: 5
          },
          grid: {
            show: true
          }
        };
        const lineChart = new ApexCharts(document.querySelector('#lineChart'), lineChartConfig);
        lineChart.render();
      }

      // Line Chart for Messages
      if (document.querySelector('#lineChart2')) {
        const lineChart2Config = {
          chart: {
            type: 'line',
            height: 400,
            fontFamily: 'Inter'
          },
          series: [{
            name: 'Message Count',
            data: [850, 1200, 950, 1400, 1100, 1650, 1350]
          }],
          xaxis: {
            categories: ['Aug 14', 'Aug 15', 'Aug 16', 'Aug 17', 'Aug 18', 'Aug 19', 'Aug 20']
          },
          colors: ['#28a745'],
          stroke: {
            curve: 'smooth',
            width: 3
          },
          markers: {
            size: 5
          },
          grid: {
            show: true
          }
        };
        const lineChart2 = new ApexCharts(document.querySelector('#lineChart2'), lineChart2Config);
        lineChart2.render();
      }

      // Last 30 Days Line Chart
      if (document.querySelector('#last30DaysChart')) {
        // Load data from API
        fetch('/dashboard/line-chart')
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.json();
          })
          .then(data => {
            console.log('Chart data received:', data); // Debug log
            
            // Update chart header with dynamic date range
            if (data.start_date && data.end_date) {
              const startDate = new Date(data.start_date);
              const endDate = new Date(data.end_date);
              const startFormatted = startDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
              const endFormatted = endDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
              
              // Update the chart subtitle
              const chartSubtitle = document.querySelector('#last30DaysChart').closest('.card').querySelector('.text-muted');
              if (chartSubtitle) {
                chartSubtitle.textContent = `From ${startFormatted} to ${endFormatted}`;
              }
            }
            
            const last30DaysConfig = {
              chart: {
                type: 'line',
                height: 350,
                fontFamily: 'Inter',
                toolbar: {
                  show: true
                }
              },
              series: [{
                name: 'SMS Sent',
                data: data.data || []
              }],
              xaxis: {
                categories: data.categories || [],
                title: {
                  text: `Period: ${data.start_date ? new Date(data.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) : ''} - ${data.end_date ? new Date(data.end_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) : ''}`
                },
                labels: {
                  rotate: -45,
                  maxHeight: 120
                }
              },
              yaxis: {
                title: {
                  text: 'SMS Count'
                }
              },
              colors: ['#6c5ce7'],
              stroke: {
                curve: 'smooth',
                width: 3
              },
              markers: {
                size: 4,
                hover: {
                  size: 6
                }
              },
              grid: {
                show: true,
                borderColor: '#e7e7e7',
                strokeDashArray: 3
              },
              tooltip: {
                enabled: true,
                x: {
                  show: true
                },
                y: {
                  formatter: function(val) {
                    return val + " SMS"
                  }
                }
              },
              dataLabels: {
                enabled: false
              }
            };
            const last30DaysChart = new ApexCharts(document.querySelector('#last30DaysChart'), last30DaysConfig);
            last30DaysChart.render();
          })
          .catch(error => {
            console.error('Error loading chart data:', error);
            // Fallback to static data if API fails
            const fallbackData = [];
            const fallbackCategories = [];
            
            // Generate fallback data for current month
            const currentDate = new Date();
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const startDate = new Date(year, month, 1);
            const endDate = new Date(year, month, currentDate.getDate());
            
            for (let i = 1; i <= currentDate.getDate(); i++) {
              const date = new Date(year, month, i);
              fallbackCategories.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
              fallbackData.push(Math.floor(Math.random() * 3000) + 500); // Random data for demo
            }
            
            // Update subtitle for fallback
            const chartSubtitle = document.querySelector('#last30DaysChart').closest('.card').querySelector('.text-muted');
            if (chartSubtitle) {
              const startFormatted = startDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
              const endFormatted = endDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
              chartSubtitle.textContent = `From ${startFormatted} to ${endFormatted} (Demo Data)`;
            }
            
            const last30DaysConfig = {
              chart: {
                type: 'line',
                height: 350,
                fontFamily: 'Inter',
                toolbar: {
                  show: true
                }
              },
              series: [{
                name: 'SMS Sent',
                data: fallbackData
              }],
              xaxis: {
                categories: fallbackCategories,
                title: {
                  text: `Period: ${startDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })} - ${endDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}`
                },
                labels: {
                  rotate: -45,
                  maxHeight: 120
                }
              },
              yaxis: {
                title: {
                  text: 'SMS Count'
                }
              },
              colors: ['#6c5ce7'],
              stroke: {
                curve: 'smooth',
                width: 3
              },
              markers: {
                size: 4,
                hover: {
                  size: 6
                }
              },
              grid: {
                show: true,
                borderColor: '#e7e7e7',
                strokeDashArray: 3
              },
              tooltip: {
                enabled: true,
                x: {
                  show: true
                },
                y: {
                  formatter: function(val) {
                    return val + " SMS"
                  }
                }
              },
              dataLabels: {
                enabled: false
              }
            };
            const last30DaysChart = new ApexCharts(document.querySelector('#last30DaysChart'), last30DaysConfig);
            last30DaysChart.render();
          });
      }
    }
  </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
  <div class="row">
    <div class="container-xxl flex-grow-1 container-p-y">
    
    <!-- Balance Section -->
    <div class="row g-6 mb-5">
      <div class="col-12">
        <div class="card card-border-shadow-success h-100">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-lg-8 col-md-7">
                <div class="d-flex align-items-center mb-3">
                  <div class="avatar me-3" style="width: 64px; height: 64px;">
                    <span class="avatar-initial rounded-3 bg-label-success" style="width: 64px; height: 64px; display: flex; align-items: center; justify-content: center;">
                      <i class="ri-wallet-line" style="font-size: 36px;"></i>
                    </span>
                  </div>
                  <div>
                    <h5 class="mb-1 fw-medium">Current Balance</h5>
                    <?php
                      $balance = $userInfo->available_balance ?? 0;
                      $balanceValue = is_numeric($balance) ? (float)$balance : 0;
                      $textClass = $balanceValue < 10 ? 'text-danger' : 'text-success';
                    ?>
                    <h3 class="mb-0 <?php echo e($textClass); ?> fw-bold"><?php echo e($userInfo->available_balance ?? '৳ 0.00'); ?></h3>
                  </div>
                </div>
                <div class="d-flex align-items-center">
                  <i class="ri-message-2-line me-2 text-muted"></i>
                  <span class="text-muted">Daily SMS Requests Sent: </span>
                  <span class="fw-medium text-dark ms-1"><?php echo e($totalMessagesDay); ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/ Balance Section -->

    <!-- Card Border Shadow -->
    <div class="row g-6 mb-5">
    <div class="col-sm-6 col-lg-3">
      <div class="card card-border-shadow-primary h-100">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2">
            <div class="avatar me-4">
              <span class="avatar-initial rounded-3 bg-label-primary"><i class="ri-message-3-line ri-24px"></i></span>
            </div>
            <h4 class="mb-0"><?php echo e(($remainingNonmaskingmessageCount ?? 0) + ($remainingMaskingMessageCount ?? 0)); ?></h4>
          </div>
          <h6 class="mb-0 fw-bold text-dark" style="font-size: 1.1rem; letter-spacing: 0.5px;">Total Remaining Messages</h6>
          <div class="mt-2">
            
            <div class="d-flex justify-content-between align-items-center">
              <span class="fw-medium text-dark">Masking (Units):</span>
              <span class="badge bg-label-info"><?php echo e($remainingMaskingMessageCount ?? 0); ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="card card-border-shadow-warning h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
        <div class="avatar me-4">
          <span class="avatar-initial rounded-3 bg-label-warning"><i class="ri-message-line ri-24px"></i></span>
        </div>
        <h4 class="mb-0"><?php echo e($totalMessages); ?></h4>
        </div>
        <h6 class="mb-0 fw-bold text-dark" style="font-size: 1.1rem; letter-spacing: 0.5px;">Total Message</h6>
        <p class="mb-0">
          <small class="text-muted">This Month</small>
        </p>
      </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="card card-border-shadow-danger h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
        <div class="avatar me-4">
          <span class="avatar-initial rounded-3 bg-label-danger"><i
            class="ri-money-dollar-circle-line ri-24px"></i></span>
        </div>
        <h4 class="mb-0"><?php echo e($totalTransections); ?></h4>
        </div>
        <h6 class="mb-0 fw-bold text-dark" style="font-size: 1.1rem; letter-spacing: 0.5px;">Total Transaction</h6>
        <p class="mb-0">
          <small class="text-muted"></small>
        </p>
      </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="card card-border-shadow-info h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
        <div class="avatar me-4">
          <span class="avatar-initial rounded-3 bg-label-info"><i class="ri-time-line ri-24px"></i></span>
        </div>
        <h4 class="mb-0"><?php echo e($totalSenderId); ?></h4>
        </div>
        <h6 class="mb-0 fw-bold text-dark" style="font-size: 1.1rem; letter-spacing: 0.5px;">Total Sender ID</h6>
        <p class="mb-0">
          <small class="text-muted"></small>
        </p>
      </div>
      </div>
    </div>
    <!--/ Card Border Shadow -->
    </div>

    <!-- Charts Section -->
    
    
    <!-- Last 30 Days Line Chart -->
    <div class="row">
      <div class="col-12 mb-6">
        <div class="card">
          <div class="card-header d-flex justify-content-between">
            <div>
              <h5 class="card-title mb-0">SMS Traffic - This Month</h5>
              <small class="text-muted">Daily SMS volume trend</small>
            </div>
          </div>
          <div class="card-body">
            <div id="last30DaysChart"></div>
          </div>
        </div>
      </div>
    </div>
    <!-- /Last 30 Days Line Chart -->
    
    <!--/ Charts Section -->

    <div class="modal fade" id="payModal" tabindex="-1" aria-labelledby="payModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="#">
        <?php echo csrf_field(); ?>
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title d-flex align-items-center mb-4" id="payModalLabel">
              Add Balance to Wallet
            </h5>
            <button type="button" class="btn-close mb-2" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="text-center mb-4">
              <div class="avatar mx-auto mb-3" style="width: 80px; height: 80px;">
                <span class="avatar-initial rounded-3 bg-label-primary" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                  <i class="ri-wallet-line" style="font-size: 36px;"></i>
                </span>
              </div>
              <h6 class="mb-1">Current Balance</h6>
              <h4 class="mb-0 <?php echo e($textClass ?? 'text-success'); ?> fw-bold"><?php echo e($userInfo->available_balance ?? '৳ 0.00'); ?></h4>
            </div>
            
            <div class="card border-0 shadow-sm mb-3">
              <div class="card-body p-3">
                <div class="mb-3">
                  <label for="amount" class="form-label fw-medium text-dark">
                    <i class="ri-money-dollar-circle-line me-2"></i>
                    Enter Amount (৳)
                  </label>
                  <input type="number" 
                         class="form-control form-control-lg border-2" 
                         id="amount" 
                         name="amount" 
                         value="3000" 
                         required 
                         min="3000" 
                         step="any"
                         placeholder="Enter amount"
                         style="border-color: #dee2e6; padding: 0.75rem 1rem;">
                  <div class="form-text mt-2">
                    <i class="ri-information-line me-1"></i>
                    Minimum amount: ৳3000
                  </div>
                </div>
              </div>
            </div>
            
            
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-outline-secondary btn-lg px-4" data-bs-dismiss="modal">
              <i class="ri-close-line me-2"></i>Cancel
            </button>
            <button type="button" class="btn btn-primary btn-lg px-4 d-flex align-items-center" id="payNowBtn">
              <i class="ri-secure-payment-line me-2"></i>
              <span>Pay Now</span>
            </button>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>


  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function () {
    
    // Function to validate amount and toggle button state
    function validateAmount() {
      const amount = parseFloat($('#amount').val()) || 0;
      const payButton = $('#payNowBtn');
      
      if (amount < 3000) {
        payButton.prop('disabled', true);
        payButton.removeClass('btn-primary').addClass('btn-secondary');
        payButton.find('span').text('Pay Now');
      } else {
        payButton.prop('disabled', false);
        payButton.removeClass('btn-secondary').addClass('btn-primary');
        payButton.find('span').text('Pay Now');
      }
    }
    
    // Validate on page load
    validateAmount();
    
    // Validate on input change
    $('#amount').on('input keyup change', function() {
      validateAmount();
    });
    
    $('#payNowBtn').click(function (e) {
      e.preventDefault();
      
      // Double-check amount before processing
      const amount = parseFloat($('#amount').val()) || 0;
      if (amount < 3000) {
        alert('Minimum amount is ৳3000. Please enter a valid amount.');
        return false;
      }
      
      $('#paymentMsg').text('Processing...');
      var data = {
      api_key: "<?php echo e($api_key); ?>", // Pass from controller: return view('payment', ['api_key'=>$user->api_key]);
      amount: $('#amount').val(),
      callback_url: "metronet"
      };

      $.ajax({
      url: "<?php echo e(url('/api/initiate-payment')); ?>", // Blade will render the full URL
      type: "POST",
      contentType: "application/json",
      headers: { 'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>" }, // Laravel CSRF protection
      data: JSON.stringify(data),
      success: function (res) {
        if (res.payment_url) {
        window.location.href = res.payment_url;
        } else {
        $('#paymentMsg').text(res.message || 'Failed to initiate payment.');
        }
      },
      error: function (xhr) {
        let msg = 'An error occurred. Please try again.';
        if (xhr.responseJSON && xhr.responseJSON.message) {
        msg = xhr.responseJSON.message;
        }
        $('#paymentMsg').text(msg);
      }
      });
    });
    });

  </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts/layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\nbr\Modules/Dashboard\resources/views/superadmin.blade.php ENDPATH**/ ?>