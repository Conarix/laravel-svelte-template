<script lang="ts">

    import {onMount} from "svelte";
    import {page} from "@inertiajs/svelte";
    import type {HTMLImgAttributes} from "svelte/elements";

    const {
        path,
        ...restProps
    } : {
        path: string,
        restProps?: HTMLImgAttributes
    } = $props();

    let url: string = $state('');

    onMount(() => {
        let baseUrl = $page.props.ziggy.base_url;
        if (!baseUrl.endsWith("/")) {
            baseUrl += "/";
        }

        let urlPath = path;
        if (path.startsWith(baseUrl)) {
            urlPath.substring(1)
        }

        url = baseUrl + urlPath;
    })
</script>

<img src={url} alt="" {...restProps} />
