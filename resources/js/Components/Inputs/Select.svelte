<script lang="ts">

    import {v4 as uuidV4} from 'uuid';
    import type {Option, SelectOptions} from "@/types";
    import {inputClasses} from "@/Components/Inputs/Partials/classes";
    import {ChevronDown, Check} from "@lucide/svelte";
    import FieldLabel from "@/Components/Inputs/Partials/FieldLabel.svelte";
    import InputError from "@/Components/Inputs/Partials/InputError.svelte";

    let {
        value = $bindable(),
        label,
        options,
        multiple = false,
        error,
        placeholder = 'Select an Option',
    } : {
        value: string|string[],
        label: string,
        options: SelectOptions,
        multiple?: boolean,
        error?: string,
        placeholder?: string,
    } = $props();

    console.log(options);

    let displayValue: string = $derived.by(() => {
        const allOptions: Option[] = options.flatMap((val) => 'value' in val ? [val] : val.options)

        if (multiple) {
            const filtered = allOptions.filter((option: Option) => value.includes(option.value));
            if (filtered.length > 0) {
                return filtered.map((val) => val.label).join(', ');
            } else {
                return placeholder;
            }
        } else {
            const selected = allOptions.find((option: Option) => value === option.value);

            if (selected) {
                return selected.label;
            } else {
                return placeholder;
            }
        }
    });

    let popup: HTMLDivElement | undefined = $state();
    let showPopup: boolean = $state(false);

    const selectOption = (val: string) => {
        if (multiple) {
            if (value.includes(val)) {
                (value as string[]).splice(value.indexOf(val), 1)
            } else {
                (value as string[]).push(val);
            }
        } else {
            if (value === val) {
                value = '';
            } else {
                value = val;
            }
        }

        value = value;
    }

    const hidePopup = () => {
        showPopup = false;
    }

    const checkHidePopup = (e: KeyboardEvent) => {
        if (e.key === 'Escape') {
            hidePopup();
        }
    }

    $effect(() => {
        if (showPopup && popup) {
            popup.focus();
        }
    })

    let id = uuidV4();
</script>

{#snippet optionButton(option: Option)}
    <button type="button" class="flex justify-start items-center gap-4 rounded-lg px-2 py-1 hover:bg-accent" onclick={() => selectOption(option.value) }>
        {#if (multiple && value.includes(option.value)) || (option.value === value)}
            <Check size={20} />
        {/if}

        {option.label}
    </button>
{/snippet}

<div class="relative flex flex-col">
    <FieldLabel {id}>{label}</FieldLabel>
    <button type="button" class={inputClasses} onclick={() => {showPopup = !showPopup}}>
        <span class="flex justify-between items-center mx-2 w-full">
            <span class="text-ellipsis">{displayValue}</span>

            <ChevronDown />
        </span>
    </button>
    <InputError {error} />

    {#if showPopup}
        <div
            bind:this={popup}
            class="absolute top-full left-0 flex flex-col max-h-60 mt-2 px-2 py-1 border w-full bg-background rounded-md overflow-y-scroll z-100" onmouseleave={hidePopup} onkeydown={checkHidePopup} tabindex="-1" role="dialog"
        >
            {#each options as option, i (i)}
                {#if 'value' in option}
                    {@render optionButton(option)}
                {:else}
                    <span class="font-semibold underline">{option.label}</span>
                    <div class="flex flex-col w-full pl-2">
                        {#each option.options as subOption, i (i)}
                            {@render optionButton(subOption)}
                        {/each}
                    </div>
                {/if}
            {/each}
        </div>
    {/if}
</div>
