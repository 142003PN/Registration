<div
    class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 my-3 js-jquery-pagination"
    data-pagination-target="{{ $targetSelector }}"
    data-pagination-per-page="{{ $perPage ?? 10 }}"
    data-pagination-label="{{ $label ?? 'records' }}"
></div>

@once
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('.js-jquery-pagination').each(function () {
                const $pager = $(this);
                const target = $pager.attr('data-pagination-target');
                let perPage = Number($pager.attr('data-pagination-per-page')) || 10;
                let currentPage = 1;
                const label = $pager.attr('data-pagination-label') || 'records';
                const $summary = $('<span class="small text-muted" role="status" aria-live="polite"></span>');
                const $size = $('<select class="form-select form-select-sm w-auto" aria-label="Records per page"></select>');
                [...new Set([10, 20, 50, perPage])].sort((a, b) => a - b).forEach(size => {
                    $size.append($('<option></option>').val(size).text(size + ' per page'));
                });
                $size.val(perPage);
                const $pages = $('<ul class="pagination pagination-sm mb-0"></ul>');
                $pager.append($summary, $('<div class="d-flex flex-wrap align-items-center gap-3"></div>').append($size, $('<nav aria-label="Table pagination"></nav>').append($pages)));

                function render() {
                    const $items = $(target);
                    const $matches = $items.filter(function () { return $(this).data('liveSearchMatches') !== false; });
                    const total = $matches.length;
                    const pageCount = Math.max(1, Math.ceil(total / perPage));
                    currentPage = Math.min(currentPage, pageCount);
                    const start = (currentPage - 1) * perPage;
                    $items.hide();
                    $matches.slice(start, start + perPage).show();
                    $summary.text('Showing ' + (total ? start + 1 : 0) + '–' + Math.min(start + perPage, total) + ' of ' + total + ' ' + label);
                    $pages.empty();
                    function button(text, page, disabled, active = false) {
                        const $item = $('<li class="page-item"></li>').toggleClass('disabled', disabled).toggleClass('active', active);
                        const $button = $('<button type="button" class="page-link"></button>').text(text).prop('disabled', disabled);
                        if (active) $button.attr('aria-current', 'page');
                        $button.on('click', () => { currentPage = page; render(); });
                        $pages.append($item.append($button));
                    }
                    button('Previous', currentPage - 1, currentPage === 1);
                    const first = Math.max(1, Math.min(currentPage - 2, pageCount - 4));
                    for (let page = first; page <= Math.min(pageCount, first + 4); page++) {
                        button(page, page, false, page === currentPage);
                    }
                    button('Next', currentPage + 1, currentPage === pageCount);
                }
                $size.on('change', function () { perPage = Number(this.value); currentPage = 1; render(); });
                $(document).on('ecms:live-search-updated', function (event, detail) {
                    if (detail.targetSelector === target) { currentPage = 1; render(); }
                });
                render();
            });
        });
    </script>
@endonce
