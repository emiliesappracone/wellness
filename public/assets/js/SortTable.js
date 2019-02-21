$(document).ready(function() {
    $('#sortTable').DataTable({
        "bLengthChange": false,
        "paging": true,
        "pageLength": 10,
        "bInfo" : false,
        drawCallback: function() {
            $('.dt-select2').select2();
        }
    });
    $('#sortTableTable_filter').css({"display":"none"});
    var table = $('#sortTable').DataTable();
    // #myInput is a <input type="text"> element
    $('#serchUserInput').on( 'keyup', function () {
        table.search( this.value ).draw();
    } );
});