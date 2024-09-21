<?php

$column_width = (int) (90 / count($columns));

?>
<style>
    .table-responsive {
        overflow-x: auto;
    }

    .table-responsive::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Customizing the scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Define a media query for smaller screens */
    @media (max-width: 768px) {

        /* Set the width of action column to 20% */
        .action-column {
            width: 20%;
        }

        /* Make the action buttons take up full width */
        .action-column .btn {
            width: 100%;
        }
    }

    /* Define a media query for larger screens */
    @media (min-width: 768px) {

        /* Set the width of action column to 15% */
        .action-column {
            width: 15%;
        }
    }
</style>

<div class="table-responsive bg-light" style="padding:5px;">
    <table id="myTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <?php if (!$unset_delete || !$unset_edit || !$unset_read || !$unset_clone || !empty($actions)) { ?>
                    <th align="center" abbr="tools" axis="col1" class="" width='%'>
                        <div class="text-center">
                            <?php echo $this->l('list_actions'); ?>
                        </div>
                    </th>
                <?php } ?>
                <?php foreach ($columns as $column) { ?>
                    <th width='<?php //echo $column_width 
                                ?>%'>
                        <div class="text-left field-sorting <?php if (isset($order_by[0]) && $column->field_name == $order_by[0]) { ?><?php echo $order_by[1] ?><?php } ?>" rel='<?php echo $column->field_name ?>'>
                            <?php echo $column->display_as ?>
                        </div>
                    </th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>

            <?php
            $i = 1;
            foreach ($list as $num_row => $row) {
            ?>
                <tr <?php if ($num_row % 2 == 1) { ?>class="" <?php } ?>>
                    <?php if (!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)) { ?>
                        <td align="center" class="action-column">

                            <div class='btn-group-sm d-flex justify-content-between'>
                                <?php if (!$unset_delete) { ?>
                                    <a class="btn btn-danger delete-row flex-grow-1 mx-1 d-flex justify-content-center align-items-center" href="<?php echo $row->delete_url ?>" title='<?php echo $this->l('list_delete') ?> <?php echo $subject ?>'>
                                        <i class="fa fa-trash"></i>
                                    </a>
                                <?php } ?>
                                <?php if (!$unset_edit) { ?>
                                    <a class="btn btn-warning edit_button edit-btn flex-grow-1 mx-1 d-flex justify-content-center align-items-center" href="<?php echo $row->edit_url ?>" title='<?php echo $this->l('list_edit') ?> <?php echo $subject ?>'>
                                        <i class="fa fa-edit"></i>
                                    </a>
                                <?php } ?>
                                <?php if (!$unset_clone) { ?>
                                    <a class="btn btn-primary clone_button flex-grow-1 mx-1 d-flex justify-content-center align-items-center" href='<?php echo $row->clone_url ?>' title='Clone <?php echo $subject ?>'>
                                        <i class="fa fa-copy"></i>
                                    </a>
                                <?php } ?>
                                <?php if (!$unset_read) { ?>
                                    <a class="btn btn-info edit_button flex-grow-1 mx-1 d-flex justify-content-center align-items-center" href='<?php echo $row->read_url ?>' title='<?php echo $this->l('list_view') ?> <?php echo $subject ?>'>
                                        <i class="fa fa-eye"></i>
                                    </a>
                                <?php } ?>
                                <?php
                                if (!empty($row->action_urls)) {
                                    foreach ($row->action_urls as $action_unique_id => $action_url) {
                                        $action = $actions[$action_unique_id];
                                ?>
                                        <a href="<?php echo $action_url; ?>" class="btn btn-primary <?php echo str_replace(' ', '_', $action->label) ?> flex-grow-1 mx-1 d-flex justify-content-center align-items-center" data-id="<?php echo $row->$primary_key; ?>" title="<?php echo $action->label ?>">
                                            <?php
                                            if (!empty($action->css_class)) { ?>
                                                <i class="<?php echo $action->css_class; ?>" alt="<?php echo $action->label ?>"> </i>
                                            <?php
                                            } else if (!empty($action->image_url)) {
                                                echo $action->image_url;
                                            } ?>
                                        </a>
                                <?php }
                                } ?>
                                <div class='clear'></div>
                            </div>
                        </td>
                    <?php } ?>
                    <?php foreach ($columns as $column) { ?>
                        <td class='<?php if (isset($order_by[0]) && $column->field_name == $order_by[0]) { ?>sorted<?php } ?>'>
                            <div class='text-left'>
                                <?php echo $row->{$column->field_name} != '' ? $row->{$column->field_name} : '&nbsp;'; ?>
                            </div>
                        </td>
                    <?php } ?>
                </tr>
            <?php
                $i++;
            }
            ?>
        </tbody>
    </table>
