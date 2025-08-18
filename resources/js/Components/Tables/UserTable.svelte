<script lang="ts">

    import type {CastFunction, Paginator, User} from "@/types";
    import DataTable from "@/Components/UI/DataTable.svelte";

    let {
        paginator,
        columns,
        casts,
        filterable,
    } : {
        paginator: Paginator<User>,
        columns: (keyof User)[],
        casts: Partial<{
            [key in keyof User]: CastFunction<any>
        }>,
        filterable: boolean,
    } = $props();
</script>

<DataTable
    {paginator}
    {columns}
    {casts}
    {filterable}
    showRoute="admin.users.show"
/>
