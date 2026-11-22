
<form method="GET" action="{{ route('dashboard') }}" class="filter-form" id="dateFilterForm">

    <div>
        <select name="date_filter" id="date_filter">
            <option value="all" {{ $dateFilter === 'all' ? 'selected' : '' }}>All Time</option>
            <option value="today" {{ $dateFilter === 'today' ? 'selected' : '' }}>Today</option>
            <option value="last_week" {{ $dateFilter === 'last_week' ? 'selected' : '' }}>Last Week</option>
            <option value="last_month" {{ $dateFilter === 'last_month' ? 'selected' : '' }}>Last Month</option>
            <option value="this_month" {{ $dateFilter === 'this_month' ? 'selected' : '' }}>This Month</option>
            <option value="custom" {{ $dateFilter === 'custom' ? 'selected' : '' }}>Custom</option>
        </select>
    </div>

    <div class="custom-date-field" style="{{ $dateFilter === 'custom' ? '' : 'display:none;' }}">
        <label for="from_date">From Date</label>
        <input type="date" name="from_date" id="from_date" value="{{ $fromDate }}">
    </div>

    <div class="custom-date-field" style="{{ $dateFilter === 'custom' ? '' : 'display:none;' }}">
        <label for="to_date">To Date</label>
        <input type="date" name="to_date" id="to_date" value="{{ $toDate }}">
    </div>

    @if($downloadRoute)
        <a href="{{ $downloadRoute . '?' . http_build_query(request()->query()) }}"
           class="btn btn-primary">
            Download
        </a>
    @else
        <button type="button" class="btn btn-download" disabled>
            Download
        </button>
    @endif

</form>

<script>
    const filterForm = document.getElementById('dateFilterForm');
    const dateFilter = document.getElementById('date_filter');
    const fromDate = document.getElementById('from_date');
    const toDate = document.getElementById('to_date');
    const customDateFields = document.querySelectorAll('.custom-date-field');

    dateFilter.addEventListener('change', function () {
        const isCustom = this.value === 'custom';

        customDateFields.forEach(function (field) {
            field.style.display = isCustom ? 'block' : 'none';
        });

        if (!isCustom) {
            filterForm.submit();
        }
    });

    function submitCustomDateFilter() {
        if (
            dateFilter.value === 'custom' &&
            fromDate.value &&
            toDate.value
        ) {
            filterForm.submit();
        }
    }

    fromDate.addEventListener('change', submitCustomDateFilter);
    toDate.addEventListener('change', submitCustomDateFilter);
</script>
