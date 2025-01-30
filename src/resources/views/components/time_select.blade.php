<div class="time-select">
    <select name="{{ $name }}">
        @for ($hour = 0; $hour < 24; $hour++)
            @for ($minute = 0; $minute < 60; $minute += 5)  {{-- 5分単位 --}}
                @php
                    $time = sprintf('%02d:%02d', $hour, $minute);
                @endphp
                <option value="{{ $time }}" {{ $time == $selected_time ? 'selected' : '' }}>
                    {{ $time }}
                </option>
            @endfor
        @endfor
    </select>
</div>