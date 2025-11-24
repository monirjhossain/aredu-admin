{{-- @extends('layouts.default')
@section('content')
<h1>Create Finance Entry</h1>

<form action="{{ route('finance_entries.store') }}" method="POST">
    @csrf
    @include('admin.account.form')
</form>
@endsection --}}


@extends('layouts.default')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ $title }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <form action="{{ route('finance_entries.store') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="">{{ $title }}</h3>
                        {{-- <a href="{{ route($url.'.index',['type' => 'expense']) }}" class="btn btn-primary">Go Back to {{ $type }} List</a> --}}
                    </div>
                </div>
                <div class="card-body">
                    @include('includes.errors')
                    <div class="row">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="{{ route($url . '.reports.index', ['type' => 'expense_summary']) }}" class="btn btn-primary w-100">Category-wise Cost</a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route($url . '.reports.index', ['type' => 'income_summary']) }}" class="btn btn-success w-100">Income Report</a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route($url . '.reports.index', ['type' => 'student_summary']) }}" class="btn btn-warning w-100">Student-wise income Cost Report</a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route($url . '.create') }}" class="btn btn-info w-100">Total Summary</a>
                            </div>
                            <div class="col-md-3 mt-2">
                                <a href="{{ route($url . '.create') }}" class="btn btn-dark w-100">Student Summary</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const typeSelector = document.getElementById('typeSelector');
        const expenseDiv = document.getElementById('expenseCategory');
        const incomeDiv = document.getElementById('incomeCategory');
        // const studentDiv = document.getElementById('studentCategory');

        function toggleCategories() {
            const value = typeSelector.value;
            expenseDiv.style.display = value === 'expense' ? 'block' : 'none';
            incomeDiv.style.display = value === 'income' ? 'block' : 'none';
            // studentDiv.style.display = value === 'income' ? 'block' : 'none';
        }
        function toggleCategoriesMK() {
            const value = typeSelector.value;
            expenseDiv.style.display = 'none';
            incomeDiv.style.display = 'none';
            // studentDiv.style.display = value === 'income' ? 'block' : 'none';
        }

        typeSelector.addEventListener('change', toggleCategories);

        // Call once on load in case of old input
        window.onload = function() {
            setTimeout(function() {
                toggleCategoriesMK();
            }, 1000);
        };
    });

    // Class definition
var KTSelect2 = function() {
	// Private functions
	var demos = function() {
		// basic
		$('#kt_select2_1').select2({
			placeholder: "Select a state"
		});

		// nested
		$('#kt_select2_2').select2({
			placeholder: "Select a state"
		});

		// multi select
		$('#kt_select2_3').select2({
			placeholder: "Select a state",
		});
		$('#kt_select2_3_5658965656').select2({
			placeholder: "Custom",
		});

		// basic
		$('#kt_select2_4').select2({
			placeholder: "Select a state",
			allowClear: true
		});

		// loading data from array
		var data = [{
			id: 0,
			text: 'Enhancement'
		}, {
			id: 1,
			text: 'Bug'
		}, {
			id: 2,
			text: 'Duplicate'
		}, {
			id: 3,
			text: 'Invalid'
		}, {
			id: 4,
			text: 'Wontfix'
		}];

		$('#kt_select2_5').select2({
			placeholder: "Select a value",
			data: data
		});


		$("#kt_select2_6").select2({
			placeholder: "Search for git repositories",
			allowClear: true,
			ajax: {
				url: "https://api.github.com/search/repositories",
				dataType: 'json',
				delay: 250,
				data: function(params) {
					return {
						q: params.term, // search term
						page: params.page
					};
				},
				processResults: function(data, params) {
					// parse the results into the format expected by Select2
					// since we are using custom formatting functions we do not need to
					// alter the remote JSON data, except to indicate that infinite
					// scrolling can be used
					params.page = params.page || 1;

					return {
						results: data.items,
						pagination: {
							more: (params.page * 30) < data.total_count
						}
					};
				},
				cache: true
			},
			escapeMarkup: function(markup) {
				return markup;
			}, // let our custom formatter work
			minimumInputLength: 1,
			templateResult: formatRepo, // omitted for brevity, see the source of this page
			templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
		});

		// custom styles

		// tagging support
		$('#kt_select2_12_1, #kt_select2_12_2, #kt_select2_12_3, #kt_select2_12_4').select2({
			placeholder: "Select an option",
		});

		// disabled mode
		$('#kt_select2_7').select2({
			placeholder: "Select an option"
		});

		// disabled results
		$('#kt_select2_8').select2({
			placeholder: "Select an option"
		});

		// limiting the number of selections
		$('#kt_select2_9').select2({
			placeholder: "Select an option",
			maximumSelectionLength: 2
		});

		// hiding the search box
		$('#kt_select2_10').select2({
			placeholder: "Select an option",
			minimumResultsForSearch: Infinity
		});

		// tagging support
		$('#kt_select2_11').select2({
			placeholder: "Add a tag",
			tags: true
		});

		// disabled results
		$('.kt-select2-general').select2({
			placeholder: "Select an option"
		});
	}

	var modalDemos = function() {
		$('#kt_select2_modal').on('shown.bs.modal', function () {
			// basic
			$('#kt_select2_1_modal').select2({
				placeholder: "Select a state"
			});

			// nested
			$('#kt_select2_2_modal').select2({
				placeholder: "Select a state"
			});

			// multi select
			$('#kt_select2_3_modal').select2({
				placeholder: "Select a state",
			});
			$('#kt_select2_3_5658965656_modal').select2({
				placeholder: "Select a state",
			});

			// basic
			$('#kt_select2_4_modal').select2({
				placeholder: "Select a state",
				allowClear: true
			});
		});
	}

	// Public functions
	return {
		init: function() {
			demos();
			modalDemos();
		}
	};
}();

// Initialization
jQuery(document).ready(function() {
	KTSelect2.init();
});
</script>


@endsection
