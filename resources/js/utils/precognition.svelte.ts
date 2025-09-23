import type {Method} from "@/types/precognition";
import {type InertiaForm, useForm as inertiaUseForm} from "@inertiajs/svelte";
import type { FormDataType, FormDataKeys } from "@inertiajs/core";
import type {Writable} from "svelte/store";
import axios, {AxiosError} from "axios";

const formIntervalMap: Record<string, number> = {};
const lastPrecognitionSent: Record<string, string> = {};

export const useForm = <T extends FormDataType<T>>(
    data: T,
    method: Method,
    url: string,
    debounce: number = 300
): Writable<InertiaForm<T>> => {
    let form = inertiaUseForm(data);
    const formIdentifier = `${method}-${url}`;

    form.subscribe((inertiaForm) => {
        console.log(`${formIdentifier} - Clear timeout`);

        if (formIntervalMap[formIdentifier]) {
            clearTimeout(formIntervalMap[formIdentifier]);
        }

        formIntervalMap[formIdentifier] = setTimeout(async () => {
            if (inertiaForm.isDirty) {
                console.log(`${formIdentifier} - Form dirty`);
                const data = inertiaForm.data();
                const dataJson = JSON.stringify(data);

                if (lastPrecognitionSent[formIdentifier] === dataJson) {
                    console.log(`${formIdentifier} - JSON match, skipping`);
                    return;
                }

                console.log(`${formIdentifier} - Sending precognition request`);
                lastPrecognitionSent[formIdentifier] = dataJson;

                try {
                    await axios[method](
                        url,
                        {
                            ...data,
                            precognitive: "true"
                        },
                        {
                            headers: {
                                "Precognition": "true",
                                "Accept": "application/json",
                            },
                        }
                    );

                    inertiaForm.clearErrors();
                } catch (e) {
                    const err = e as AxiosError<{ errors: Record<string, string[]>}>;

                    if (err.status === 422 && err.response) {
                        inertiaForm.clearErrors()
                        for (const [field, errorsArr] of Object.entries(err.response.data.errors)) {
                            inertiaForm.setError(field as FormDataKeys<T>, errorsArr[0]);
                        }
                    } else {
                        throw e;
                    }
                }

            }
        }, debounce);
    });

    return form;
}
