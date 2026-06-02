(function () {
  var sidebar = document.getElementById('sidebar');
  var content = document.getElementById('content');
  var topbar = document.getElementById('topbar');
  var toggleBtn = document.getElementById('toggleBtn');
  var mobileBtn = document.getElementById('mobileBtn');
  var overlay = document.getElementById('overlay');

  if (toggleBtn) {
    toggleBtn.addEventListener('click', function () {
      if (sidebar) sidebar.classList.toggle('collapsed');
      if (content) content.classList.toggle('full');
      if (topbar) topbar.classList.toggle('full');
    });
  }

  if (mobileBtn) {
    mobileBtn.addEventListener('click', function () {
      if (sidebar) sidebar.classList.add('mobile-show');
      if (overlay) overlay.classList.add('show');
    });
  }

  if (overlay) {
    overlay.addEventListener('click', function () {
      if (sidebar) sidebar.classList.remove('mobile-show');
      if (overlay) overlay.classList.remove('show');
    });
  }

  var currentPage = window.location.pathname.split('/').pop() || 'index.html';
  var navLinks = document.querySelectorAll('.sidebar .nav-link');
  if (navLinks.length > 0) {
    navLinks.forEach(function (link) {
      link.classList.remove('active');
      if (link.getAttribute('href') === currentPage) {
        link.classList.add('active');
      }
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    if (typeof ApexCharts !== 'undefined') {
      if (document.getElementById('salesPurchaseChart')) {
        var spOptions = {
          series: [
            { name: 'Sales', data: [44, 55, 57, 56, 61, 58, 63, 60, 66] },
            { name: 'Purchase', data: [76, 85, 101, 98, 87, 105, 91, 114, 94] }
          ],
          colors: ['#f7a085', '#E66239'],
          chart: { type: 'bar', height: 350, width: '100%', parentHeightOffset: 0, toolbar: { show: false } },
          grid: { show: true, borderColor: '#e2e8f0' },
          legend: { show: true, fontFamily: 'Poppins, serif', fontWeight: 500, markers: { size: 5, shape: 'square', strokeWidth: 0, offsetX: -2, offsetY: 0 } },
          plotOptions: { bar: { horizontal: false, columnWidth: '85%', borderRadius: 3, borderRadiusApplication: 'end' } },
          dataLabels: { enabled: false },
          stroke: { show: false, width: 2, colors: ['transparent'] },
          xaxis: { categories: ['28 Jan', '29 Jan', '30 Jan', '31 Jan', '1 Feb', '2 Feb', '3 Feb', '4 Feb', '5 Feb'], axisBorder: { show: false }, axisTicks: { show: false } },
          yaxis: { labels: { formatter: function (e) { return e + 'k'; } }, title: { text: '$ (thousands)' } },
          fill: { opacity: 1 },
          tooltip: { y: { formatter: function (val) { return '$ ' + val + ' thousands'; } } }
        };
        new ApexCharts(document.querySelector('#salesPurchaseChart'), spOptions).render();
      }

      if (document.getElementById('customerChart')) {
        var cOptions = {
          series: [44, 55],
          chart: { height: 200, type: 'radialBar' },
          colors: ['#5BE49B', '#E66239'],
          plotOptions: {
            radialBar: {
              dataLabels: { name: { fontSize: '22px' }, value: { fontSize: '16px' }, total: { show: false } },
              hollow: { margin: 3, size: '40%', background: 'transparent' },
              track: { show: true, background: '#f0f0f0', strokeWidth: '45%', opacity: 1, margin: 5 }
            }
          },
          fill: { type: 'gradient', gradient: { shade: 'dark', type: 'vertical', gradientToColors: ['#007867', '#FFD666', '#FFAC82'], stops: [0, 100] } },
          stroke: { lineCap: 'round' },
          labels: ['First Time', 'Return']
        };
        new ApexCharts(document.querySelector('#customerChart'), cOptions).render();
      }

      if (document.getElementById('salesChart')) {
        var salesThisYear = [42000, 53000, 48000, 61000, 72000, 69000, 74000, 82000, 78000, 86000, 91000, 97000];
        var salesLastYear = [38000, 45000, 47000, 56000, 65000, 63000, 68000, 70000, 69000, 75000, 80000, 84000];
        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        function formatCurrency(value) {
          if (value == null) return '-';
          return '$' + Number(value).toLocaleString('en-US', { maximumFractionDigits: 0 });
        }

        var sOptions = {
          chart: { id: 'sales-overview', type: 'area', height: 420, zoom: { enabled: false }, toolbar: { show: false } },
          colors: ['#E66239', '#198754'],
          stroke: { width: [3, 2.5], curve: 'smooth' },
          markers: { size: 4, hover: { sizeOffset: 2 } },
          series: [{ name: 'This Year', data: salesThisYear }, { name: 'Last Year', data: salesLastYear }],
          fill: { type: 'gradient', gradient: { shadeIntensity: 1, inverseColors: false, opacityFrom: 0.45, opacityTo: 0.05, stops: [20, 60, 100] } },
          yaxis: { labels: { formatter: function (val) { return formatCurrency(val); } }, title: { text: 'Sales' } },
          xaxis: { categories: months, tickPlacement: 'on' },
          tooltip: { shared: true, y: { formatter: function (val) { return formatCurrency(val); } } },
          legend: { position: 'top', horizontalAlign: 'right' },
          responsive: [{ breakpoint: 640, options: { chart: { height: 340 }, legend: { position: 'bottom', horizontalAlign: 'center' } } }]
        };
        var sChart = new ApexCharts(document.querySelector('#salesChart'), sOptions);
        sChart.render();

        var btnRandom = document.getElementById('btn-random');
        var btnUpdate = document.getElementById('btn-update');
        var showingBoth = true;

        if (btnRandom) {
          btnRandom.addEventListener('click', function () {
            var rand = function () { return Math.round((Math.random() * 80 + 20) * 1000); };
            sChart.updateSeries([
              { name: 'This Year', data: Array.from({ length: 12 }, rand) },
              { name: 'Last Year', data: Array.from({ length: 12 }, rand) }
            ]);
          });
        }

        if (btnUpdate) {
          btnUpdate.addEventListener('click', function () {
            if (showingBoth) {
              sChart.updateSeries([{ name: 'This Year', data: salesThisYear }]);
              btnUpdate.textContent = 'Show Comparison';
            } else {
              sChart.updateSeries([
                { name: 'This Year', data: salesThisYear },
                { name: 'Last Year', data: salesLastYear }
              ]);
              btnUpdate.textContent = 'Show This Year Only';
            }
            showingBoth = !showingBoth;
          });
        }
      }
    }
  });
})();
