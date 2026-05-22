{{-- $updateUrl, $currentStatusId, $statuses, $tone, $compact (optional) --}}
<div class="uni-status-wrap status-tone-{{ $tone }}{{ !empty($compact) ? ' uni-status-wrap--compact' : '' }}{{ !empty($wrapClass) ? ' ' . $wrapClass : '' }}">
    <span class="uni-status-saving"><i class="fas fa-spinner fa-spin"></i></span>
    <select
        class="inline-status-select"
        data-url="{{ $updateUrl }}"
        data-original="{{ $currentStatusId }}"
    >
        @foreach ($statuses as $status)
            <option value="{{ $status->id }}" {{ (int) $currentStatusId === (int) $status->id ? 'selected' : '' }}>
                {{ $status->name }}
            </option>
        @endforeach
    </select>
</div>
