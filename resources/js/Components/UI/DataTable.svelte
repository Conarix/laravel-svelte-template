<script lang="ts">

    import {LoaderCircle, ArrowDownUp, ArrowDownNarrowWide, ArrowDownWideNarrow} from "@lucide/svelte";
    import type {CastFunction, Paginator} from "@/types";
    import Text from "@/Components/Inputs/Text.svelte";
    import Debug from "@/Components/Debug.svelte";
    import {Link, page, useForm} from "@inertiajs/svelte";
    import Number from "@/Components/Inputs/Number.svelte";
    import Button from "@/Components/UI/Button.svelte";
    import {titleCase} from "@/utils/helpers";

    type Props = {
        paginator: Paginator<any>,
        columns: string[],
        casts: {
            [key: string]: CastFunction<any>
        }
        filterable: boolean,
        showRoute?: string,
    }

    let {
        paginator,
        columns,
        casts,
        filterable,
        showRoute,
    }: Props = $props();

    const searchParams = new URLSearchParams(window.location.search);

    const sortDirections = ['', 'asc', 'desc'] as const;
    let form = $state(useForm({
        search: searchParams.get('search') ?? '',
        sort: searchParams.get('sort') ?? '',
        sort_direction: searchParams.get('sort_direction') ?? sortDirections[0],
        per_page: searchParams.get('per_page') ? parseInt(searchParams.get('per_page') as string, 10) : 10,
    }));

    let timeout: number;

    const filterChange = () => {
        clearTimeout(timeout);

        if ($form.isDirty) {
            timeout = setTimeout(search, 1000)
        }
    };

    const search = () => {
        $form.get(
            $page.props.ziggy.location,
            {
                preserveScroll: true,
                replace: true
            }
        );
    }

    const handleSortChange = (column: string) => {
        if ($form.sort === column) {
            // Cycle sort_direction
            const nextIndex = sortDirections.findIndex((dir) => dir === $form.sort_direction) + 1;
            const nextSortDirection = sortDirections[nextIndex < sortDirections.length ? nextIndex : 0];

            if (nextSortDirection === '') {
                $form.sort = '';
            } else {
                $form.sort_direction = nextSortDirection;
            }
        } else {
            $form.sort = column;
            $form.sort_direction = 'asc'
        }

        clearTimeout(timeout);
        search();
    }
</script>

<div class="relative flex flex-col justify-start items-center w-full">
    <div class="grid grid-cols-1 gap-2 w-full">
        {#if filterable}
            <div class="grid gap-2 w-full">
                <Text bind:value={$form.search} placeholder="Search..." oninput={filterChange} />
            </div>
        {/if}
        <div class="grid gap-2 w-full border-b py-1 px-2"
             style="grid-template-columns: repeat({columns.length}, minmax(0, 1fr))">
            {#each columns as column, i (i)}
                <div
                    onclick={() => handleSortChange(column)}
                    onkeydown={(e) => e.key === 'Enter' && handleSortChange(column)}
                    tabindex="-1"
                    role="button"
                    class="flex items-center gap-2 cursor-pointer"
                >
                    <span class="text-lg">{titleCase(column)}</span>

                    {#if $form.sort === column}
                        {#if $form.sort_direction === 'asc'}
                            <ArrowDownNarrowWide />
                        {:else}
                            <ArrowDownWideNarrow />
                        {/if}
                    {:else}
                        <ArrowDownUp />
                    {/if}
                </div>
            {/each}
        </div>
        <div class="grid grid-cols-1 w-full divide-y">
            {#each paginator.data as record, i (i)}
                {#snippet dataRow()}
                    <div class="grid gap-2 w-full py-1 px-2 hover:bg-accent {showRoute ? 'cursor-pointer' : ''}"
                         style="grid-template-columns: repeat({columns.length}, minmax(0, 1fr))">
                        {#each columns as column, i (i)}
                            {#if casts[column]}
                                {#if record[column]}
                                    <span>{casts[column](record[column]) ?? 'N/A'}</span>
                                {:else}
                                    <span>N/A</span>
                                {/if}
                            {:else}
                                <span>{record[column] ?? 'N/A'}</span>
                            {/if}
                        {/each}
                    </div>
                {/snippet}

                {#if showRoute}
                    <Link href={route(showRoute, [record.id])}>
                        {@render dataRow()}
                    </Link>
                {:else}
                    {@render dataRow()}
                {/if}
            {:else}
                <div class="flex justify-center items-center col-span-full">
                    <span class="font-semibold">No Records Found</span>
                </div>
            {/each}
        </div>
    </div>

    <div class="grid grid-cols-3 justify-center items-center w-full mt-2">
        <div class="flex justify-start items-center">
            <Number label="Per Page" bind:value={
                () => $form.per_page.toString(),
                (v) => $form.per_page = parseInt(v, 10)
            } min={1} max={100}/>
        </div>

        <span class="font-semibold text-center">{paginator.from} to {paginator.to} of {paginator.total}</span>

        <span class="flex justify-end items-center gap-1">
            {#each paginator.links as link, i (i)}
                {#if link.url && !link.active}
                    <Link href={link.url ?? '#'} disabled={link.active}>
                        <Button variant="outline" disabled={link.active}>
                            {@html link.label}
                        </Button>
                    </Link>
                {:else}
                    <Button variant="outline" disabled={link.active}>
                        {@html link.label}
                    </Button>
                {/if}
            {/each}
        </span>
    </div>

    <div class="absolute top-0 left-0 justify-center items-center w-full h-full bg-neutral-500/50 text-white" style="display: {$form.processing ? 'flex' : 'none'}">
        <LoaderCircle class="animate-spin" size="50" />
    </div>
</div>

<Debug {$form}/>
