<div
    class="row g-2 mb-3 js-live-search-controls"
    data-live-search-target="{{ $targetSelector }}"
    data-live-search-empty-message="{{ $emptyMessage ?? 'No matching records found.' }}"
    @isset($remoteUrl)
        data-live-search-url="{{ $remoteUrl }}"
        data-live-search-replace="{{ $remoteTargets }}"
    @endisset
>
    <div class="col-12 col-md-7 col-lg-4">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input
                type="search"
                id="{{ $inputId }}"
                class="form-control js-live-search-input"
                placeholder="{{ $placeholder ?? 'Search records...' }}"
                aria-label="{{ $placeholder ?? 'Search records' }}"
                value="{{ $value ?? '' }}"
                autocomplete="off"
            >
        </div>
    </div>
    @foreach(($filters ?? []) as $filter)
        <div class="col-12 col-md-5 col-lg-3">
            @if(($filter['type'] ?? 'select') === 'month')
                <label for="{{ $filter['id'] ?? ($inputId . ucfirst($filter['key'])) }}" class="form-label visually-hidden">{{ $filter['label'] ?? 'Month' }}</label>
                <input
                    type="month"
                    id="{{ $filter['id'] ?? ($inputId . ucfirst($filter['key'])) }}"
                    class="form-control js-live-filter-input"
                    data-filter-key="{{ $filter['key'] }}"
                    value="{{ $filter['value'] ?? '' }}"
                    @isset($filter['max']) max="{{ $filter['max'] }}" @endisset
                    @isset($filter['min']) min="{{ $filter['min'] }}" @endisset
                >
            @else
                <select
                    id="{{ $filter['id'] ?? ($inputId . ucfirst($filter['key'])) }}"
                    class="form-select js-live-filter-select"
                    aria-label="{{ $filter['label'] ?? 'Filter' }}"
                    data-filter-key="{{ $filter['key'] }}"
                >
                    <option value="">{{ $filter['label'] ?? 'Filter' }}: All</option>
                    @foreach(($filter['options'] ?? []) as $option)
                        <option value="{{ $option['value'] }}" @selected((string) ($filter['value'] ?? '') === (string) $option['value'])>{{ $option['label'] }}</option>
                    @endforeach
                </select>
            @endif
        </div>
    @endforeach
</div>

