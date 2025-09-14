<script lang="ts">
    import {DateTime} from "luxon";
    import FieldLabel from "@/Components/Inputs/Partials/FieldLabel.svelte";
    import {v4 as uuidV4} from "uuid";
    import Base from "@/Components/Inputs/Partials/Base.svelte";
    import {datesInMonth, dateToString} from "@/utils/helpers";
    import InputError from "@/Components/Inputs/Partials/InputError.svelte";
    import InputHint from "@/Components/Inputs/Partials/InputHint.svelte";
    import Button from "@/Components/UI/Button.svelte";
    import { ChevronsRight, ChevronsLeft } from "@lucide/svelte";
    import { clickOutside } from "@/Actions/ClickOutside";
    import FieldRow from "@/Components/Forms/Partials/FieldRow.svelte";
    import Select from "@/Components/Inputs/Select.svelte";
    import type {Option} from "@/types";

    const MONTHS: string[] = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December',
    ] as const;

    const WEEKDAYS: string[] = [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday',
    ] as const;

    const HOURS: Option[] = Array.from({ length: 23 }, (x, i) => ({label: i.toString(), value: i.toString()}));
    const MINUTES: Option[] = Array.from({ length: 59 }, (x, i) => ({label: i.toString(), value: i.toString()}));

    let {
        label,
        value = $bindable(),
        time = false,
        weekStartsAt = 'Monday',
        error,
        hint,
    } : {
        label: string;
        value: DateTime | null;
        time?: boolean;
        weekStartsAt?: (typeof WEEKDAYS)[number];
        error?: string;
        hint?: string;
    } = $props();

    let pickerOpen: boolean = $state(false);
    let pickerView: 'decade' | 'year' | 'month' = $state('month');
    let year: number = $state(value ? value.year : DateTime.now().year);
    let month: number = $state(value ? value.month : DateTime.now().month);
    let hour: number = $state(value ? value.hour : 0);
    let minute: number = $state(value ? value.minute : 0);
    let dates: DateTime[] = $derived(datesInMonth(year, month));
    let startOffset: number = $derived(WEEKDAYS.indexOf(dates[0]?.weekdayLong ?? 'Monday'));
    let displayFormat: string = $derived(time ? "l jS F Y \\a\\t H:i:s" : "l jS F Y")
    const today: DateTime = DateTime.local();

    const id = uuidV4();

    const openPicker = () => {
        pickerView = 'month';
        pickerOpen = true;
    };

    const viewDown = () => {
        switch (pickerView) {
            case 'month':
                pickerView = 'year';
                break;
            case 'year':
                pickerView = 'decade';
                year = Math.floor(year / 10) * 10;
                break;
        }
    }

    const handlePickerBack = () => {
        switch (pickerView) {
            case 'month':
                month--;

                if (month == 0) {
                    year--;
                    month = 12;
                }

                break;
            case 'year':
                year--;
                break;
            case 'decade':
                year = Math.floor(year / 10) * 10;
                year -= 10;
                break;
        }
    }

    const handlePickerForward = () => {
        switch (pickerView) {
            case 'month':
                month++;

                if (month == 13) {
                    year++;
                    month = 1;
                }
                break;
            case 'year':
                year++;
                break;
            case 'decade':
                year += 10;
                break;
        }
    }

    const timeChanged = () => {
        if (value) {
            value = value.set({ hour: hour, minute: minute });
        }
    }

    const selectDate = (date: DateTime) => {
        value = date;

        if (time) timeChanged();
    }
</script>

<div class="relative flex flex-col">
    <FieldLabel {id}>{label}</FieldLabel>
    <Base
        {id}
        value={value ? dateToString(displayFormat)(value.toISO()) : ''}
        type="datetime"
        readonly
        onfocus={openPicker}
    />
    <InputHint {hint} />
    <InputError {error} />

    {#if pickerOpen}
        <div
            class="absolute top-[105%] left-0 flex flex-col gap-4 w-xs border bg-background shadow-lg rounded-md p-2 date-picker"
            use:clickOutside={() => pickerOpen = false}
        >
            <div class="flex justify-between items-center w-full">
                <Button variant="outline" onclick={handlePickerBack}>
                    <ChevronsLeft />
                </Button>

                {#if pickerView === 'decade'}
                    <span>{year} - {year + 9}</span>
                {:else}
                    <Button variant="outline" onclick={viewDown}>
                        {#if pickerView === 'year'}
                            <span>{year}</span>
                        {:else}
                            <span>{MONTHS[month - 1]} {year}</span>
                        {/if}
                    </Button>
                {/if}

                <Button variant="outline" onclick={handlePickerForward}>
                    <ChevronsRight />
                </Button>
            </div>

            <div class={["date-picker-calendar", pickerView]}>
                {#if pickerView === 'decade'}
                    {#each Array.from({ length: 10 }, (x, i) => year + i) as decadeYear (decadeYear)}
                        <Button variant="outline" onclick={() => {year = decadeYear; pickerView = 'year'}}>
                            {decadeYear}
                        </Button>
                    {/each}
                {:else if pickerView === 'year'}
                    {#each MONTHS as yearMonth, i (i)}
                        <Button variant="outline" onclick={() => {month = i+1; pickerView = 'month'}}>
                            {yearMonth.substring(0, 3)}
                        </Button>
                    {/each}
                {:else}
                    {#each WEEKDAYS as weekday, i (i)}
                        <span class="text-muted-foreground text-center">{weekday.substring(0, 2)}</span>
                    {/each}
                    {#each Array.from({length: startOffset}) as offset, i (i)}
                        <span></span>
                    {/each}
                    {#each dates as date, i (i)}
                        <Button variant="outline" onclick={() => selectDate(date)} class={[value && date.hasSame(value, 'day') && 'bg-accent']}>
                            <span class={[date.hasSame(today, 'day') && 'today']}>{date.day}</span>
                        </Button>
                    {/each}
                {/if}
            </div>

            {#if pickerView === 'month'}
                {#if time}
                    <div class="flex justify-center items-center w-full gap-2 *:first:flex-grow *:last:flex-grow">
                        <Select
                            label="Hour"
                            bind:value={
                                () => hour.toString(),
                                (v) => hour = parseInt(v, 10)
                            }
                            options={HOURS}
                            onchange={timeChanged}
                        />

                        <span>:</span>

                        <Select
                            label="Minute"
                            bind:value={
                                () => minute.toString(),
                                (v) => minute = parseInt(v, 10)
                            }
                            options={MINUTES}
                            onchange={timeChanged}
                        />
                    </div>
                {/if}

                <FieldRow columns={{default: 1, sm: 2}}>
                    <Button variant="outline" onclick={() => { year = today.year; month = today.month; }}>
                        <span>Go To Today</span>
                    </Button>

                    <Button variant="outline" onclick={() => value = null}>
                        <span class="clear">Clear</span>
                    </Button>
                </FieldRow>
            {/if}
        </div>
    {/if}
</div>

<style>
    .date-picker-calendar {
        display: grid;
        gap: 0.25rem;
    }

    .date-picker-calendar.month {
        grid-template-columns: repeat(7, minmax(0, 1fr));
    }

    .date-picker-calendar.year, .date-picker-calendar.decade {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }

    .date-picker-calendar .today {
        color: var(--date-picker-today, #0077ff);
    }

    .date-picker .clear {
        color: var(--date-picker-clear, #ff0032);
    }
</style>
