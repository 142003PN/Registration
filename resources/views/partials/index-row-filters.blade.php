data-record
@foreach ($tableFilters as $filter)
    @php
        $filterValue = data_get($record, $filter['path']);
        if (($filter['type'] ?? '') === 'month') {
            $filterValue = $filterValue?->format('Y-m') ?? '';
        }
        if (($filter['type'] ?? '') === 'boolean') $filterValue = $filterValue ? 'Yes' : 'No';
        $filterValues = is_array($filterValue)
            ? ($filterValue ?: [$filter['fallback'] ?? 'Unassigned'])
            : [$filterValue ?? ($filter['fallback'] ?? 'Unassigned')];
    @endphp
    data-filter-values-{{ $filter['key'] }}="{{ json_encode(array_values($filterValues)) }}"
@endforeach
