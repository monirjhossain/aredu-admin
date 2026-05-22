(function () {
    window.confirmDeleteStudentApplication = function (id) {
        if (typeof Swal === 'undefined') {
            var form = document.getElementById('deleteForm' + id);
            if (form && confirm('Are you sure you want to delete this application?')) {
                form.submit();
            }
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then(function (result) {
            if (result.isConfirmed) {
                var form = document.getElementById('deleteForm' + id);
                if (form) {
                    form.submit();
                }
            }
        });
    };
})();
