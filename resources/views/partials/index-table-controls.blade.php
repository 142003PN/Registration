@php
    $liveFilters = collect($tableFilters)->map(function ($filter) use ($records) {
        $options = $records->flatMap(function ($record) use ($filter) {
            $value = data_get($record, $filter['path']);
            if (($filter['type'] ?? '') === 'month') return [];
            if (($filter['type'] ?? '') === 'boolean') $value = $value ? 'Yes' : 'No';
            if (is_array($value)) return $value ?: [$filter['fallback'] ?? 'Unassigned'];
            return [$value ?? ($filter['fallback'] ?? 'Unassigned')];
        })->filter(fn ($value) => $value !== null && $value !== '')->unique()->sort()->values()
            ->map(fn ($value) => ['value' => (string) $value, 'label' => str_replace('_', ' ', (string) $value)])->all();
        return array_merge($filter, ['options' => $options]);
    })->all();
@endphp
@include('partials.live-search', [
    'inputId' => $tableId . '-search',
    'targetSelector' => '#' . $tableId . ' > tr[data-record]',
    'placeholder' => 'Search ' . $recordLabel . '...',
    'filters' => $liveFilters,
])