</div>
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Image View</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalImage" class="img-fluid" alt="Image">
            </div>
        </div>
    </div>
</div>
<script>
    $('[data-toggle="tooltip"]').tooltip();
    $(document).ready(function() {

        // var dataTable = $('#myTable').DataTable({
        // scrollX: true,
        // dom: 'Bfrtip',
        // buttons: [
        //     'print',
        //     'excelHtml5'
        // ],
        // "order": false
        // });

        <?php if (!$unset_add) { ?>
            $(".crud-add-btn").html('<a href="<?= $add_url ?>" title="<?= $this->l('list_add'); ?> <?= $subject ?>"' +
                'class="btn btn-flat btn-primary">' +
                '<i class="fa fa-plus mr-2"></i>' +
                '<?= $this->l('list_add'); ?> <?= $subject ?>' +
                '</a>');

        <?php } ?>


        $('#myTable thead tr')
            .clone(true)
            .addClass('filters')
            .appendTo('#myTable thead');

        var table = $('#myTable').DataTable({
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
                'print',
                'excelHtml5'
            ],
            "order": false,
            orderCellsTop: true,
            fixedHeader: false,
            initComplete: function() {
                var api = this.api();

                // For each column
                api
                    .columns()
                    .eq(0)
                    .each(function(colIdx) {
                        // Set the header cell to contain the input element
                        var cell = $('.filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        var title = $(cell).text().trim();
                        console.log(title);
                        if (title === 'Actions') {
                            $(cell).html('<button id="clearFiltersBtn" class="btn btn-danger" style="width:100%; justify-content: center;">Clear Filter</button>');
                            $(cell).addClass('text-center');
                        } else {
                            $(cell).html('<input type="text" class="form-control" placeholder="' + title + '" />');

                            // On every keypress in this input
                            $(
                                    'input',
                                    $('.filters th').eq($(api.column(colIdx).header()).index())
                                )

                                .off('keyup change')
                                .on('change', function(e) {
                                    // Get the search value
                                    $(this).attr('title', $(this).val());
                                    var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                    var cursorPosition = this.selectionStart;
                                    // Search the column for that value
                                    api
                                        .column(colIdx)
                                        .search(
                                            this.value != '' ?
                                            regexr.replace('{search}', '(((' + this.value + ')))') :
                                            '',
                                            this.value != '',
                                            this.value == ''
                                        )
                                        .draw();
                                })
                                .on('keyup', function(e) {
                                    e.stopPropagation();

                                    $(this).trigger('change');
                                    $(this)
                                        .focus()[0]
                                        .setSelectionRange(cursorPosition, cursorPosition);
                                });
                        }
                    });

                $('#clearFiltersBtn').on('click', function() {
                    $('.filters input').val('').trigger('change'); // Clear input values and trigger change event
                    api.search('').draw(); // Clear DataTable search and redraw
                });
            },
        });

        // Handle image click events
        $(document).on('click', '.image-thumbnail', function() {
            const imageUrl = $(this).data('image');
            $('#modalImage').attr('src', imageUrl);
            $('#imageModal').modal('show');
        });

        // Reset the modal when it's closed
        $('#imageModal').on('hidden.bs.modal', function() {
            $('#modalImage').attr('src', '');
        });
    });
</script>