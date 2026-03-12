<script>
    // add padding on bottom of the datatables
    var isExpanded                      = false;
    var paddingHeight                   = '65px';
    var datatablesEl                    = '';
    var lastRowIndex                    = 1;
    var isBottomRow                     = false;
    
    function addPadding(e) {
        isExpanded = $(e).attr('aria-expanded');
        paddingHeight = $(e).attr('data-padding');
        datatablesEl = $(e).attr('data-table-el');

        lastRowIndex = $(datatablesEl).find('tr:last').index();
        clickedRowIndex = $(e).parents('tr').index();
        isBottomRow = lastRowIndex === clickedRowIndex;

        if(isExpanded === 'false') {
            if(isBottomRow) {
                $(datatablesEl).css('padding-bottom', paddingHeight);
            }
            if(clickedRowIndex === (lastRowIndex - 1)) {
                $(datatablesEl).css('padding-bottom', ( (paddingHeight.match(/\d+/) / 2) + 'px' ));
            }
            if(clickedRowIndex === (lastRowIndex - 2)) {
                $(datatablesEl).css('padding-bottom', ( (paddingHeight.match(/\d+/) / 3) + 'px' ));
            }
        } else {
            $(datatablesEl).css('padding-bottom', '0');
        }
    }

    $(document).ready(function() {
        // adjust datatatbles header
        function adjustTable() {
            setTimeout(function() {
                if($($.fn.dataTable.tables(true)).dataTable().fnSettings()._bInitComplete) {
                    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
                    console.log('done : ' + $($.fn.dataTable.tables(true)).dataTable().fnSettings()._bInitComplete);
                }else{
                    adjustTable();
                    console.log('wait : ' + $($.fn.dataTable.tables(true)).dataTable().fnSettings()._bInitComplete);
                }
            }, 1000);
        }
        adjustTable();
    })

</script>