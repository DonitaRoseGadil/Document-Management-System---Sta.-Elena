$('#generateReportBtn').on('click', function() {
            var start = $('#reportStartDate').val();
            var end = $('#reportEndDate').val();
            var all = $('#generateAllReports').is(':checked');

            if (!all && (!start || !end)) {
                Swal.fire({
                    title: "Error!",
                    text: "Please select a specific date range or check 'Generate All Reports'.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
                return false; // Prevent form submission
            }

            if (new Date(start) > new Date(end)) {
                Swal.fire({
                    title: "Error!",
                    text: "End date cannot be earlier than start date.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
                return false; // Prevent form submission
            }

            if (all && start && end) {
                Swal.fire({
                    title: "Error!",
                    text: "Please select either a specific date range or check 'Generate All Reports'.",
                    icon: "error",
                    confirmButtonText: "OK"
                });

                $('#reportStartDate').val('');
                $('#reportEndDate').val('');
                $('#generateAllReports').prop('checked', false);
                return false; // Prevent form submission
                
            }
            $('#reportForm').submit();

        });

$('#generateAllReports').on('change', function() { 
    if ($(this).is(':checked')) {
        $('#reportStartDate').val('');
        $('#reportEndDate').val('');
    }
});