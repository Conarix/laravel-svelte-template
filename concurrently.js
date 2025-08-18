import { concurrently } from "concurrently";

const { result } = concurrently([
    {
        command: 'npm run dev',
        name: 'vite',
        prefixColor: '#E06C75', // Red
    },
    // {
    //     command: "watchexec -e .php -r 'php artisan horizon'",
    //     name: 'horizon',
    //     prefixColor: '#98C379', // Green
    // },
    {
        command: "watchexec --emit-events-to environment -e php --watch app/Enums -r 'php artisan ts:create-enum $WATCHEXEC_WRITTEN_PATH --force'",
        name: 'Watch Enums',
        prefixColor: '#ABB2BF',
    },
])
