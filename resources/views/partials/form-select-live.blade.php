{{-- 
    Reusable Live Search Form Select Component
    
    Usage:
    @include('partials.form-select-live', [
        'id' => 'course_id',
        'name' => 'course_id',
        'label' => 'Course',
        'options' => $courses,
        'optionKey' => 'id',
        'optionLabel' => 'title',
        'optionDisplay' => 'c_code {{ $item->c_title }}', // optional, supports blade syntax
        'selected' => old('course_id', $selectedId),
        'required' => true,
        'searchPlaceholder' => 'Select Course',
        'searchHelpText' => 'Start typing to search courses by code or title.',
        'errorField' => 'course_id', // optional, for @error() checking
        'additionalDataAttributes' => ['data-custom' => 'value'], // optional
        'wrapperClass' => '', // optional additional wrapper classes
        'selectClass' => '', // optional additional select classes
    ])
--}}

@php
    $id = $id ?? uniqid('form-select-');
    $name = $name ?? '';
    $label = $label ?? '';
    $options = $options ?? [];
    $optionKey = $optionKey ?? 'id';
    $optionLabel = $optionLabel ?? 'name';
    $optionDisplay = $optionDisplay ?? null;
    $selected = $selected ?? '';
    $required = $required ?? false;
    $searchPlaceholder = $searchPlaceholder ?? "Select $label";
    $searchHelpText = $searchHelpText ?? "Start typing to search...";
    $errorField = $errorField ?? $name;
    $additionalDataAttributes = $additionalDataAttributes ?? [];
    $wrapperClass = $wrapperClass ?? '';
    $selectClass = $selectClass ?? '';
    $slot = $slot ?? null;
    
    $requiredAttr = $required ? 'required' : '';
    $requiredMessage = $required ? "$label is required." : '';
    $dataAttrs = '';
    foreach ($additionalDataAttributes as $key => $value) {
        $dataAttrs .= " $key=\"$value\"";
    }
@endphp

<div class="ecms-live-select {{ $wrapperClass }}" {{ $dataAttrs }}>
    <div class="ecms-live-select-control">
        <i class="fas fa-search ecms-live-select-icon"></i>
        <input
            type="search"
            class="ecms-live-select-input"
            id="{{ $id }}_search"
            placeholder="{{ $searchPlaceholder }}"
            autocomplete="off"
            aria-label="Search {{ strtolower($label) }}"
            aria-expanded="false"
            role="combobox"
            data-live-search-input
            data-validation-ignore="true"
        >
        <i class="fas fa-chevron-down ecms-live-select-caret"></i>
    </div>
    
    <select 
        class="form-select ecms-live-select-native {{ $selectClass }} @error($errorField) is-invalid @enderror" 
        id="{{ $id }}" 
        name="{{ $name }}" 
        {{ $requiredAttr }}
        @if($requiredMessage)
            data-required-message="{{ $requiredMessage }}"
        @endif
        data-live-search-field 
        tabindex="-1" 
        aria-hidden="true"
    >
        <option value="">Select {{ $label }}</option>
        
        @if($slot && $slot->isNotEmpty())
            {{-- Use slot content for custom option rendering --}}
            {{ $slot }}
        @else
            {{-- Auto-render options from array --}}
            @foreach ($options as $option)
                @php
                    $optionValue = is_object($option) ? $option->{$optionKey} : $option[$optionKey];
                    $isSelected = (string)$optionValue === (string)$selected;
                    
                    if ($optionDisplay) {
                        // Use blade-like syntax: 'c_code {{ $item->c_title }}'
                        $displayText = preg_replace_callback('/\{\{\s*\$item->(\w+)\s*\}\}/', function($matches) use ($option) {
                            return is_object($option) ? $option->{$matches[1]} : $option[$matches[1]];
                        }, $optionDisplay);
                    } else {
                        $displayText = is_object($option) ? $option->{$optionLabel} : $option[$optionLabel];
                    }
                @endphp
                <option value="{{ $optionValue }}" @selected($isSelected)>
                    {{ $displayText }}
                </option>
            @endforeach
        @endif
    </select>
    
    <div class="ecms-live-select-menu" data-live-search-menu></div>
    <div class="form-text ecms-live-select-status" data-live-search-status>{{ $searchHelpText }}</div>
</div>
