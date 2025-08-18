
export const titleCase = (s: string) =>
    s.replace(/^[-_]*(.)/, (_, c) => c.toUpperCase())
        .replace(/[-_]+(.)/g, (_, c) => ' ' + c.toUpperCase());

export const dateToString = (format: string = "l jS F Y \\a\\t H:i:s") => {
    return (s: string | null) => {
        if (!s) return "N/A";

        let date = new Date(Date.parse(s));

        const days = [
            'sunday',
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
        ];

        const months = [
            'january',
            'february',
            'march',
            'april',
            'may',
            'june',
            'july',
            'august',
            'september',
            'october',
            'november',
            'december',
        ];

        // Default: th
        const ordinalSuffixMap : { [key: string]: string } = {
            '1': 'st',
            '2': 'nd',
            '3': 'rd',
        };

        const valueMap: { [key: string]: string|number } = {
            // Day
            'd': date.getDate().toString().padStart(2, '0'),
            'j': date.getDate().toString(),
            'D': ucfirst(days[date.getDay()].substring(0, 3)),
            'l': ucfirst(days[date.getDay()]),
            'N': date.getDay() ? date.getDay() : 7,
            'S': ordinalSuffixMap[date.getDate().toString().slice(-1)] ?? 'th',
            'w': date.getDay(),

            // Month
            'F': ucfirst(months[date.getMonth()]),
            'm': date.getMonth().toString().padStart(2, '0'),
            'M': ucfirst(months[date.getMonth()].substring(0, 3)),
            'n': date.getMonth(),
            't': (new Date(date.getFullYear(), date.getMonth(), 0)).getDate(),

            // Year
            'L': ((date.getFullYear() % 4 === 0 && date.getFullYear() % 100 !== 0) || date.getFullYear() % 400 === 0) ? 1 : 0,
            'Y': date.getFullYear(),
            'y': date.getFullYear().toString().slice(-2),

            // Time
            'a': date.getHours() >= 12 ? 'pm' : 'am',
            'A': date.getHours() >= 12 ? 'PM' : 'AM',
            'g': date.getHours() > 12 ? date.getHours() - 12 : date.getHours(),
            'G': date.getHours(),
            'h': (date.getHours() > 12 ? date.getHours() - 12 : date.getHours()).toString().padStart(2, '0'),
            'H': date.getHours().toString().padStart(2, '0'),
            'i': date.getMinutes().toString().padStart(2, '0'),
            's': date.getSeconds().toString().padStart(2, '0'),
            'u': date.getMilliseconds() * 1000,
            'v': date.getMilliseconds(),
        }

        let formattedDate = '';
        let skipParse = false;

        for (let char of format) {
            if (char === '\\') {
                skipParse = true;
                continue;
            }

            if (skipParse) {
                formattedDate += char;
                skipParse = false;
                continue;
            }

            formattedDate += (valueMap[char] ?? char).toString();
        }

        return formattedDate;
    }
}

export const ucfirst = (s: string) =>
    s.charAt(0).toUpperCase() + s.slice(1);

export const is_numeric = (s: string) =>
    !isNaN(parseFloat(s)) && isFinite(parseFloat(s));

export const dialogShow = (id: string): void => {
    const dialogElement = document.getElementById(id);
    if (!dialogElement) {
        console.error(`Failed to get dialog: ${id}`);
        return;
    }

    dialogElement.style.display = 'flex';
    dialogElement.focus();
};

export const dialogHide = (id: string): void => {
    const dialogElement = document.getElementById(id);
    if (!dialogElement) {
        console.error(`Failed to get dialog: ${id}`);
        return;
    }

    dialogElement.style.display = 'none';
};
