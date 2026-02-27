<script>
    $(document).ready(function() {
        var $table = $("<?= $tableId ?>");
        var $tbody = $table.find('tbody');

        if ($tbody.length > 0) {
            $tbody.sortable({
                handle: ".drag-handle",
                placeholder: "ui-sortable-placeholder",
                axis: "y",
                helper: function(e, ui) {
                    ui.children().each(function() {
                        $(this).width($(this).width());
                    });
                    return ui;
                },
                handle: ".drag-handle",
                placeholder: "ui-sortable-placeholder",
                axis: "y",
                start: function(e, ui) {
                    ui.placeholder.height(ui.item.height());
                },
                update: function(event, ui) {
                    let items = [];
                    $(this).find('tr').each(function(index) {
                        let id = $(this).attr('data-id') || $(this).data('id');
                        if (id) {
                            items.push({
                                id: id,
                                sort_order: index + 1
                            });
                        }
                    });

                    if (items.length > 0) {
                        $.ajax({
                            url: '<?= $url ?>',
                            method: 'POST',
                            contentType: 'application/json',
                            data: JSON.stringify({
                                items: items
                            }),
                            success: function(response) {
                                console.log('✅ Успешно пренареждане!');
                            },
                            error: function(xhr) {
                                console.error('❌ Грешка:', xhr.responseText);
                            }
                        });
                    }
                }
            }).disableSelection();

            console.log('🚀 Sortable активиран за: <?= $tableId ?>');
        } else {
            console.error('⚠️ Не е открит tbody за таблица: <?= $tableId ?>');
        }
    });
</script>

<style>
    .ui-sortable-handle {
        touch-action: none;
    }

    .ui-sortable-placeholder {
        display: table-row;
        background: #f8fafc !important;
        border: 2px dashed #cbd5e1 !important;
        visibility: visible !important;
    }

    .drag-handle {
        cursor: grab !important;
    }
</style>