@once
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        (function () {
            if (window.__ecmsLiveSearchInitialized) {
                return;
            }

            window.__ecmsLiveSearchInitialized = true;

            function ensureEmptyState($items, message) {
                if (!$items.length) {
                    return $();
                }

                const $container = $items.first().parent();
                let $emptyState = $container.children('.js-live-search-empty');

                if ($emptyState.length) {
                    return $emptyState;
                }

                if ($items.first().is('tr')) {
                    const colspan = $items.first().children().length || 1;
                    $emptyState = $('<tr class="js-live-search-empty d-none"><td class="text-center text-muted py-4"></td></tr>');
                    $emptyState.find('td').attr('colspan', colspan).text(message);
                } else if ($items.first().is('li')) {
                    $emptyState = $('<li class="list-group-item js-live-search-empty text-center text-muted py-3 d-none"></li>').text(message);
                } else {
                    $emptyState = $('<div class="js-live-search-empty text-center text-muted py-3 d-none"></div>').text(message);
                }

                $container.append($emptyState);

                return $emptyState;
            }

            function getDataValue($item, prefix, key) {
                return (($item.attr(`data-${prefix}-${key}`) || '') + '').trim();
            }

            function bindLiveSearch($controls) {
                if ($controls.data('liveSearchBound')) {
                    return;
                }

                $controls.data('liveSearchBound', true);

                const targetSelector = $controls.data('live-search-target');
                const emptyMessage = $controls.data('live-search-empty-message') || 'No matching records found.';
                const $searchInput = $controls.find('.js-live-search-input');
                const $filterSelects = $controls.find('.js-live-filter-select');
                const $filterInputs = $controls.find('.js-live-filter-input');
                const remoteUrl = $controls.attr('data-live-search-url');

                if (remoteUrl) {
                    const replaceSelector = $controls.attr('data-live-search-replace');
                    let timer;
                    let pendingRequest;
                    let revision = 0;
                    const $status = $('<p class="small text-muted d-none" role="status"></p>').insertAfter($controls);

                    const updateResults = () => {
                        clearTimeout(timer);
                        const currentRevision = ++revision;
                        if (pendingRequest) pendingRequest.abort();
                        const url = new URL(remoteUrl, window.location.origin);
                        const search = ($searchInput.val() || '').toString().trim();
                        if (search) url.searchParams.set('search', search);
                        $filterSelects.add($filterInputs).each(function () {
                            if (this.value) url.searchParams.set($(this).data('filter-key'), this.value);
                        });
                        timer = setTimeout(async () => {
                            pendingRequest = new AbortController();
                            $status.text('Loading results...').removeClass('d-none');
                            try {
                                const response = await fetch(url, {
                                    signal: pendingRequest.signal,
                                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                                });
                                if (!response.ok) throw new Error('Request failed');
                                const html = await response.text();
                                if (currentRevision !== revision) return;
                                const page = new DOMParser().parseFromString(html, 'text/html');
                                const targets = Array.from(document.querySelectorAll(replaceSelector));
                                const replacements = targets.map(target => page.getElementById(target.id));
                                if (replacements.some(target => !target)) throw new Error('Missing results');
                                targets.forEach((target, index) => target.replaceWith(replacements[index]));
                                window.history.replaceState(null, '', url);
                                $status.addClass('d-none');
                            } catch (error) {
                                if (error.name !== 'AbortError' && currentRevision === revision) {
                                    $status.text('Unable to load results. Please try again.').removeClass('d-none');
                                }
                            }
                        }, 250);
                    };

                    $searchInput.on('input', updateResults);
                    $filterSelects.on('change', updateResults);
                    $filterInputs.on('input change', updateResults);
                    return;
                }

                let $items = $(targetSelector);

                if (!$items.length) {
                    return;
                }

                $items.each(function (index) {
                    const $item = $(this);

                    if (typeof $item.data('liveSearchOriginalIndex') === 'undefined') {
                        $item.data('liveSearchOriginalIndex', index);
                    }
                });

                const $container = $items.first().parent();
                const $emptyState = ensureEmptyState($items, emptyMessage);

                const applyLiveSearch = () => {
                    const query = ($searchInput.val() || '').toString().trim().toLowerCase();
                    const activeFilters = [];
                    const matchedItems = [];

                    $items = $(targetSelector);
                    $items.each(function (index) {
                        const $item = $(this);

                        if (typeof $item.data('liveSearchOriginalIndex') === 'undefined') {
                            $item.data('liveSearchOriginalIndex', index);
                        }
                    });

                    $filterSelects.add($filterInputs).each(function () {
                        const $select = $(this);
                        const value = ($select.val() || '').toString().trim().toLowerCase();

                        if (value) {
                            activeFilters.push({
                                key: $select.data('filter-key'),
                                value: value,
                            });
                        }
                    });

                    $items.each(function () {
                        const $item = $(this);
                        const matchesSearch = !query || $item.text().toLowerCase().indexOf(query) !== -1;
                        const matchesFilters = activeFilters.every((filter) => {
                            const values = $item.attr(`data-filter-values-${filter.key}`);
                            if (values) {
                                return JSON.parse(values).some(value => String(value).trim().toLowerCase() === filter.value);
                            }
                            return getDataValue($item, 'filter', filter.key).toLowerCase() === filter.value;
                        });
                        const matches = matchesSearch && matchesFilters;

                        $item.data('liveSearchMatches', matches);
                        $item.toggle(matches);

                        if (matches) {
                            matchedItems.push(this);
                        }
                    });

                    $items
                        .get()
                        .sort((left, right) => {
                            return $(left).data('liveSearchOriginalIndex') - $(right).data('liveSearchOriginalIndex');
                        })
                        .forEach((item) => $container.append(item));

                    if ($emptyState.length) {
                        $emptyState.toggleClass('d-none', matchedItems.length > 0);
                    }

                    $(document).trigger('ecms:live-search-updated', [{
                        targetSelector: targetSelector,
                        matchedCount: matchedItems.length,
                    }]);
                };

                $searchInput.on('input', applyLiveSearch);
                $filterSelects.on('change', applyLiveSearch);
                $filterInputs.on('input change', applyLiveSearch);

                applyLiveSearch();
            }

            $(function () {
                $('.js-live-search-controls').each(function () {
                    bindLiveSearch($(this));
                });
            });
        })();
    </script>
@endonce
