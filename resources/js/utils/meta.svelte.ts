import {type Writable, writable} from "svelte/store";

export const theme: Writable<'light' | 'dark'> = writable('dark');
